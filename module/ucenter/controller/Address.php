<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\ucenter\controller;

use houdunwang\validate\Validate;
use system\model\Member;
use system\model\MemberAddress;

/**
 * 地址管理
 * Class Address
 * @package module\ucenter\controller
 */
class Address extends Auth {
	/**
	 * 修改手机号
	 */
	public function lists() {
		$data = MemberAddress::orderBy( 'isdefault', 'DESC' )
		                     ->orderBy( 'id', 'asc' )
		                     ->where( 'siteid', SITEID )
		                     ->where( 'uid', v( 'member.info.uid' ) )
		                     ->get();
		View::with( 'data', $data );

		return View::make( $this->template . '/address_lists.html' );
	}

	//设置地址
	public function post() {
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			$model        = $id ? MemberAddress::find( $id ) : new MemberAddress();
			$model['uid'] = v( 'member.info.uid' );
			//不存在默认地址时将此地址设置为默认
			if ( ! \Member::getDefaultAddress() ) {
				$model['isdefault'] = 1;
			}
			if ( $model->save( Request::post() ) ) {
				message( '保存地址成功', url( 'address.lists' ), 'success' );
			}
			message( $model->getError(), 'back', 'error' );
		}
		if ( $id ) {
			View::with( 'field', MemberAddress::find( $id ) );
		}

		return View::make( $this->template . '/address_post.html' );
	}

	//设置默认地址
	public function changeDefault() {
		$id = Request::get( 'id' );
		$ad = MemberAddress::where( 'uid', v( 'member.info.uid' ) )->where( 'siteid', SITEID )->where( 'id', $id )->first();
		if ( $ad ) {
			//删除原来的默认地址
			MemberAddress::where( 'uid', v( 'member.info.uid' ) )->where( 'siteid', SITEID )->update( [ 'isdefault' => 0 ] );
			//将当前地址设置为默认
			MemberAddress::where( 'id', $id )->update( [ 'isdefault' => 1 ] );
			message( '默认地址设置成功', '', 'success' );
		}
		message( '地址不存在', '', 'error' );
	}

	//删除地址
	public function Remove() {
		$id    = Request::get( 'id' );
		$model = MemberAddress::where( 'uid', v( 'member.info.uid' ) )->where( 'siteid', SITEID )->where( 'id', $id )->first();
		if ( $model ) {
			//删除地址
			$model->destory();
			//如果删除的是默认地址,重新设置地址
			if ( $model['isdefault'] ) {
				MemberAddress::where( 'uid', v( 'member.info.uid' ) )
				             ->where( 'siteid', SITEID )
				             ->orderBy( 'id', 'asc' )
				             ->limit( 1 )
				             ->update( [ 'isdefault' => 1 ] );
			}
			message( '地址删除成功', '', 'success' );
		}
		message( '地址不存在', '', 'error' );
	}
}