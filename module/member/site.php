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
use system\model\MemberGroup;

/**
 * 会员中心
 * Class site
 * @package module\member
 * @author 向军
 */
class site extends hdSite {
	protected $db;

	public function __construct() {
		parent::__construct();
		$this->db = new Member();
	}

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
			if ( ! $this->db->save() ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			message( '编辑用户资料成功', 'back', 'success' );
		}
		if ( ! $this->db->hasUser( $uid ) ) {
			message( '当前站点中不存在此用户' );
		}
		View::with( 'user', $this->db->find( $uid ) );
		View::make( $this->template . '/member_edit.html' );
	}

	//添加会员
	public function doSiteMemberPost() {
		if ( IS_POST ) {
			if ( ! $uid = $this->db->add() ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			message( '添加会员成功', site_url( 'MemberEdit', [ 'uid' => $uid ] ), 'success' );
		}
		$group = Db::table( 'member_group' )->where( 'siteid', v( 'site.siteid' ) )->get();
		View::with( 'group', $group );
		View::make( $this->template . '/member_post.html' );
	}

	//修改密码
	public function doSiteChangePassword() {
		if ( $this->db->save() ) {
			message( '密码更新成功', 'back', 'success' );
		}
		message( $this->db->getError(), 'back', 'error' );

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
		$model = new MemberGroup();
		if ( IS_POST ) {
			if ( empty( $_POST['id'] ) ) {
				message( '没有可操作的会员组', 'back', 'error' );
			}
			foreach ( $_POST['id'] as $k => $id ) {
				$data['id']     = $id;
				$data['title']  = $_POST['title'][ $k ];
				$data['credit'] = $_POST['credit'][ $k ];
				$data['rank']   = min( 255, intval( $_POST['rank'][ $k ] ) );
				if ( ! $model->save( $data ) ) {
					message( $model->getError(), 'back', 'error' );
				}
			}
			//更改会员组变更设置
			Db::table( 'site_setting' )->where( 'siteid', v( 'site.siteid' ) )->update( [
				'grouplevel' => $_POST['grouplevel']
			] );
			m( 'Site' )->updateSiteCache();
			message( '更改会组资料更新成功', 'refresh', 'success' );
		}
		$sql    = "SELECT count(*) as user_count,m.uid,g.* FROM " . tablename( 'member_group' ) . " g LEFT JOIN " . tablename( 'member' ) . " m ON g.id=m.group_id WHERE g.siteid=" . v( 'site.siteid' ) . " GROUP BY g.id ORDER BY g.rank DESC,g.id";
		$groups = Db::query( $sql );
		View::with( 'groups', $groups );
		View::with( 'grouplevel', v( 'setting.grouplevel' ) );
		View::make( $this->template . '/group_lists.html' );
	}

	//删除组
	public function doSiteDelGroup() {
		$id    = q( 'get.id' );
		$model = new MemberGroup();
		//默认组编号
		$defaultId = $model->getDefaultGroup();
		if ( $id == $defaultId ) {
			message( '默认组不能删除', 'back', 'error' );
		}
		//更改用户默认组
		$this->db->where( 'siteid', v( 'site.siteid' ) )->where( 'group_id', $id )->update( [ 'group_id' => $defaultId ] );
		$model->where( 'id', $id )->delete();
		message( '删除组成功', '', 'success' );
	}

	//添加会员组
	public function doSiteGroupPost() {
		$model = new MemberGroup();
		if ( IS_POST ) {
			if ( empty( $_POST['title'] ) ) {
				message( '会员组名称不能为空', 'back', 'error' );
			};
			$action = q( 'get.id' ) ? 'save' : 'add';
			if ( ! $model->$action() ) {
				message( $model->getError(), 'back', 'error' );
			}
			message( '会员组资料保存成功', site_url( 'GroupLists' ) );
		}
		$field = Db::table( 'member_group' )->where( 'id', q( 'get.id' ) )->first();
		View::with( 'field', $field )->make( $this->template . '/group_post.html' );
	}

	//设置默认组
	public function doSiteSetDefaultGroup() {
		$model = new MemberGroup();
		$id    = q( 'get.id' );
		$group = $model->where( 'siteid', v( 'site.siteid' ) )->find( $id );
		if ( empty( $group ) ) {
			message( '会员组不存在', '', 'error' );
		}
		if ( $group['isdefault'] == 1 ) {
			message( '该会员组已经是默认的会员组', '', 'error' );
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
		$uid = q( 'uid' );
		//1 积分 2 余额
		$type = q( 'type' );
		if ( IS_POST ) {
			if ( empty( $_POST['remark'] ) ) {
				message( '备注不能为空', '', 'error' );
			}
			if ( ! is_numeric( $_POST['num'] ) ) {
				message( '更改数量必须为数字', '', 'error' );
			}
			//更改数量
			$num       = q( 'post.num' );
			$user      = Db::table( 'member' )->where( 'uid', $uid )->first();
			$newCredit = $user[ $type ] + $num;
			if ( $newCredit < 0 ) {
				message( '你的积分数量不够', '', 'error' );
			}
			$data[ $type ] = $newCredit;
			if ( Db::table( 'member' )->where( 'uid', intval( $uid ) )->update( $data ) ) {
				Db::table( 'credits_record' )->insert( [
					'siteid'     => SITEID,
					'uid'        => $uid,
					'credittype' => $type,
					'num'        => $num,
					'operator'   => Session::get( 'user.uid' ),
					'module'     => 'system',
					'createtime' => time(),
					'remark'     => q( 'post.remark' )
				] );
				//更改会员session数据
				Util::updateMemberSessionCache();
			}
			message( v( "setting.creditnames.$type.title" ) . '更改成功', '', 'success' );
		}
		View::with( 'uid', $uid );
		View::with( 'type', $type );
		View::make( $this->template . '/trade.html' );
	}
}