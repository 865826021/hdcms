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

/**
 * 会员中心代金券处理
 * Class ticket
 * @package uc\controller
 */
class ticket extends hdSite {
	//列表
	public function doWebLists() {
		$uid = Session::get( 'member.uid' );
		//获取用户卡券
		switch ( $_GET['status'] ) {
			case 1:
			case 2:
				$sql = "SELECT t.tid,count(t.tid) as nums,t.discount,t.condition,t.tid,t.starttime,t.endtime,t.title,t.limit,t.thumb,tr.status,tr.uid,tr.remark ";
				$sql .= "FROM " . tablename( 'ticket' ) . " t JOIN " . tablename( 'ticket_record' ) . " tr ON t.tid=tr.tid ";
				$sql .= "WHERE tr.uid={$uid} AND t.type={$_GET['type']} AND tr.status={$_GET['status']} GROUP BY t.tid";
				break;
			case 3:
				//过期
				$sql = "SELECT t.tid,count(t.tid) as nums,t.discount,t.condition,t.tid,t.starttime,t.endtime,t.title,t.limit,t.thumb,tr.status,tr.uid,tr.remark ";
				$sql .= "FROM " . tablename( 'ticket' ) . " t JOIN " . tablename( 'ticket_record' ) . " tr ON t.tid=tr.tid ";
				$sql .= "WHERE tr.uid={$uid} AND t.type={$_GET['type']} AND t.endtime<" . time() . " GROUP BY t.tid";
		}

		$data = Db::query( $sql );
		View::with( 'data', $data );
		View::make( 'ucenter/ticket_lists.html' );
	}

	//兑换
	public function doWebConvert() {
		if ( IS_POST ) {
			$tid = q( 'post.tid' );
			Db::lock( 'ticket_record,ticket,member' );
			//会员已经兑换的数量
			$count = Db::table( 'ticket_record' )->where( 'tid', $tid )->where( 'uid', Session::get( 'member.uid' ) )->count();
			//卡券信息
			$ticket = Db::table( 'ticket' )->where( 'tid', $tid )->first();
			if ( $count > $ticket['limit'] ) {
				message( '卡券已经全部兑换完毕', '', 'error' );
			}
			//执行兑换,首先看会员积分数量够不够了..:)
			$user = Db::table( 'member' )->where( 'uid', Session::get( 'member.uid' ) )->first();
			if ( $user['credit1'] < $ticket['credit'] ) {
				message( '您的积分不够,不能兑换哟', '', 'error' );
			}
			//减掉会员积分
			Db::table( "member" )->where( 'uid', Session::get( 'member.uid' ) )->decrement( 'credit1', $ticket['credit'] );
			//增加会员卡券记录
			$data['tid']        = $ticket['tid'];
			$data['uid']        = Session::get( 'member.uid' );
			$data['siteid']     = SITEID;
			$data['createtime'] = time();
			$data['usetime']    = 0;
			$data['module']     = '';
			$data['remark']     = '';
			$data['status']     = 1;//1 兑换 2 核销
			$data['manage']     = 0;//管理员
			Db::table( 'ticket_record' )->insert( $data );
			message( '卡券兑换成功', '', 'success' );
		}
		$sql = "SELECT t.tid,t.starttime,t.endtime,t.description,t.title,t.limit,t.thumb FROM " . tablename( 'ticket' );
		$sql .= " t LEFT JOIN " . tablename( 'ticket_record' ) . " tr ON t.tid=tr.tid " . "WHERE t.starttime<" . time() . " AND t.endtime>" . time();
		$sql .= " AND t.type={$_GET['type']}" . " AND t.siteid=" . SITEID . " GROUP BY t.tid HAVING count(tr.id) < t.limit";
		$data = Db::query( $sql );
		View::with( 'data', $data );
		View::make( 'ucenter/ticket_convert.html' );
	}

	//使用卡券
	public function doWebEmploy() {
		$tid = q( 'get.tid', 0, 'intval' );
		//会员卡卷记录
		$ticket = Db::table( 'ticket' )->where( 'tid', $tid )->first();
		View::with( 'ticket', $ticket );
		View::make( 'ucenter/ticket_employ.html' );
	}
}