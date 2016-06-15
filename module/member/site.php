<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\member;

use module\hdSite;
use system\model\Member;

class site extends hdSite {
	//会员列表
	public function doSiteMemberLists() {
		if ( IS_POST ) {
			//删除用户
		}
		$data = Db::table( 'member' )->where( 'siteid', v( 'site.siteid' ) )->get();
		View::with( 'data', $data );
		View::make( $this->template . '/member_lists.html' );
	}

	//编辑用户信息
	public function doSiteMemberEdit() {
		$uid = q( 'get.uid' );
		if ( IS_POST ) {
			if ( ! Member::save() ) {
				message( Member::getError(), 'back', 'error' );
			}
			message( '编辑用户资料成功', 'back', 'success' );
		}
		if ( ! Member::hasUser( $uid ) ) {
			message( '当前站点中不存在此用户' );
		}
		View::with( 'user', Member::find( $uid ) );
		View::make( $this->template . '/member_edit.html' );
	}

	//添加会员
	public function doSiteMemberPost() {
		if ( IS_POST ) {
			if ( ! $uid = Member::add( $_POST ) ) {
				message( Member::getError(), 'back', 'error' );
			}
			message( '添加会员成功', site_url( 'MemberEdit', [ 'uid' => $uid ] ), 'success' );
		}
		$group = Db::table( 'member_group' )->where( 'siteid', v( 'site.siteid' ) )->get();
		View::with( 'group', $group );
		View::make( $this->template . '/member_post.html' );
	}

	//修改密码
	public function doSiteChangePassword() {
		if ( Member::save() ) {
			message( '密码更新成功', 'back', 'success' );
		}
		message( Member::getError(), 'back', 'error' );

	}

	//删除用户
	public function doSiteDelete() {
		$data = q( 'post.uid', [ ] );
		foreach ( $data as $uid ) {
			if ( Member::hasUser( $uid ) ) {
				Member::delete( $uid );
			}
		}
		message( '删除用户成功', 'back', 'success' );
	}

	//会员字段管理
	public function doSiteFieldlists() {
		if ( IS_POST ) {
			foreach ( $_POST['member_fields'] as $id => $d ) {
				$d['id']      = $id;
				$d['orderby'] = min( 255, intval( $d['orderby'] ) );
				Db::table( 'profile_fields' )->update( $d );
			}
			message( '更新会员字段成功', 'refresh', 'success' );
		}
		$data = Db::table( 'member_fields' )->orderBy( 'orderby', 'DESC' )->orderBy( 'id', 'ASC' )->get();
		View::with( 'data', $data );
		View::make( $this->template . '/fields_lists.html' );
	}

	//会员字段编辑
	public function doSiteFieldPost() {
		$id    = q( 'get.id', 0, 'intval' );
		$field = Db::table( 'member_fields' )->where( 'siteid', v( 'site.siteid' ) )->find( $id );
		if ( empty( $field ) ) {
			message( '你编辑的字段不存在', 'back', 'error' );
		}
		if ( IS_POST ) {
			Db::table( 'member_fields' )->where( 'id', $id )->update( $_POST );
			message( '修改字段成功', site_url( 'FieldLists' ), 'success' );
		}
		View::with( 'field', $field );
		View::make( $this->template . '/field_post.html' );
	}

	//会员组列表
	public function doSiteGroupLists() {
		if ( IS_POST ) {
			if ( empty( $_POST['id'] ) ) {
				message( '请选择会员组后进行操作', 'back', 'error' );
			}

			foreach ( $_POST['id'] as $k => $id ) {
				$data['id']     = $id;
				$data['title']  = $_POST['title'][ $k ];
				$data['credit'] = $_POST['credit'][ $k ];
				$data['rank']   = min( 255, intval( $_POST['rank'][ $k ] ) );
				Db::table( 'member_group' )->update( $data );
			}
			//更改会员组变更设置
			Db::table( 'site_setting' )->where( 'siteid', v( 'site.siteid' ) )->update( [
				'grouplevel' => $_POST['grouplevel']
			] );
			message( '更改会组资料更新成功', 'refresh', 'success' );
		}
		$sql    = "SELECT count(*) as user_count,m.uid,g.* FROM " . tablename( 'member_group' ) . " g LEFT JOIN " . tablename( 'member' ) . " m ON g.id=m.group_id WHERE g.siteid=" . v( 'site.siteid' ) . " GROUP BY g.id ORDER BY g.rank DESC,g.id";
		$groups = Db::query( $sql );
		View::with( 'groups', $groups );
		View::with( 'grouplevel', Db::table( 'site_setting' )->where( 'siteid', v( 'site.siteid' ) )->pluck( 'grouplevel' ) );
		View::make( $this->template . '/group_lists.html' );
	}

	//添加会员组
	public function doSiteGroupPost() {
		if ( IS_POST ) {
			if ( empty( $_POST['title'] ) ) {
				message( '会员组名称不能为空', 'back', 'error' );
			};
			$data['title']     = $_POST['title'];
			$data['credit']    = intval( $_POST['credit'] );
			$data['siteid']    = Session::get( 'siteid' );
			$data['is_system'] = 0;
			$data['rank']      = q( 'post.rank', 0, 'intval' );
			$data['isdefault'] = q( 'post.isdefault', 0, 'intval' );
			$id                = Db::table( 'member_group' )->replaceGetId( $data );
			message( '会员组资料保存成功', site_url( 'GroupPost', [ 'id' => $id ] ) );
		}
		$field = Db::table( 'member_group' )->where( 'id', q( 'get.id' ) )->first();
		View::with( 'field', $field )->make( $this->template . '/group_post.html' );
	}

	//设置默认组
	public function doSiteSetDefaultGroup() {
		$group = Db::table( 'member_group' )->where( 'siteid', v( 'site.siteid' ) )->find( q( 'get.id' ) );
		if ( empty( $group ) ) {
			message( '会员组不存在', '', 'error' );
		}
		if ( $group['isdefault'] == 1 ) {
			message( '该会员组已经是默认的会员组', '', 'error' );
		}
		if ( $group['credit'] != 0 ) {
			message( '默认会员组初始积分必须为0', '', 'error' );
		}
		Db::table( 'member_group' )->update( [ 'isdefault' => 0 ] );
		Db::table( 'member_group' )->where( 'id', $group['id'] )->update( [ 'isdefault' => 1 ] );
		message( '默认会员组设置成功', '', 'success' );
	}
}