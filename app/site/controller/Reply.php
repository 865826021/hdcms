<?php namespace app\site\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\Rule;
use system\model\RuleKeyword;

/**
 * 关键词回复处理
 * Class reply
 * @package site\controller
 */
class Reply {
	//模型
	protected $rule;
	//回复模块处理类
	protected $moduleClass;

	public function __construct() {
		$this->rule = new Rule();
		if ( ! service( 'module' )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$this->moduleClass = ( v( 'module.is_system' ) ? '\module\\' : 'addons\\' ) . v( 'module.name' ) . '\module';
		//分配菜单
		service( 'menu' )->assign();
	}

	//回复列表
	public function lists() {
		$db = Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'module', v( 'module.name' ) );
		if ( $status = q( 'get.status' ) ) {
			$db->where( 'status', $status == 'close' ? 0 : 1 );
		}
		$rules = $db->get() ?: [ ];
		$data  = [ ];
		//回复关键词
		foreach ( $rules as $k => $v ) {
			$v['keywords'] = Db::table( 'rule_keyword' )->where( 'rid', $v['rid'] )->lists( 'content' );
			//按关键词搜索
			if ( $con = q( 'post.content' ) ) {
				if ( ! in_array( $con, $v['keywords'] ) ) {
					continue;
				}
			}
			$data[ $k ] = $v;
		}

		return view()->with( [
			'module' => v( 'module.name' ),
			'data'   => $data
		] );
	}

	//添加/修改回复
	public function post() {
		if ( IS_POST ) {
			$data             = json_decode( Request::post( 'keyword' ), TRUE );
			$data['rank']     = $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
			$data['module']   = v( 'module.name' );
			$data['keywords'] = $data['keyword'];
			$rid              = service( 'WeChat' )->rule( $data );
			//调用模块的执行方法
			$module = new $this->moduleClass();
			//字段验证
			if ( $msg = $module->fieldsValidate( $rid ) ) {
				message( $msg, 'back', 'error' );
			}
			//使模块保存回复内容
			$module->fieldsSubmit( $rid );
			message( '规则保存成功', u( 'post', [ 'rid' => $rid, 'm' => v( 'module.name' ) ] ) );
		}
		//获取关键词回复
		if ( $rid = Request::get( 'rid' ) ) {
			$data = Db::table( 'rule' )->find( $rid );
			if ( empty( $data ) ) {
				message( '回复规则不存在', 'back', 'error' );
			}
			$data['keyword'] = Db::table( 'rule_keyword' )->orderBy( 'id', 'asc' )->where( 'rid', $rid )->get();
			View::with( 'rule', $data );
		}
		$module     = new $this->moduleClass();
		$moduleForm = $module->fieldsDisplay( $rid );

		return view()->with( 'moduleForm', $moduleForm );
	}

	//删除规则
	public function remove() {
		$rid = Request::get( 'rid' );
		service( 'weChat' )->removeRule( $rid );
		$module = new $this->moduleClass();
		$module->ruleDeleted( $rid );
		message( '删除成功', 'back', 'success' );
	}
}