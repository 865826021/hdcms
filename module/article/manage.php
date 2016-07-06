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
use system\model\Site;
use system\model\Template;
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
		$data = ( new Template() )->getSiteAllTemplate( SITEID, q( 'get.type' ) );
		View::with( 'data', $data );
		View::make( $this->template . '/manage/template.php' );
	}

	//站点管理
	public function doSiteSite() {
		$data = Db::table( 'web' )->where( 'siteid', '=', SITEID )->get();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['site_info'] = json_decode( $v['site_info'], TRUE );
			$data[ $k ]['url']       = '?a=entry/home&m=article&siteid='.SITEID.'&webid=' . $v['id'];
		}
		View::with( 'data', $data )->make( $this->template . '/manage/site.php' );
	}

	//添加站点
	public function doSiteSitePost() {
		$replyCover  = new ReplyCover();
		$ruleKeyword = new RuleKeyword();
		if ( IS_POST ) {
			$data = json_decode( $_POST['data'], TRUE );
			//添加微站
			$web                 = $data;
			$web['id']           = empty( $web['web_id'] ) ? 0 : $web['web_id'];
			$web['template_name'] = $data['template_name'];
			$web['title']        = $data['name'];
			$web['site_info']    = $_POST['data'];
			$action              = $this->webid ? 'save' : 'add';
			if ( ! $web_id = $this->web->$action( $web ) ) {
				message( $this->web->getError(), 'back', 'error' );
			}
			//添加回复规则
			$data['module'] = 'cover';
			$ruleModel      = new Rule();
			$action         = empty( $data['rid'] ) ? 'add' : 'save';
			if ( ! $rid = $ruleModel->$action( $data ) ) {
				message( $ruleModel->getError(), 'back', 'error' );
			}
			$rid = q( 'get.rid', $rid );
			//添加回复关键词
			$keyword['id']      = empty( $data['keyword_id'] ) ? 0 : $data['keyword_id'];
			$keyword['content'] = $data['keyword'];
			$keyword['rid']     = $rid;
			$keyword['module']  = 'cover';
			$action             = empty( $keyword['id'] ) ? 'add' : 'save';
			if ( ! $ruleKeyword->$action( $keyword ) ) {
				message( $ruleKeyword->getError(), 'back', 'error' );
			}
			//添加封面回复
			$cover['id']          = empty( $data['reply_cover_id'] ) ? 0 : $data['reply_cover_id'];
			$cover['web_id']      = $web_id;
			$cover['rid']         = $rid;
			$cover['module']      = 'site';
			$cover['title']       = $data['name'];
			$cover['description'] = $data['description'];
			$cover['thumb']       = $data['thumb'];
			$cover['url']         = '?a=entry/home&m=article&t=web&siteid=' . SITEID . '&webid=' . $web_id;
			$action               = empty( $data['reply_cover_id'] ) ? 'add' : 'save';
			if ( ! $replyCover->$action( $cover ) ) {
				message( $replyCover->getError(), 'back', 'error' );
			}
			message( '保存站点数据成功', site_url( 'site' ), 'success' );
		}
		if ( $this->webid ) {
			//编辑数据时
			$web                     = $this->web->find( $this->webid );
			$field                   = json_decode( $web['site_info'], TRUE );
			$reply_cover             = $replyCover->where( 'web_id', $web['id'] )->first();
			$field['rid']            = $reply_cover['rid'];
			$field['web_id']         = $web['id'];
			$field['keyword_id']     = $ruleKeyword->where( 'rid', $reply_cover['rid'] )->pluck( 'id' );
			$field['reply_cover_id'] = $reply_cover['id'];
		}
		View::with( 'field', isset( $field ) ? json_encode( $field ) : '' );
		View::make( $this->template . '/manage/sitePost.php' );
	}

	//选择模板
	public function doSiteLoadTpl() {
		$data = ( new Template() )->getSiteAllTemplate();
		View::with( 'data', $data )->make( $this->template . '/manage/loadTpl.php' );
	}

	//删除站点
	public function doSiteDel() {
		if ( ( new Web() )->remove( q( 'get.webid' ) ) ) {
			message( '删除站点成功', '', 'success' );
		} else {
			message( '站点删除失败', '', 'error' );
		}
	}
}