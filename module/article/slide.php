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
use system\model\Web;
use system\model\WebSlide;

/**
 * 站点幻灯图
 * Class slide
 * @package module\article
 */
class slide extends hdSite {
	//官网编号
	protected $webid;
	protected $web;
	protected $webSlide;

	public function __construct() {
		parent::__construct();
		$this->web      = new Web();
		$this->webSlide = new WebSlide();
		//官网编号
		$this->webid = Request::get( 'webid' );
		if ( ! $this->web->where( 'id', $this->webid )->get() ) {
			message( '你访问的官网不存在', 'back', 'error' );
		}
	}




	//删除
	public function doSiteRemove() {
		$this->webSlide->delete( Request::get( 'id' ) );
		message( '删除幻灯片成功', 'back', 'success' );
	}
}