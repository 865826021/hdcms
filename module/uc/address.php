<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\uc;

use module\hdSite;
use system\model\MemberAddress;

/**
 * 地址管理
 * Class address
 * @package member\address
 * @author 向军
 */
class address extends hdSite {
	protected $db;

	public function __construct() {
		//登录检测
		Util::instance( 'Member' )->isLogin();
		parent::__construct();
		$this->db = new MemberAddress();
	}

	//地址列表
	public function doWebLists() {
		$data = Db::table( 'member_address' )
		          ->orderBy( 'isdefault', 'DESC' )
		          ->orderBy( 'id', 'asc' )
		          ->where( 'siteid', SITEID )
		          ->where( 'uid', Session::get( 'member.uid' ) )
		          ->get();
		View::with( 'data', $data );
		View::make( $this->ucenter_template . '/address_lists.html' );
	}

	//设置地址
	public function doWebPost() {

		if ( IS_POST ) {
			//不存在默认地址时将此地址设置为默认
			if ( ! $this->db->getMemberDefaultAddress() ) {
				$_POST['isdefault'] = 1;
			}
			$action = empty( $_POST['id'] ) ? 'add' : 'save';
			if ( $this->db->$action() ) {
				message( '保存地址成功', web_url( 'lists' ), 'success' );
			}
			message( $this->db->getError(), 'back', 'error' );
		}
		if ( $id = q( 'get.id', 0, 'intval' ) ) {
			View::with( 'field', $this->db->find( $id ) );
		}
		View::make( $this->ucenter_template . '/address_post.html' );
	}

	//修改默认地址
	public function doWebChangeDefault() {
		$id = q( 'id', 0, 'intval' );
		$ad = Db::table( 'member_address' )->where( 'uid', Session::get( 'member.uid' ) )->where( 'siteid', SITEID )->where( 'id', $id )->first();
		if ( $ad ) {
			//删除原来的默认地址
			Db::table( 'member_address' )->where( 'uid', Session::get( 'member.uid' ) )->where( 'siteid', SITEID )->update( [ 'isdefault' => 0 ] );
			//将当前地址设置为默认
			Db::table( 'member_address' )->where( 'id', $id )->update( [ 'isdefault' => 1 ] );
		}
	}

	//删除地址
	public function doWebRemove() {
		$id = q( 'id', 0, 'intval' );
		$ad = $this->db->where( 'uid', Session::get( 'member.uid' ) )->where( 'siteid', SITEID )->where( 'id', $id )->first();
		if ( $ad ) {
			//删除地址
			Db::table( 'member_address' )->where( 'id', $id )->delete();
			//如果删除的是默认地址,重新设置地址
			if ( $ad['isdefault'] ) {
				Db::table( 'member_address' )
				  ->where( 'uid', Session::get( 'member.uid' ) )
				  ->where( 'siteid', SITEID )
				  ->orderBy( 'id', 'asc' )
				  ->limit( 1 )
				  ->update( [ 'isdefault' => 1 ] );
			}
		}
		message( '地址不存在', 'back', 'error' );
	}
}