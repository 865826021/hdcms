<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\model;

/**
 * 支付记录模型
 * Class Pay
 * @package system\model
 */
class Pay extends Common {
	protected $table = "pay";
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'tid', 'required', '定单号不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'type', 'required', '支付类型不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'fee', 'required', '支付金额不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'module', 'required', '模块名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'goods_name', 'required', '商品名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'status', 'required', '支付状态不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
	];

	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::EMPTY_AUTO, self::MODEL_BOTH ],
		[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
		[ 'use_card', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'card_type', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'card_id', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'card_fee', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'attach', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'body', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];
}