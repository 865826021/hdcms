<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\article;

use module\hdSite;
use system\model\ReplyCover;
use system\model\ReplyNews;
use system\model\Rule;
use system\model\RuleKeyword;
use system\model\Template;
use system\model\Web;
use system\model\WebArticle;
use system\model\WebCategory;
use system\model\WebNav;
use web\site;

/**
 * 模板列表
 * Class content
 * @package module\article
 */
class content extends hdSite {
	protected $cid;
	protected $webCategory;
	protected $webArticle;

	public function __construct() {
		parent::__construct();
		$this->cid         = Request::get( 'cid' );
		$this->webCategory = new WebCategory();
		$this->webArticle  = new WebArticle();
		if ( $this->cid && ! $this->webCategory->where( 'siteid', SITEID )->where( 'cid', $this->cid )->get() ) {
			message( '栏目不存在或不属于该站点', 'back', 'error' );
		}
		//站点检测
		$web = Db::table( 'web' )->where( 'siteid', SITEID )->get();
		if ( empty( $web ) ) {
			message( '需要添加站点才可以执行操作', site_url( 'manage/SitePost' ), 'error' );
		}
	}

	//添加栏目
	public function doSiteCategory() {
		$data = Db::table( 'web_category' )->where( 'siteid', SITEID )->orderBy( 'cid', 'ASC' )->get() ?: [ ];
		foreach ( $data as $k => $v ) {
			$data[ $k ]['url'] = __ROOT__ . '/index.php?s=content/home/category&siteid=' . v( 'site.siteid' ) . '&cid=' . $v['cid'];
		}
		$data = Data::tree( $data, 'title', 'cid', 'pid' );
		View::with( 'data', $data );

		return View::make( $this->template . '/content/category.php' );
	}

	//栏目修改
	public function doSiteCategoryPost() {
		if ( IS_POST ) {
			$data      = json_decode( $_POST['data'], TRUE );
			$insertCid = $this->webCategory->save( $data );
			$cid       = $this->cid ?: $insertCid;
			//是添加微站首页导航
			$webNav = new WebNav();
			if ( ! empty( $data['web_id'] ) ) {
				$data['name']         = $data['title'];
				$data['url']          = $data['linkurl'] ?: '?a=entry/category&t=web&m=article&siteid=' . SITEID . '&cid=' . $data['cid'];
				$data['entry']        = 'home';
				$data['category_cid'] = $cid;
				$webNav->save( $data );
			} else {
				$webNav->where( 'category_cid', $cid )->delete();
			}
			message( '栏目保存成功', site_url( 'category' ), 'success' );
		}
		//栏目列表,用于添加栏目时指定父级栏目
		$category = $this->webCategory->getLevelCategory( $this->cid );
		if ( $this->cid ) {
			//编辑时获取旧数据
			$field        = Db::table( 'web_category' )->where( 'cid', $this->cid )->first();
			$field['css'] = json_decode( $field['css'], TRUE );
			//栏目模板
			$template = Db::table( 'template' )->where( 'name', $field['template_name'] )->first() ?: [ ];
		}
		$web = Db::table( 'web' )->where( 'siteid', SITEID )->get();
		View::with( 'web', Arr::string_to_int( $web ) );
		View::with( 'field', Arr::string_to_int( $field ) );
		View::with( 'category', Arr::string_to_int( $category ) );
		View::with( 'template', Arr::string_to_int( $template ) );

		return View::make( $this->template . '/content/categoryPost.php' );
	}

	//删除栏目
	public function doSiteRemoveCat() {
		if ( Db::table( 'web_category' )->where( 'pid', $this->cid )->get() ) {
			ajax( [ 'valid' => 0, 'message' => '请先删除子栏目' ] );
		}
		Db::table( 'web_category' )->where( 'cid', $this->cid )->delete();
		Db::table( 'web_nav' )->where( 'category_cid', $this->cid )->delete();
		ajax( [ 'valid' => 1, 'message' => '栏目删除成功' ] );
	}

	//文章管理
	public function doSiteArticle() {
		$data = Db::table( 'web_article' )->where( 'siteid', SITEID )->paginate( 10, 8 );

		return view( $this->template . '/content/article.php' )->with( 'data', $data );
	}

	//文章管理
	public function doSiteArticlePost() {
		$aid = Request::get( 'aid' );
		if ( $aid && ! Db::table( 'web_article' )->find( $aid ) ) {
			message( '文章不存在', 'back', 'error' );
		}
		if ( IS_POST ) {
			$data     = json_decode( $_POST['data'], TRUE );
			$insertId = $this->webArticle->save( $data );
			$aid      = $aid ?: $insertId;
			//回复规则与回复关键词设置
			if ( ! empty( $data['keyword'] ) && ! empty( $data['thumb'] ) ) {
				$rule['rid']    = Db::table( 'reply_cover' )->where( 'module', 'article:aid:' . $aid )->pluck( 'rid' );
				$rule['name']   = "微站文章:{$aid}";
				$rule['module'] = 'news';
				//添加回复关键词
				$rule['keywords'] = [ [ 'content' => $data['keyword'] ] ];
				$rid              = service( 'WeChat' )->rule( $rule );
				//添加封面回复
				$ReplyNews      = new ReplyNews();
				$data['rid']    = $rid;
				$data['module'] = 'article:aid:' . $aid;
				$data['url']    = $data['linkurl'] ?: '?a=entry/content&m=article&t=web&siteid=' . SITEID . '&aid=' . $aid;
				$ReplyNews->save( $data );
				$this->webArticle->update( [ 'aid' => $aid, 'rid' => $rid ] );
			}
			message( '保存文章成功', site_url( 'article', [ 'cid' => $data['category_cid'] ] ), 'success' );
		}
		//栏目列表
		$category = $this->webCategory->getLevelCategory();
		if ( empty( $category ) ) {
			message( '请先添加文章类别', 'category', 'error' );
		}
		//所有模板数据用于设置文章模板
		$template = service( 'template' )->getSiteAllTemplate();
		//获取文章数据
		if ( $aid ) {
			$field = DB::table( 'web_article' )->find( $aid );
		}
		View::with( 'field', Arr::string_to_int( $field ) );
		View::with( 'template', Arr::string_to_int( $template ) );
		View::with( 'category', Arr::string_to_int( $category ) );

		return View::make( $this->template . '/content/articlePost.php' );
	}

	//删除文章
	public function doSiteArticleDel() {ato
		model( 'WebArticle' )->del( Request::get( 'aid' ) );
		message( '文章删除文章成功', 'back', 'success' );
	}

	//文章排序
	public function doSiteArticleOrder() {
		$data = json_decode( $_POST['data'], TRUE );
		foreach ( $data as $a ) {
			$orderby = min( 255, intval( $a['orderby'] ) );
			Db::table( 'web_article' )->where( 'aid', '=', $a['aid'] )->update( [ 'orderby' => $orderby ] );
		}
		message( '修改文章排序成功', 'back', 'success' );
	}

}