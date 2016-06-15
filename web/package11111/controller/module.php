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

	//    //模块主页
	//    public function home()
	//    {
	//        $module = Db::table('modules')->where('name', q('get.m'))->first();
	//        View::with('module', $module)->make();
	//    }

	//自定义模块业务
	public function entry() {
		$module     = q( 'get.m', '', 'htmlspecialchars' );
		$controller = q( 'get.c', '', 'htmlspecialchars' );
		$do         = q( 'get.do', '', 'htmlspecialchars' );
		$do         = 'doSite' . $do;
		$class      = '\addons\\' . $module . "\\{$controller}";
		if ( class_exists( $class ) && method_exists( $class, $do ) ) {
			$obj = new $class( $module );
			$obj->$do();
		}
	}

	//模块封面设置
	public function cover() {
		$bid    = q( 'get.bid', 0, 'intval' );
		$siteid = v( 'site.siteid' );
		if ( IS_POST ) {
			//表单验证
			$fail = Validate::make( [
				[ 'name', 'required', '封面名称不能为空' ],
			] )->fail();
			if ( $fail ) {
				message( Validate::getError(), 'back', 'error' );
			}
			//扩展模块
			if ( $bid ) {
				$module         = Db::table( 'modules_bindings' )->where( 'bid', '=', $bid )->first();
				$cover          = Db::table( 'reply_cover' )
				                    ->where( 'siteid', '=', v( 'site.siteid' ) )
				                    ->where( 'module', '=', $module['module'] )
				                    ->where( 'do', '=', $module['do'] )
				                    ->first();
				$data['rank']   = $_POST['istop'] ? 255 : min( 255, intval( $_POST['rank'] ) );
				$data['siteid'] = v( 'site.siteid' );
				$data['name']   = $_POST['name'];
				$data['rank']   = $_POST['istop'] ? 255 : min( 255, intval( $_POST['rank'] ) );
				$data['module'] = 'cover';
				$data['status'] = $_POST['status'];
				if ( $cover ) {
					$rid = $cover['rid'];
					Db::table( 'rule' )->where( 'rid', $cover['rid'] )->update( $data );
				} else {
					$rid = Db::table( 'rule' )->insertGetId( $data );
				}

				//添加关键词
				$keyword = json_decode( $_POST['keyword'] );
				foreach ( (array) $keyword as $v ) {
					$data = [
						'rid'     => $rid,
						'siteid'  => v( 'site.siteid' ),
						'module'  => 'cover',
						'content' => $v->content,
						'type'    => $v->type,
						'rank'    => 0,
						'status'  => 1,
					];
					if ( $cover ) {
						Db::table( 'rule_keyword' )->where( 'rid', $cover['rid'] )->update( $data );
					} else {
						Db::table( 'rule_keyword' )->insert( $data );
					}
				}
				$data                = [ ];
				$data['rid']         = $rid;
				$data['do']          = Db::table( 'modules_bindings' )->where( 'bid', '=', $bid )->pluck( 'do' );
				$data['siteid']      = v( 'site.siteid' );
				$data['module']      = $_POST['module'];
				$data['title']       = $_POST['title'];
				$data['description'] = $_POST['description'];
				$data['thumb']       = $_POST['thumb'];
				$data['url']         = $_POST['url'];
				if ( $cover ) {
					$data['id'] = $cover['id'];
				}
				Db::table( 'reply_cover' )->replace( $data );
			}
			message( '功能封面更新成功', 'refresh', 'success' );
		} else {
			$replyRule    = [ ];
			$replyKeyword = [ 'default' => [ ], 'contain' => [ ], 'regexp' => [ ], 'depot' => [ ] ];
			$field        = [ ];
			//扩展模块封面
			if ( $bid ) {
				$module          = Db::table( 'modules_bindings' )->where( 'bid', '=', $bid )->first();
				$field['url']    = "?s=package/web/entry&i={$siteid}&m={$module['module']}&c=site&do={$module['do']}";
				$field['name']   = $module['title'];
				$field['module'] = $module['module'];
				//编辑时获取关键词
				$cover = Db::table( 'reply_cover' )
				           ->where( 'siteid', '=', v( 'site.siteid' ) )
				           ->where( 'module', '=', $module['module'] )
				           ->where( 'do', '=', $module['do'] )
				           ->first();
				if ( $cover ) {
					$field['title']       = $cover['title'];
					$field['description'] = $cover['description'];
					$field['thumb']       = $cover['thumb'];
					$replyRule            = Db::table( 'rule' )->where( 'rid', $cover['rid'] )->first();
					$replyRule['istop']   = $replyRule['rank'] == 255 ? 1 : 0;
					//回复关键词
					$replyKeyword = Db::table( 'rule_keyword' )->where( 'rid', $cover['rid'] )->field( 'content,type' )->get();
				}
			} else {
				//系统模块封面
			}

			View::with( [
				'field'        => $field,
				'replyRule'    => $replyRule,
				'replyKeyword' => json_encode( $replyKeyword )
			] );
			View::make();
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