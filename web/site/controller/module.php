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
use system\model\ModuleSetting;
use system\model\ReplyCover;
use system\model\Rule;
use system\model\RuleKeyword;
use system\model\User;

/**
 * 模块动作访问处理
 * Class Module
 * @package site\controller
 */
class Module {
	protected $module;
	protected $controller;
	protected $action;

	public function __construct() {
		//站点模块功能检测
		if ( ! v( 'module' ) ) {
			message( '站点不存在这个模块,请系统管理员添加', 'back', 'error' );
		}
		if ( $action = q( 'get.a' ) ) {
			$info             = explode( '/', $action );
			$this->module     = count( $info ) == 3 ? array_shift( $info ) : NULL;
			$this->controller = $info[0];
			$this->action     = $info[1];
		} else {
			$this->module = v( 'module.name' );
		}
		if ( ACTION !== 'web' ) {
			( new Menu() )->getMenus();
		}
	}

	//模块主页
	public function home() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		View::make();
	}

	//模块配置
	public function setting() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$class = '\addons\\' . v( 'module.name' ) . '\module';
		if ( ! class_exists( $class ) || ! method_exists( $class, 'settingsDisplay' ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		View::with( 'module_action_name', '参数设置' );
		$obj     = new $class();
		$setting = ( new ModuleSetting() )->getModuleConfig( v( 'module.name' ) );

		return $obj->settingsDisplay( $setting );
	}


	//模块封面设置
	public function cover() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$bid        = q( 'get.bid', 0, 'intval' );
		$replyCover = new ReplyCover();
		$ruleModel  = new Rule();
		if ( IS_POST ) {
			if ( empty( $_POST['title'] ) || empty( $_POST['description'] ) || empty( $_POST['thumb'] ) ) {
				message( '封面标题,描述,缩略图不能为空', 'back', 'error' );
			}
			$data           = json_decode( $_POST['keyword'], TRUE );
			$data['module'] = 'cover';
			$data['rank']   = $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
			//添加回复规则
			$action = isset( $data['rid'] ) ? 'save' : 'add';
			if ( ! $rid = $ruleModel->$action( $data ) ) {
				message( $ruleModel->getError(), 'back', 'error' );
			}
			$rid = isset( $data['rid'] ) ? $data['rid'] : $rid;
			//添加回复关键字
			$keywordModel = new RuleKeyword();
			$keywordModel->where( 'rid', $rid )->delete();
			foreach ( $data['keyword'] as $keyword ) {
				$keyword['module'] = 'cover';
				$keyword['rid']    = $rid;
				if ( ! $keywordModel->add( $keyword ) ) {
					message( $keywordModel->getError(), 'back', 'error' );
				}
			}
			$moduleBindings = Db::table( 'modules_bindings' )->where( 'bid', $bid )->first();
			$cover          = $replyCover->where( 'siteid', SITEID )
			                             ->where( 'module', v( 'module.name' ) )
			                             ->where( 'do', $moduleBindings['do'] )
			                             ->first();
			//添加封面回复
			$data                = [ ];
			$data['rid']         = $rid;
			$data['do']          = $moduleBindings['do'];
			$data['siteid']      = SITEID;
			$data['module']      = v( 'module.name' );
			$data['title']       = $_POST['title'];
			$data['description'] = $_POST['description'];
			$data['thumb']       = $_POST['thumb'];
			$data['url']         = $_POST['url'];
			if ( $cover ) {
				$data['id'] = $cover['id'];;
			}
			$action = $cover ? 'save' : 'add';
			if ( ! $replyCover->$action( $data ) ) {
				message( $replyCover->getError(), 'back', 'error' );
			}
			message( '功能封面更新成功', 'refresh', 'success' );
		}
		$moduleBindings = Db::table( 'modules_bindings' )->where( 'bid', $bid )->first();
		$field          = $replyCover->where( 'siteid', SITEID )
		                             ->where( 'module', v( 'module.name' ) )
		                             ->where( 'do', $moduleBindings['do'] )
		                             ->first();
		//获取关键词回复
		if ( $field ) {
			$data = $ruleModel->where( 'rid', $field['rid'] )->first();
			if ( empty( $data ) ) {
				message( '回复规则不存在', 'back', 'error' );
			}
			$data['keyword'] = ( new RuleKeyword() )->orderBy( 'id', 'asc' )->where( 'rid', $field['rid'] )->get();
			View::with( 'rule', $data );
		}
		$field['url']  = '?a=site/' . $moduleBindings['do'] . "&siteid=" . SITEID . "&t=web&m=" . v( 'module.name' );
		$field['name'] = $moduleBindings['title'];
		View::with( 'field', $field );
		View::make();
	}

	//请求入口
	public function entry() {
		switch ( q( 'get.t' ) ) {
			case 'site':
				$this->site();
				break;
			case 'web':
				$this->web();
				break;
			default:
				message( '你访问的页面不存在', 'back', 'warning' );
		}
	}

	//模块前台处理业务
	public function web() {
		$action = 'doWeb' . $this->action;
		$class  = ( v( 'module.is_system' ) ? '\module\\' : '\addons\\' ) . v( 'module.name' ) . '\\';
		$class .= $this->module ? $this->module . '\\' . $this->controller : $this->controller;
		if ( class_exists( $class ) && method_exists( $class, $action ) ) {
			$obj = new $class();

			return $obj->$action();
		}
	}

	//模块后台管理业务
	public function site() {
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		//系统模块只存在name值,如果存在is_system等其他值时为插件扩展模块
		$class = ( v( 'module.is_system' ) ? '\module\\' : '\addons\\' ) . v( 'module.name' ) . '\\';
		$class .= $this->module ? $this->module . '\\' . $this->controller : $this->controller;
		$action = 'doSite' . $this->action;
		if ( ! class_exists( $class ) || ! method_exists( $class, $action ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		$obj = new $class();
		$obj->$action();
	}

	//模块业务
	public function business() {
		$bid = q( 'get.bid', 0, 'intval' );
		if ( ! ( new User() )->verifyModuleAccess() ) {
			message( '你没有操作权限', 'back', 'error' );
		}
		$method = Db::table( 'modules_bindings' )->where( 'bid', $bid )->pluck( 'do' );
		$class  = '\addons\\' . v( 'module.name' ) . '\site';
		$action = "doSite{$method}";
		if ( ! class_exists( $class ) || ! method_exists( $class, $action ) ) {
			message( '访问的模块不存在', 'back', 'error' );
		}
		$obj = new $class();
		$obj->$action();
	}
}