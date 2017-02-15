<?php namespace system\service\ticket;

use system\model\Ticket as TicketModel;
use system\model\TicketRecord;

/**
 * 卡券管理服务
 * Class Ticket
 * @package system\service\ticket
 */
class Ticket {
	/**
	 * 获取卡券标题
	 *
	 * @param int $type 卡券类型
	 *
	 * @return string
	 */
	public function title( $type ) {
		$names = [ 1 => '折扣券', 2 => '代金券' ];

		return $names[ $type ];
	}

	/**
	 * 获取指定类型的卡券列表
	 *
	 * @param int $type 卡券类型
	 * @param int $siteId 站点编号
	 *
	 * @return array
	 */
	public function getTicketListsByType( $type, $siteId = 0 ) {
		$siteId = $siteId ?: SITEID;

		return Db::table( 'ticket' )->where( 'siteid', $siteId )->where( 'type', intval( $type ) )->get();
	}

	/**
	 * 卡券兑换
	 *
	 * @param int $tid 卡券编号
	 *
	 * @return bool
	 */
	public function convert( $tid ) {
		Db::beginTransaction();
		//会员已经兑换的数量
		$count = $this->getNumByTid( $tid, v( 'member.info.uid' ) );
		//卡券信息
		$ticket      = TicketModel::where( 'tid', $tid )->where( 'siteid', SITEID )->first();
		$ticketTitle = $this->title( $ticket['type'] );
		if ( $count >= $ticket['limit'] ) {
			return '只能兑换' . $ticket['limit'] . '个' . $ticketTitle . ',你已经全部兑换完毕';
		}
		//减掉会员积分
		$Member             = new Member();
		$data               = [ ];
		$data['uid']        = v( 'member.info.uid' );
		$data['credittype'] = $ticket['credittype'];
		$data['num']        = - 1 * $ticket['credit'];
		$data['module']     = v( 'module.name' );
		$data['remark']     = '兑换' . $ticketTitle . ':' . $ticket['title'] . ',消耗' . $ticket['credit'] . \Credit::title( $ticket['credittype'] );
		//使用用积分兑换卡券
		\Credit::change( $data );
		TicketModel::where( 'tid', $tid )->decrement( 'amount', 1 );
		//记录卡券兑换日志
		$data               = [ ];
		$data['uid']        = v( 'member.info.uid' );
		$data['createtime'] = time();
		$data['usetime']    = 0;//使用时间
		$data['status']     = 1;
		$data['siteid']     = SITEID;
		$data['tid']        = $tid;
		$data['manage']     = 0;//核销员编号
		$data['module']     = v( 'module.name' );
		$data['remark']     = '兑换' . $ticketTitle . ':' . $ticket['title'] . ',消耗' . $ticket['credit'] . \Credit::title( $ticket['credittype'] );//核销员编号
		TicketRecord::insert( $data );
		DB::commit();

		return true;
	}

	/**
	 * 获取指定卡券允许使用的用户组编号
	 *
	 * @param int $tid 卡券编号
	 *
	 * @return array
	 */
	public function getTicketGroupIds( $tid ) {
		if ( empty( $tid ) ) {
			return [ ];
		}

		return Db::table( 'ticket_groups' )->where( 'siteid', SITEID )->where( 'tid', $tid )->lists( 'group_id' );
	}

	/**
	 * 获取指定卡券允许使用的模块
	 *
	 * @param int $tid 卡券编号
	 *
	 * @return array
	 */
	public function getTicketModules( $tid ) {
		if ( empty( $tid ) ) {
			return [ ];
		}
		$module = Db::table( 'ticket_module' )->where( 'siteid', SITEID )->where( 'tid', $tid )->lists( 'module' );

		return $module ? Db::table( 'modules' )->whereIn( 'name', $module )->get() : [ ];
	}

	/**
	 * 获取会员卡券兑换数量
	 *
	 * @param int $tid 卡券编号
	 * @param int $uid 会员编号
	 *
	 * @return mixed
	 */
	public function getNumByTid( $tid, $uid ) {
		return Db::table( 'ticket_record' )->where( 'tid', $tid )->where( 'uid', $uid )->count();
	}
}