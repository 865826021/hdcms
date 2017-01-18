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