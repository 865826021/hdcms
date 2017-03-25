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
		$module = Db::table( 'navigate' )->where( 'entry', 'member' )->where( 'siteid', SITEID )->lists( 'module' );
		$data   = [];
		foreach ( $module as $m ) {
			if ( $moduleData = Db::table( 'modules' )->where( 'name', $m )->first() ) {
				$data[] = [
					'module' => $moduleData,
					'menus'  => Db::table( 'navigate' )->where( 'entry', 'member' )->where( 'module', $m )->where( 'siteid', SITEID )->get()
				];
			}
		}
		foreach ( $data as $k => $v ) {
			foreach ( $v['menus'] as $n => $m ) {
				$data[ $k ]['menus'][ $n ]['css'] = json_decode( $m['css'], true );
			}
		}
//p(v('member'));
		View::with( [ 'data' => $data ] );

		return view( $this->template . '/index.html' );

	}
}