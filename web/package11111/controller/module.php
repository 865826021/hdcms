<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace package\controller;

use web\site;

/**
 * 用于模块后台处理入口
 * Class entry
 * @package platform\controller
 * @author 向军
 */
class module extends site {
	//模块名称
	protected $module;

	//构造函数
	public function __construct() {
		parent::__construct();
		//获取模块
		if ( isset( $_GET['bid'] ) ) {
			//根据动作的编号获取模块
			$this->module = Db::table( 'modules_bindings' )->where( 'bid', $_GET['bid'] )->pluck( 'module' );
		} else {
			$this->module = q( 'get.m', '', 'htmlspecialchars' );
		}
		if ( empty( $this->module ) ) {
			message( '你访问的扩展模块不存在', 'system/site/lists', 'error' );
		}

		//判断用户是否有该模块的使用权限
		if ( System::hasModuleAccess( $this->module ) === FALSE ) {
			message( '你没有使用该模块的权限', 'system/site/lists', 'error' );
		}
	}



	//    //模块配置
	//    public function setting()
	//    {
	//        $module  = q('get.m', '', 'htmlentities');
	//        $class   = '\addons\\' . $module . '\module';
	//        $setting = Db::table('module_setting')->where('module', '=', $module)->where('siteid', '=', v('site.siteid'))->pluck('setting');
	//        $obj     = new $class($module);
	//        $obj->settingsDisplay(unserialize($setting));
	//    }
	//
	//    //模块业务处理
	//    public function business()
	//    {
	//        $bid    = q('get.bid', 0, 'intval');
	//        $module = Db::table('modules_bindings')->where('bid', '=', $bid)->first();
	//        $class  = '\addons\\' . $module['module'] . '\site';
	//        $do     = 'doSite' . $module['do'];
	//        if (!class_exists($class) || !method_exists($class, $do))
	//        {
	//            message('请求的页面不存在', 'back', 'error');
	//        }
	//        $obj = new $class($module['module']);
	//        $obj->$do();
	//    }
}