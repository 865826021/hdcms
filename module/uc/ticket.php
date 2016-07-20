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
use system\model\Member;

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
		View::make( $this->ucenter_template . '/ticket_lists.html' );
	}

	//兑换
	public function doWebConvert() {
		if ( IS_POST ) {
			$TicketModel = new \system\model\Ticket();
			//兑换卡券
			if ( ! $TicketModel->convert( q( 'post.tid' ) ) ) {
				message( $TicketModel->getError(), 'back', 'error' );
			}
			message( '兑换成功', '', 'success' );
		}
		$sql = "SELECT t.tid,t.credit,t.starttime,t.credittype,t.endtime,t.description,t.title,t.limit,t.thumb FROM " . tablename( 'ticket' );
		$sql .= " t LEFT JOIN " . tablename( 'ticket_record' ) . " tr ON t.tid=tr.tid " . "WHERE t.starttime<" . time() . " AND t.endtime>" . time();
		$sql .= " AND t.type={$_GET['type']}" . " AND t.siteid=" . SITEID . " GROUP BY t.tid HAVING count(tr.id) < t.limit";
		$data   = Db::query( $sql );
		$Member = new Member();
		foreach ( $data as $k => $v ) {
			//兑换积分名称,积分或余额
			$data[ $k ]['credit_title'] = $Member->getCreditTitle( $v['credittype'] );
		}
		View::with( 'data', $data );
		View::make( $this->ucenter_template . '/ticket_convert.html' );
	}

	//使用卡券
	public function doWebEmploy() {
		$tid = q( 'get.tid', 0, 'intval' );
		//会员卡卷记录
		$ticket = Db::table( 'ticket' )->where( 'tid', $tid )->first();
		View::with( 'ticket', $ticket );
		View::make( $this->ucenter_template . '/ticket_employ.html' );
	}
}