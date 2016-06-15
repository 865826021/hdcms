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

class Modules extends Model {
	protected $table = 'modules';
	protected $validate
	                 = [
			[ 'name', 'required', '模块名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'name', 'regexp:/^[a-z]+$/', '模块名称只能为英文字母', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'industry', 'required', '模块名称不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'title', 'required', '请输入中文的模块标题', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'version', 'regexp:/^\d[\d\.]+$/', '版本号必须为数字', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'resume', 'required', '模块描述不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'detail', 'required', '模块详细介绍不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'author', 'required', '模块作者不能为空', self::MUST_VALIDATE, self::MODEL_INSERT ],
			[ 'url', 'http', '发布url链接错误', self::MUST_VALIDATE, self::MODEL_INSERT ],
		];
	protected $auto
	                 = [
			[ 'name', 'strtolower', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'is_system', 0, 'string', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'subscribes', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'processors', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'setting', 'intval', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'rule', 'intval', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'permissions', 'autoFilterPermission', 'method', self::MUST_AUTO, self::MODEL_INSERT ],
			[ 'permissions', 'serialize', 'function', self::MUST_AUTO, self::MODEL_INSERT ],
		];

	/**
	 * 模块权限数据处理,过滤掉不能模块名称开始的数据
	 *
	 * @param string $val
	 *
	 * @return array
	 */
	protected function autoFilterPermission( $val ) {
		$data        = preg_split( '/\n/', $val );
		$permissions = [ ];
		foreach ( $data as $d ) {
			$setting = explode( ':', $d );
			if ( count( $setting ) == 2 && preg_match( "/^{$data['name']}_/", $setting[1] ) ) {
				$setting[1] = strtolower( $setting[1] );
				array_push( $permissions, $setting );
			}
		}

		return $permissions;
	}

	/**
	 * 获取站点模块数据
	 * 包括站点套餐内模块和为站点独立添加的模块
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteAllModules( $siteid ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		//获取站点可使用的所有套餐
		$package = ( new Package() )->getSiteAllPackageData( $siteid );
		$modules = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐
			$modules = $this->get();
		} else {
			$moduleNames = [ ];
			foreach ( $package as $p ) {
				$moduleNames = array_merge( $moduleNames, $p['modules'] );
			}
			if ( ! empty( $moduleNames ) ) {
				$modules = $this->whereIn( 'name', $moduleNames )->get();
			}
		}
		foreach ( $modules as $k => $m ) {
			$m['subscribes']  = unserialize( $m['subscribes'] );
			$m['processors']  = unserialize( $m['processors'] );
			$m['permissions'] = unserialize( $m['permissions'] );
			$binds            = Db::table( 'modules_bindings' )->where( 'module', $m['name'] )->get();
			foreach ( $binds as $b ) {
				$m['budings'][ $b['entry'] ][] = $b;
			}
			$modules[ $k ] = $m;
		}

		return $cache[ $siteid ] = $modules;
	}

	/**
	 * 当前站点是否可以使用指定模块
	 *
	 * @param int $siteid 站点编号
	 * @param string $module 模块名称
	 *
	 * @return bool
	 */
	public function siteHasModule( $siteid, $module ) {
		$modules = $this->getSiteAllModules( $siteid );
		foreach ( $modules as $m ) {
			if ( strtolower( $module ) == strtolower( $m['name'] ) ) {
				return TRUE;
			}
		}

		return FALSE;
	}
}