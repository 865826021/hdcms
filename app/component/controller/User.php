<?php namespace app\component\controller;

/**
 * 用户
 * Class User
 * @package app\component\controller
 */
class User {
	/**
	 * 选择用户
	 * @return mixed
	 */
	public function users() {
		\User::loginAuth();
		if ( IS_POST ) {
			//过滤不显示的用户
			$filterUid = explode( ',', q( 'get.filterUid', '' ) );
			$db        = Db::table( 'user' )->join( 'user_group', 'user.groupid', '=', 'user_group.id' );
			//排除站长
			if ( ! empty( $filterUid ) ) {
				$db->whereNotIn( 'uid', $filterUid );
			}
			//按用户名筛选
			if ( empty( $_GET['username'] ) ) {
				$users = $db->get();
			} else {
				$users = $db->where( "username LIKE '%{$_GET['username']}%'" )->get();
			}
			ajax( $users );
		}

		return view();
	}
}