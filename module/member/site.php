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
use system\model\MemberFields;
use system\model\MemberGroup;

/**
 * 会员中心
 * Class site
 * @package module\member
 * @author 向军
 */
class site extends hdSite {

	//会员列表
	public function doSiteMemberLists() {
		$data = Db::table( 'member' )
		          ->join( 'member_group', 'member.group_id', '=', 'member_group.id' )
		          ->where( 'member.siteid', SITEID )
		          ->paginate( 20, 8 );

		return view( $this->template . '/member_lists.html' )->with( 'data', $data );
	}

	//编辑用户信息
	public function doSiteMemberEdit() {
		$uid    = Request::get( 'uid' );
		$member = new Member();
		if ( IS_POST ) {
			foreach ( Request::post() as $k => $v ) {
				$member[ $k ] = $v;
			}
			$member->save();
			message( '编辑用户资料成功', 'back', 'success' );
		}

		return view( $this->template . '/member_edit.html' )->with( 'user', $member->find( $uid ) );
	}

	//添加会员
	public function doSiteMemberPost() {
		if ( IS_POST ) {
			Validate::make( [
				[ 'password', 'confirm:password2', '两次密码输入不一致' ]
			] );
			//保存到模型数据
			$member = new Member();
			foreach ( Request::post() as $k => $v ) {
				$member[ $k ] = $v;
			}
			//密码处理
			$member->setPasswordAndSecurity();
			$uid = $member->save();
			message( '添加会员成功', site_url( 'MemberEdit', [ 'uid' => $uid ] ), 'success' );
		}
		$group = Db::table( 'member_group' )->where( 'siteid', SITEID )->get();

		return view( $this->template . '/member_post.html' )->with( 'group', $group );
	}

	//修改密码
	public function doSiteChangePassword() {
		Validate::make( [
			[ 'password', 'required', '密码不能为空' ],
			[ 'password', 'confirm:password2', '两次密码不一致' ],
		] );
		$member           = new Member();
		$member->uid      = Request::post( 'uid' );
		$member->password = Request::post( 'password' );
		$member->setPasswordAndSecurity();
		$member->save();
		message( '密码更新成功', 'back', 'success' );
	}

	//删除用户
	public function doSiteDelete() {
		$uids   = Request::post( 'uid', [ ] );
		$member = new Member();
		foreach ( $uids as $uid ) {
			$user = $member->where( 'siteid', SITEID )->find( $uid );
			$user->destory();
		}
		message( '删除用户成功', 'back', 'success' );
	}

	//会员字段管理
	public function doSiteFieldlists() {
		if ( IS_POST ) {
			foreach ( Request::post( 'member_fields' ) as $id => $d ) {
				$d['id']      = $id;
				$d['status']  = intval( $d['status'] ) ? 1 : 0;
				$d['orderby'] = min( 255, intval( $d['orderby'] ) );
				Db::table( 'member_fields' )->update( $d );
			}
			message( '更新会员字段成功', 'refresh', 'success' );
		}
		$data = Db::table( 'member_fields' )->orderBy( 'orderby', 'DESC' )->orderBy( 'id', 'ASC' )->get();

		return view( $this->template . '/fields_lists.html' )->with( 'data', $data );;
	}

	//会员字段编辑
	public function doSiteFieldPost() {
		$id    = Request::get( 'id', 0, 'intval' );
		$model = new MemberFields();
		$field = Db::table( 'member_fields' )->where( 'siteid', SITEID )->find( $id );
		if ( empty( $field ) ) {
			message( '你编辑的字段不存在', 'back', 'error' );
		}
		if ( IS_POST ) {
			$model['id']      = Request::get( 'id' );
			$model['orderby'] = Request::post( 'orderby' );
			$model['title']   = Request::post( 'title' );
			$model['status']  = Request::post( 'status' );
			$model->save();
			message( '修改字段成功', site_url( 'FieldLists' ), 'success' );
		}

		return view( $this->template . '/field_post.html' )->with( 'field', $field );
	}

	//会员组列表
	public function doSiteGroupLists() {
		$model = new MemberGroup();
		if ( IS_POST ) {
			foreach ( Request::post( 'id' ) as $k => $id ) {
				$model['id']     = $id;
				$model['title']  = $_POST['title'][ $k ];
				$model['credit'] = $_POST['credit'][ $k ];
				$model['rank']   = min( 255, intval( $_POST['rank'][ $k ] ) );
				$model->save();
			}
			//更改会员组变更设置
			Db::table( 'site_setting' )->where( 'siteid', SITEID )->update( [
				'grouplevel' => $_POST['grouplevel']
			] );
			service( 'site' )->updateCache();
			message( '更改会组资料更新成功', 'refresh', 'success' );
		}
		$sql    = "SELECT count(*) as user_count,m.uid,g.* FROM " . tablename( 'member_group' ) . " g LEFT JOIN " . tablename( 'member' ) . " m ON g.id=m.group_id WHERE g.siteid=" . SITEID . " GROUP BY g.id ORDER BY g.rank DESC,g.id";
		$groups = Db::query( $sql );
		View::with( 'groups', $groups );
		View::with( 'grouplevel', v( 'site.setting.grouplevel' ) );

		return view( $this->template . '/group_lists.html' );
	}

	//删除组
	public function doSiteDelGroup() {
		$id    = Request::get( 'id' );
		$model = model( 'MemberGroup' )->find( $id );
		//默认组编号
		$defaultId = service( 'member' )->defaultGruopId();
		if ( $id == $defaultId ) {
			message( '默认组不能删除', 'back', 'error' );
		}
		//更改用户默认组
		Db::table( 'member' )->where( 'siteid', SITEID )->where( 'group_id', $id )->update( [ 'group_id' => $defaultId ] );
		$model->destory();
		message( '删除组成功', '', 'success' );
	}

	//添加会员组
	public function doSiteGroupPost() {
		$model = new MemberGroup();
		$id    = Request::get( 'id' );
		if ( IS_POST ) {
			Validate::make( [
				[ 'title', 'required', '会员组名称不能为空' ],
				[ 'credit', 'required', '积分数量不能为空' ],
			] );
			$model['id']     = $id;
			$model['title']  = Request::post( 'title' );
			$model['credit'] = Request::post( 'credit' );
			$model->save();
			message( '会员组资料保存成功', site_url( 'GroupLists' ) );
		}
		$field = Db::table( 'member_group' )->where( 'id', $id )->first();

		return view( $this->template . '/group_post.html' )->with( 'field', $field );
	}

	//设置默认组
	public function doSiteSetDefaultGroup() {
		$model = new MemberGroup();
		$id    = q( 'get.id' );
		$group = $model->where( 'siteid', SITEID )->find( $id );
		if ( empty( $group ) ) {
			message( '会员组不存在', '', 'error' );
		}
		if ( $group['credit'] != 0 ) {
			message( '默认会员组初始积分必须为0', '', 'error' );
		}
		$model->update( [ 'isdefault' => 0 ] );
		if ( ! $model->save( [ 'id' => $id, 'isdefault' => 1 ] ) ) {
			message( $model->getError(), 'back', 'error' );
		}
		message( '默认会员组设置成功', '', 'success' );
	}

	//修改会员积分/余额
	public function doSiteTrade() {
		//会员编号
		$uid = Request::get( 'uid' );
		//1 积分 2 余额
		$type = Request::get( 'type' );
		if ( IS_POST ) {
			Validate::make( [
				[ 'remark', 'required', '备注不能为空' ],
				[ 'remark', 'num', '更改数量必须为数字' ],
			] );
			//更改数量
			$data               = [ ];
			$data['uid']        = $uid;
			$data['credittype'] = $type;
			$data['num']        = Request::post( 'num' );
			$data['operator']   = v( 'user.info.uid' );
			$data['remark']     = Request::post( 'remark' );
			service( 'credit' )->changeCredit( $data );
			message( v( "setting.creditnames.$type.title" ) . '更改成功', '', 'success' );
		}

		return view( $this->template . '/trade.html' )->with( [ 'uid' => $uid, 'type' => $type ] );
	}
}