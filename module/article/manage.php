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
		$data = Db::table( 'web' )->where( 'siteid', SITEID )->get();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['site_info'] = json_decode( $v['site_info'], TRUE );
			$data[ $k ]['url']       = '?a=entry/home&m=article&siteid=' . SITEID . '&webid=' . $v['id'];
		}

		return View::with( 'data', $data )->make( $this->template . '/manage/site.php' );
	}

	//添加站点
	public function doSiteSitePost() {
		$replyCover  = new ReplyCover();
		$ruleKeyword = new RuleKeyword();
		if ( IS_POST ) {
			$data = json_decode( $_POST['data'], TRUE );
			//添加微站
			$this->web['id']            = empty( $data['web_id'] ) ? 0 : $data['web_id'];
			$this->web['template_name'] = $data['template_name'];
			$this->web['title']         = $data['name'];
			$this->web['site_info']     = $_POST['data'];
			$this->web['thumb']         = $data['thumb'];
			$this->web['template_tid']  = $data['template_tid'];
			$insertId                   = $this->web->save();
			//站点编号
			$web['id'] = $this->web['id'] ?: $insertId;
			//添加回复规则
			$data['module']   = 'cover';
			$ruleModel        = new Rule();
			$ruleModel['rid'] = empty( $data['rid'] ) ? 0 : $data['rid'];
			$insertRid        = $ruleModel->save();
			$rid              = $ruleModel['rid'] ?: $insertRid;
			//添加回复关键词
			$ruleKeyword['id']      = empty( $data['keyword_id'] ) ? 0 : $data['keyword_id'];
			$ruleKeyword['content'] = $data['keyword'];
			$ruleKeyword['rid']     = $rid;
			$ruleKeyword['module']  = 'cover';
			$ruleKeyword->save();
			//添加封面回复
			$replyCover['id']          = empty( $data['reply_cover_id'] ) ? 0 : $data['reply_cover_id'];
			$replyCover['web_id']      = $web['id'];
			$replyCover['rid']         = $rid;
			$replyCover['module']      = 'article';
			$replyCover['title']       = $data['name'];
			$replyCover['description'] = $data['description'];
			$replyCover['thumb']       = $data['thumb'];
			$replyCover['url']         = '?a=entry/home&m=article&t=web&siteid=' . SITEID . '&webid=' . $web['id'];
			$replyCover->save();
			message( '保存站点数据成功', site_url( 'site' ), 'success' );
		}
		if ( $this->webid ) {
			//编辑数据时
			$web                     = $this->web->find( $this->webid );
			$field                   = json_decode( $web['site_info'], TRUE );
			$reply_cover             = $replyCover->where( 'web_id', $this->webid )->first();
			$field['rid']            = $reply_cover['rid'];
			$field['web_id']         = $web['id'];
			$field['keyword_id']     = $ruleKeyword->where( 'rid', $reply_cover['rid'] )->pluck( 'id' );
			$field['reply_cover_id'] = $reply_cover['id'];
		}
		View::with( 'field', isset( $field ) ? json_encode( $field ) : '' );

		return View::make( $this->template . '/manage/sitePost.php' );
	}

	//选择模板
	public function doSiteLoadTpl() {
		$data = service( 'template' )->getSiteAllTemplate();

		return view( $this->template . '/manage/loadTpl.php' )->with( 'data', $data );
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