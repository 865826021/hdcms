<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\site\controller;

use system\model\Menu;
use system\model\Rule;
use system\model\SiteUser;

/**
 * 关键词回复处理
 * Class reply
 * @package site\controller
 */
class Reply {
	protected $db;
	//回复模块处理类
	protected $moduleClass;

	public function __construct() {
		$this->db = new Rule();
		//验证站点权限
		if ( ( new SiteUser() )->verify() === FALSE ) {
			message( '你没有管理站点的权限', 'back', 'warning' );
		}
		//验证站点权限
		//todo 验证模块...
		//分配菜单
		( new Menu() )->getMenus( TRUE );
		$module = Db::table( 'modules' )->where( 'name', q( 'get.m' ) )->first();
		v( 'module', $module );
		if ( empty( $module ) ) {
			//系统模块
			if ( is_dir( 'module/' . $module ) ) {
				$module['name']      = $module;
				$module['is_system'] = 1;
			} else {
				message( '您访问的模块不存在', u( "system/site/lists" ), 'error' );
			}
		}
		$this->moduleClass = ( $module['is_system'] ? '\module\\' : 'addons\\' ) . $module['name'] . '\module';
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
			$rid            = $this->db->store( $data );
			if ( $rid === FALSE ) {
				message( '参数错误', 'back', 'error' );
			}
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
		$rule = $this->db->getRuleInfo( q( 'get.rid' ) );
		View::with( 'rule', $rule );
		$module     = new $this->moduleClass();
		$moduleForm = $module->fieldsDisplay( q( 'get.rid' ) );
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