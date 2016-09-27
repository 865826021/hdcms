<?php namespace app\site\controller;
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\Menu;
use system\model\Rule;
use system\model\RuleKeyword;
use system\model\User;

/**
 * 关键词回复处理
 * Class reply
 * @package site\controller
 */
class Reply {
	protected $rule;
	//回复模块处理类
	protected $moduleClass;

	public function __construct() {
		$this->rule = new Rule();
		$module     = Db::table( 'modules' )->where( 'name', q( 'get.m' ) )->first();
		v( 'module', $module );
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$this->moduleClass = ( $module['is_system'] ? '\module\\' : 'addons\\' ) . $module['name'] . '\module';
		//分配菜单
		( new Menu() )->getMenus();
	}

	//回复列表
	public function lists() {
		$db = Db::table( 'rule' )->where( 'siteid', v( 'site.siteid' ) )->where( 'module', v( 'module.name' ) );
		if ( $status = q( 'get.status' ) ) {
			$db->where( 'status', $status == 'close' ? 0 : 1 );
		}
		$rules = $db->get();
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
		View::with( [
			'module' => v( 'module.name' ),
			'data'   => $data
		] )->make();
	}

	//添加/修改回复
	public function post() {
		if ( IS_POST ) {
			$data           = json_decode( $_POST['keyword'], TRUE );
			$data['module'] = v( 'module.name' );
			$data['rank']   = $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
			//添加回复规则
			$action = isset( $data['rid'] ) ? 'save' : 'add';
			if ( ! $rid = $this->rule->$action( $data ) ) {
				message( $this->rule->getError(), 'back', 'error' );
			}
			$rid = isset( $data['rid'] ) ? $data['rid'] : $rid;
			//添加回复关键字
			$keywordModel = new RuleKeyword();
			$keywordModel->where( 'rid', $rid )->delete();
			foreach ( $data['keyword'] as $keyword ) {
				$keyword['module'] = v( 'module.name' );
				$keyword['rid']    = $rid;
				if ( ! $keywordModel->add( $keyword ) ) {
					message( $keywordModel->getError(), 'back', 'error' );
				}
			}
			//调用模块的执行方法
			$module = new $this->moduleClass();
			//字段验证
			if ($msg = $module->fieldsValidate( $rid ) ) {
				message( $msg, 'back', 'error' );
			}
			//使模块保存回复内容
			$module->fieldsSubmit( $rid );
			message( '规则保存成功', u( 'post', [ 'rid' => $rid, 'm' => v( 'module.name' ) ] ) );
		}
		//获取关键词回复
		if ( $rid = q( 'get.rid' ) ) {
			$data = $this->rule->where( 'rid', $rid )->first();
			if ( empty( $data ) ) {
				message( '回复规则不存在', 'back', 'error' );
			}
			$data['keyword'] = ( new RuleKeyword() )->orderBy( 'id', 'asc' )->where( 'rid', $rid )->get();
			View::with( 'rule', $data );
		}
		$module     = new $this->moduleClass();
		$moduleForm = $module->fieldsDisplay( $rid );
		View::with( 'moduleForm', $moduleForm )->make();
	}

	//删除规则
	public function remove() {
		$rid = q( 'get.rid', '', 'intval' );
		Db::table( 'rule' )->where( 'rid', $rid )->delete();
		Db::table( 'rule_keyword' )->where( 'rid', $rid )->delete();
		$module = new $this->moduleClass( $_GET['m'] );
		$module->ruleDeleted( $rid );
		message( '删除成功', '', 'success' );
	}
}