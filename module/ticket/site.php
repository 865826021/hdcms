<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\ticket;

use module\hdSite;
use system\model\MemberGroup;
use system\model\Ticket;
use system\model\TicketGroups;
use system\model\TicketModule;
use system\model\TicketRecord;

/**
 * 卡券管理
 * Class site
 * @package module\ticket
 */
class site extends hdSite {
	protected $db;
	protected $names = [ 1 => '折扣券', 2 => '代金券' ];

	public function __construct() {
		parent::__construct();
		$this->db = new Ticket();
		View::with( 'ticket_name', $this->db->getTitleByType( q( 'get.type' ) ) );
	}

	//折扣券列表
	public function doSitelists() {
		$sql = "SELECT t.*, count(r.id) as usertotal FROM " . tablename( 'ticket' ) . " t ";
		$sql .= "LEFT JOIN " . tablename( 'ticket_record' ) . " r ";
		$sql .= "ON t.tid=r.tid WHERE t.type=" . q( 'get.type' ) . " GROUP BY t.tid";
		$data = Db::query( $sql );
		View::with( 'data', $data );
		View::make( $this->template . '/lists.html' );
	}

	//添加折扣券
	public function doSitepost() {
		$tid = q( 'get.tid', 0, 'intval' );
		if ( IS_POST ) {
			$action = $tid ? 'save' : 'add';
			if ( ! $insertId = $this->db->$action() ) {
				message( $this->db->getError(), 'back', 'error' );
			}
			$tid = q( 'get.tid', $insertId );
			//模块设置
			$ticketModule = new TicketModule();
			$ticketModule->where( 'tid', $tid )->delete();
			foreach ( (array) $_POST['module'] as $module ) {
				$data['tid']    = $tid;
				$data['module'] = $module;
				$ticketModule->add( $data );
			}
			//会员组
			$ticketGroups = new TicketGroups();
			$ticketGroups->where( 'siteid', v( 'site.siteid' ) )->where( 'tid', $tid )->delete();
			foreach ( (array) $_POST['groups'] as $group_id ) {
				$data['tid']      = $tid;
				$data['group_id'] = $group_id;
				$ticketGroups->add( $data );
			}
			message( '积分数据更新成功', site_url( 'lists', [ 'type' => q( 'type' ) ] ) );
		}
		//文字描述
		if ( q( 'get.type' ) == 1 ) {
			$msg = [
				'module'      => [ 'title' => '折扣券' ],
				'title'       => [ 'title' => '折扣券名称', 'help' => '' ],
				'condition'   => [ 'title' => '满多少钱可打折', 'help' => '请填写整数。默认订单金额大于0元就可以使用' ],
				'discount'    => [ 'title' => '折扣', 'help' => '请填写0-1的小数。' ],
				'description' => [ 'title' => '折扣券说明', 'help' => '' ],
				'amount'      => [ 'title' => '折扣券总数量', 'help' => '此设置项设置折扣券的总发行数量。' ],
				'limit'       => [ 'title' => '每人可使用数量', 'help' => '此设置项设置每个用户可领取此折扣券数量。' ],
			];
		} else {
			$msg = [
				'module'      => [ 'title' => '代金券' ],
				'title'       => [ 'title' => '代金券名称', 'help' => '' ],
				'condition'   => [ 'title' => '使用条件', 'help' => '订单满多少钱可用。' ],
				'discount'    => [ 'title' => '代金券面额', 'help' => '代金券面额必须少于使用条件的金额。' ],
				'description' => [ 'title' => '代金券说明', 'help' => '' ],
				'amount'      => [ 'title' => '代金券总数量', 'help' => '此设置项设置代金券的总发行数量。' ],
				'limit'       => [ 'title' => '每人可使用数量', 'help' => '此设置项设置每个用户可领取此代金券数量。' ],
			];
		}
		$field = $this->db->where( 'tid', $tid )->first();
		//所有会员组
		$groups = ( new MemberGroup() )->getSiteGroups();
		//卡券可使用的模块
		$modules = ( new TicketModule() )->getTicketModules( $tid );
		//卡券可使用的会员组
		$groupsIds = ( new TicketGroups() )->getTicketGroupIds( $tid );
		View::with( [
			'msg'       => $msg,
			'field'     => $field,
			'groups'    => $groups,
			'modules'   => $modules,
			'groupsIds' => $groupsIds
		] );
		View::make( $this->template . '/post.html' );
	}

	//删除卡券
	public function doSiteDel() {
		$res = Db::table( 'ticket_record' )->where( 'siteid', SITEID )->where( 'tid', q( 'get.tid' ) )->get();
		if ( $res ) {
			message( '卡券已经使用,不能删除', 'back', 'error' );
		}
		Db::table( 'ticket' )->where( 'siteid', SITEID )->where( 'tid', q( 'get.tid' ) )->delete();
		message( '卡券删除成功', 'back', 'success' );
	}

	//卡券核销列表
	public function doSitecharge() {
		//分页数据
		$db = Db::table( 'member' );
		$db->field( "ticket_record.id,member.uid,ticket.title,ticket.thumb,ticket.condition,ticket.discount,user.username,ticket_record.createtime,ticket_record.usetime,ticket_record.status" );
		$db->join( 'ticket_record', 'member.uid', '=', 'ticket_record.uid' );
		$db->join( 'ticket', 'ticket.tid', '=', 'ticket_record.tid' );
		$db->leftJoin( 'user', 'user.uid', '=', 'ticket_record.manage' );
		$db->where( 'ticket.type', q( 'get.type' ) )->where( 'member.siteid', SITEID );
		//有搜索条件
		if ( $tid = q( 'get.tid' ) ) {
			$db->where( 'ticket.tid', $tid );
		}
		if ( $status = q( 'get.status' ) ) {
			$db->where( 'ticket_record.status', $status );
		}
		if ( $uid = q( 'get.uid' ) ) {
			$db->where( 'ticket_record.uid', $uid );
		}
		$page = Page::row( 15 )->make( $db->count() );
		//查询数据
		$db = Db::table( 'member' );
		$db->field( "ticket_record.id,member.uid,ticket.title,ticket.thumb,ticket.condition,ticket.discount,user.username,ticket_record.createtime,ticket_record.usetime,ticket_record.status" );
		$db->join( 'ticket_record', 'member.uid', '=', 'ticket_record.uid' );
		$db->join( 'ticket', 'ticket.tid', '=', 'ticket_record.tid' );
		$db->leftJoin( 'user', 'user.uid', '=', 'ticket_record.manage' );
		$db->where( 'ticket.type', q( 'get.type' ) )->where( 'member.siteid', SITEID );
		//有搜索条件
		if ( $tid = q( 'get.tid' ) ) {
			$db->where( 'ticket.tid', $tid );
		}
		if ( $status = q( 'get.status' ) ) {
			$db->where( 'ticket_record.status', $status );
		}
		if ( $uid = q( 'get.uid' ) ) {
			$db->where( 'ticket_record.uid', $uid );
		}
		$data = $db->limit( Page::limit() )->get();
		//卡券数据
		$ticket = Db::table( 'ticket' )->where( 'siteid', SITEID )->where( 'type', q( 'get.type' ) )->groupBy( 'tid' )->get();
		View::with( 'ticket', $ticket );
		View::with( 'data', $data );
		View::with( 'page', $page );
		View::make( $this->template . '/charge.html' );
	}

	//核销卡券
	public function doSiteVerification() {
		$id    = q( 'get.id', 0, 'intval' );
		$model = new TicketRecord();
		if ( $model->verification( $id ) ) {
			message( '卡券核销成功', 'back', 'success' );
		}
		message( $model->getError(), 'back', 'error' );
	}

	//删除卡券
	public function doSiteRemove() {
		$id    = q( 'get.id', 0, 'intval' );
		$model = new TicketRecord();
		if ( $model->delete( $id ) ) {
			message( '卡券删除成功', 'back', 'success' );
		}
		message( $model->getError(), 'back', 'error' );
	}
}











