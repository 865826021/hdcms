<?php namespace system\model;

/**
 * 卡券使用管理
 * Class TicketRecord
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class TicketRecord extends Common {
	protected $table = 'ticket_record';
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::EMPTY_AUTO, self::MODEL_BOTH ],
	];
}