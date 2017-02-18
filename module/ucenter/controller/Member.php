<?php namespace module\ucenter\controller;

/**
 * 会员中心
 * Class Member
 * @package module\ucenter\controller
 */
class Member extends Auth {

	//会员中心
	public function index() {
		//读取菜单
		$menus = Db::table( 'navigate' )->where( 'entry', 'profile' )->where( 'siteid', SITEID )->get();

		foreach ( $menus as $k => $v ) {
			$menus[ $k ]['css']    = json_decode( $v['css'], true );
		}
		View::with( [ 'menus' => $menus ] );

		return view( $this->template . '/index.html' );

	}
}