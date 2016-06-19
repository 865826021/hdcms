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

	//	//折扣券
	//	public function doSitediscount() {
	//
	//		$this->post();
	//	}
	//
	//	//代金券
	//	public function doSitecoupon() {
	//		$this->post();
	//	}

	/**
	 * 卡券核销
	 */
	public function doSitecharge() {
		$sql
			  = "SELECT * FROM hd_member m JOIN hd_ticket_record tr ON m.uid=tr.uid JOIN hd_ticket t ON t.tid=tr.tid
                WHERE t.type={$_GET['type']}";
		$data = Db::select( $sql );
		View::make( $this->template . '/charge.html' );
	}
}