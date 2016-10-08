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
use system\model\Rule;
use system\model\RuleKeyword;
use system\model\Web;

/**
 * 模板列表
 * Class manage
 * @package module\article
 */
class manage extends hdSite {
	protected $web;
	protected $webid;

	public function __construct() {
		parent::__construct();
		$this->web   = new Web();
		$this->webid = q( 'get.webid' );
		if ( $this->webid && ! $this->web->where( 'siteid', SITEID )->where( 'id', $this->webid )->get() ) {
			message( '站点不存在', 'back', 'error' );
		}
	}

	//选择模板
	public function doSiteTemplate() {
		$data = service( 'template' )->getSiteAllTemplate( SITEID, q( 'get.type' ) );

		return view( $this->template . '/manage/template.php' )->with( [ 'data' => $data ] );
	}

	//站点管理
	public function doSiteSite() {
		$data = Db::table( 'web' )->where( 'siteid', SITEID )->get() ?: [ ];
		foreach ( $data as $k => $v ) {
			$data[ $k ]['site_info'] = json_decode( $v['site_info'], TRUE );
			$data[ $k ]['url']       = '?a=entry/home&m=article&siteid=' . SITEID . '&webid=' . $v['id'];
		}

		return View::with( 'data', $data )->make( $this->template . '/manage/site.php' );
	}

	//添加站点
	public function doSiteSitePost() {
		if ( IS_POST ) {
			$data              = json_decode( $_POST['data'], TRUE );
			$data['site_info'] = $_POST['data'];
			$insertId          = $this->web->save( $data );
			$web['id']         = $this->webid ?: $insertId;
			//添加回复规则
			$rule             = [ ];
			$rule['rid']      = Db::table( 'reply_cover' )->where( 'web_id', $web['id'] )->pluck( 'rid' );
			$rule['module']   = 'cover';
			$rule['name']     = '微站:'.$data['title'];
			$rule['keywords'] = [
				[
					'content' => $data['keyword'],
				]
			];
			$rid              = service( 'WeChat' )->rule( $rule );
			//添加封面回复
			$replyCover = new ReplyCover();
			$replyCover->where( 'rid', $rid )->delete();
			$data['web_id'] = $web['id'];
			$data['rid']    = $rid;
			$data['module'] = 'article';
			$data['url']    = '?a=entry/home&m=article&t=web&siteid=' . SITEID . '&webid=' . $web['id'];
			$replyCover->save( $data );
			message( '保存站点数据成功', site_url( 'site' ), 'success' );
		}
		if ( $this->webid ) {
			//编辑数据时
			$web         = $this->web->find( $this->webid );
			$field       = json_decode( $web['site_info'], TRUE );
			$field['id'] = $this->webid;
		}
		View::with( 'field', isset( $field ) ? json_encode( $field, JSON_UNESCAPED_UNICODE ) : '' );

		return View::make( $this->template . '/manage/sitePost.php' );
	}

	//选择模板
	public function doSiteLoadTpl() {
		$data = service( 'template' )->getSiteAllTemplate();

		return view( $this->template . '/manage/loadTpl.php' )->with( 'data', $data );
	}

	//删除微站
	public function doSiteDel() {
		service( 'web' )->del( Request::get( 'webid' ) );
		message( '删除站点成功', '', 'success' );
	}
}