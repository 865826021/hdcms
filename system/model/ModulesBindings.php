<?php namespace system\model;

use houdunwang\model\Model;

/**
 * 模块动作操作模型
 * Class ModulesBindings
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class ModulesBindings extends Model {
	//数据表
	protected $table = "modules_bindings";

	//允许填充字段
	protected $allowFill = [ ];

	//禁止填充字段
	protected $denyFill = [ ];

	//自动验证
	protected $validate = [
		//['字段名','验证方法','提示信息',验证条件,验证时间]
		[ 'title', 'required', '模块名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'entry', 'required', '动作类型不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
		[ 'do', 'required', '动作不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
	];

	//自动完成
	protected $auto = [
		//['字段名','处理方法','方法类型',验证条件,验证时机]
		[ 'controller', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'url', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'icon', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];

	//自动过滤
	protected $filter = [
		//[表单字段名,过滤条件,处理时间]
	];

	//时间操作,需要表中存在created_at,updated_at字段
	protected $timestamps = false;
}