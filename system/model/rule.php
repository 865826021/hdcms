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

use hdphp\model\Model;

/**
 * 微信回复规则
 * Class Rule
 * @package system\model
 * @author 向军
 */
class Rule extends Model {
	protected $table            = 'rule';
	protected $denyInsertFields = [ 'rid' ];
	protected $validate
	                            = [
			[ 'rank', 'num:0,255', '排序数字在0~255之间', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'name', 'required', '规则名称不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'module', 'required', 'module字段不能为空', self::EXIST_VALIDATE, self::MODEL_BOTH ],
			[ 'rid', 'validateRid', '回复规则不属于本网站', self::EXIST_VALIDATE, self::MODEL_BOTH ],
		];

	protected function validateRid( $field, $val ) {
		return Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'rid', $val )->get() ? TRUE : FALSE;
	}

	protected $filter
		= [
			[ 'rid', self::EMPTY_FILTER, self::MODEL_BOTH ],
		];

	protected $auto
		= [
			[ 'siteid', SITEID, 'string', self::MUST_AUTO, self::MODEL_BOTH ],
			[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
			[ 'rank', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ]
		];

	//	public function store( $rule, $keyword ) {
	//		$action = isset( $rule['rid'] ) ? 'save' : 'add';
	//		if ( ! $rid = $this->save( $rule ) ) {
	//			return FALSE;
	//		}
	//		$rid = isset( $rule['rid'] ) ? $rule['rid'] : $rid;
	//		if ( $keyword ) {
	//			$keywordModel = new RuleKeyword();
	//			foreach ( $keyword as $k ) {
	//				if(!$keywordModel->add($k)){
	//					return
	//				}
	//			}
	//		}
	//	}
}