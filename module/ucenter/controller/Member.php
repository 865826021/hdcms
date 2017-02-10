<?php namespace module\ucenter\controller;

use module\HdController;
use system\model\Navigate;
use system\model\Page;
use system\model\ReplyCover;

/**
 * 会员中心
 * Class Member
 * @package module\ucenter\controller
 */
class Member extends HdController {
	public function __construct() {
		parent::__construct();
		$this->template = "ucenter/" . v( 'site.info.ucenter_template' ) . '/' . ( IS_MOBILE ? 'mobile' : 'web' );
		template_path( $this->template );
		template_url( __ROOT__ . '/' . $this->template );
		//来源页面
		if ( $from = Request::get( 'from' ) ) {
			Session::set( 'from', $from );
		}
	}

	//会员中心
	public function home() {
		return view( $this->template . '/home.html' );
	}
}