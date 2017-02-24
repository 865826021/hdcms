<?php namespace system\service\module;

use system\model\Modules;
use system\model\ModulesBindings;
use system\model\ModuleSetting;
use system\model\SiteModules;
use system\model\UserPermission;

/**
 * 模块管理服务
 * Class Module
 * @package system\service\module
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Module {
	//删除模块时的关联数据表
	protected $relationTables = [
		'module_setting',
		'site_modules',
		'ticket_module',
		'modules_bindings',
	];
	protected $industry = [
		'business'  => '主要业务',
		'customer'  => '客户关系',
		'marketing' => '营销与活动',
		'tools'     => '常用服务与工具',
		'industry'  => '行业解决方案',
		'other'     => '其他'
	];

	/**
	 * 系统启动时执行的模块初始化
	 */
	public function moduleInitialize() {
		/**
		 * 初始化模块数据
		 * 加载模块数据到全局变量窗口中
		 */
		if ( $name = Request::get( 'm' ) ) {
			$module = Db::table( 'modules' )->where( 'name', $name )->first();
			if ( empty( $module ) ) {
				message( '你访问的模块不存在或已经卸载,无法继续操作。', '', 'warning' );
			}
			v( 'module', $module );
		}
		/**
		 * 扩展模块单独使用变量访问
		 * 而不是使用框架中的s变量
		 * 所以当存在a变量时访问到扩展模块处理
		 */
		if ( Request::get( 'm' ) && Request::get( 'action' ) ) {
			Request::set( 'get.s', 'site/entry/action' );
		}
	}

	/**
	 * 检测模块是否已经安装
	 *
	 * @param $module
	 *
	 * @return bool
	 */
	public function isInstall( $module ) {
		return Modules::where( 'name', $module )->first() || is_dir( "module/{$module}" );
	}

	/**
	 * 调用模块方法
	 *
	 * @param string $module 模块.方法
	 * @param array $params 方法参数
	 *
	 * @return mixed
	 */
	function api( $module, $params ) {
		static $instance = [ ];
		$info = explode( '.', $module );
		if ( ! isset( $instance[ $module ] ) ) {
			$data                = Modules::where( 'name', $info[0] )->first();
			$class               = 'addons\\' . $data['name'] . '\api';
			$instance[ $module ] = new $class;
		}

		return call_user_func_array( [ $instance[ $module ], $info[1] ], $params );
	}

	/**
	 * 验证站点是否拥有模块
	 *
	 * @param int $siteId 站点编号
	 * @param string $module 模块名称
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function hasModule( $siteId = 0, $module = '' ) {
		$siteId = $siteId ?: SITEID;
		$module = $module ?: v( 'module.name' );
		if ( empty( $siteId ) || empty( $module ) ) {
			return false;
		}
		$modules = $this->getSiteAllModules( $siteId );
		foreach ( $modules as $m ) {
			if ( strtolower( $module ) == strtolower( $m['name'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * 获取站点模块数据
	 * 包括站点套餐内模块和为站点独立添加的模块
	 *
	 * @param int $siteId 站点编号
	 * @param bool $readFromCache 读取缓存数据
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function getSiteAllModules( $siteId = 0, $readFromCache = true ) {
		$siteId = $siteId ?: SITEID;
		if ( empty( $siteId ) ) {
			message( '获取站点模块数据时, 站点编号不能为空', '', 'error', 5 );
		}
		static $cache = [ ];
		if ( isset( $cache[ $siteId ] ) ) {
			return $cache[ $siteId ];
		}
		//读取缓存
		if ( $readFromCache ) {
			if ( $data = d( "modules:{$siteId}" ) ) {
				return $data;
			}
		}
		//获取站点可使用的所有套餐
		$package = \Package::getSiteAllPackageData( $siteId );
		$modules = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐
			$modules = Modules::get() ? Modules::get()->toArray() : [ ];
		} else {
			$moduleNames = [ ];
			foreach ( $package as $p ) {
				$moduleNames = array_merge( $moduleNames, $p['modules'] );
			}
			$moduleNames = array_merge( $moduleNames, $this->getSiteExtModulesName( $siteId ) );
			if ( ! empty( $moduleNames ) ) {
				$res     = Db::table( 'modules' )->whereIn( 'name', $moduleNames )->get();
				$modules = $res ?: [ ];
			}
		}
		//加入系统模块
		$modules   = array_merge( $modules, Modules::where( 'is_system', 1 )->get()->toArray() );
		$cacheData = [ ];
		foreach ( $modules as $k => $m ) {
			$m['subscribes']  = json_decode( $m['subscribes'], true ) ?: [ ];
			$m['processors']  = json_decode( $m['processors'], true ) ?: [ ];
			$m['permissions'] = array_filter( json_decode( $m['permissions'], true ) ?: [ ] );
			$binds            = Db::table( 'modules_bindings' )->where( 'module', $m['name'] )->get() ?: [ ];
			foreach ( $binds as $b ) {
				//业务动作有多个储存时使用JSON格式的
				if ( $b['entry'] == 'business' ) {
					$b['do'] = json_decode( $b['do'], true );
				}
				$m['budings'][ $b['entry'] ][] = $b;
			}
			$cacheData[ $m['name'] ] = $m;
		}
		cache( "modules:{$siteId}", $cacheData );

		return $cache[ $siteId ] = $cacheData;
	}

	/**
	 * 当前站点使用的非系统模块
	 * @return array
	 */
	public function currentUseModule() {
		foreach ( v( 'site.modules' ) as $v ) {
			if ( $v['name'] == v( 'module.name' ) && v( 'module.is_system' ) == 0 ) {
				return $v;
			}
		}

		return [ ];
	}

	/**
	 * 获取权限菜单使用的标准模块数组
	 * 供 getExtModuleByUserPermission 方法调用
	 *
	 * @param array $modules 扩展模块列表
	 * @param string $name 模块标识
	 * @param string $identifying 标识标识
	 * @param string $cat_name 父级菜单标识
	 * @param string $title 菜单标题
	 * @param array $permission 原权限数据
	 * @param string $url 链接地址
	 *
	 * @return mixed
	 */
	protected function formatModuleAccessData( &$modules, $name, $identifying, $cat_name, $title, $permission, $url, $ico ) {
		$data['name']        = "$name";
		$data['title']       = $title;
		$data['url']         = $url;
		$data['identifying'] = $identifying;
		$data['status']      = 0;
		$data['ico']         = $ico;
		if ( empty( $permission ) ) {
			$data['status'] = 1;
		} elseif ( isset( $permission[ $name ] ) && in_array( $identifying, $permission[ $name ] ) ) {
			$data['status'] = 1;
		}
		$module                                    = v( 'site.modules.' . $name );
		$modules[ $name ]['module']                = [
			'title'     => $module['title'],
			'name'      => $module['name'],
			'is_system' => $module['is_system']
		];
		$modules[ $name ]['access'][ $cat_name ][] = $data;

		return $modules;
	}

	/**
	 * 根据用户权限获取用户的扩展模块权限列表
	 * 注意:包括站点所有模块
	 * 通过属性status判断该用户对某个动作有没有权限
	 * 可用于权限菜单与后台模块菜单显示
	 *
	 * @param string $uid 用户编号
	 *
	 * @return array
	 */
	public function getExtModuleByUserPermission( $uid = '' ) {
		$uid        = $uid ?: v( 'user.info.uid' );
		$permission = \User::getUserAtSiteAccess( SITEID, $uid );
		$modules    = [ ];
		foreach ( v( 'site.modules' ) as $name => $m ) {
			//对扩展模块进行处理
			if ( $m['setting'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_setting', '系统功能', '参数设置', $permission, "?s=site/config/post&m={$name}&mark=package", 'fa fa-cog' );
			}
			if ( $m['crontab'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_crontab', '系统功能', '定时任务', $permission, "?s=site/crontab/lists&m={$name}&mark=package", 'fa fa-globe' );
			}
			if ( $m['router'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_router', '系统功能', '路由规则', $permission, "?s=site/router/lists&m={$name}&mark=package", 'fa fa-tachometer' );
			}
			if ( $m['domain'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_domain', '系统功能', '域名设置', $permission, "?s=site/domain/post&m={$name}&mark=package", 'fa fa-wordpress' );
			}
			if ( $m['middleware'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_middleware', '系统功能', '中间件设置', $permission, "?s=site/middleware/post&m={$name}&mark=package", 'fa fa-twitch' );
			}
			if ( $m['rule'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_rule', '微信回复', '回复规则列表', $permission, "?s=site/rule/lists&m={$name}&mark=package", 'fa fa-rss' );
			}
			if ( $m['cover'] ) {
				foreach ( $m['cover'] as $c ) {
					$this->formatModuleAccessData( $modules, $name, 'system_cover', '微信回复', $c['title'], $permission, "?s=site/rule/lists&m={$name}&mark=package", 'fa fa-file-image-o' );
				}
			}
			if ( $m['budings']['member'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_member', '导航菜单', '桌面会员中心导航', $permission, "?s=site/navigate/lists&entry=member&m={$name}&mark=package", 'fa fa-renren' );
			}
			if ( $m['budings']['profile'] ) {
				$this->formatModuleAccessData( $modules, $name, 'system_profile', '导航菜单', '移动会员中心导航', $permission, "?s=site/navigate/lists&entry=profile&m={$name}&mark=package", 'fa fa-github' );
			}
			if ( $m['budings']['business'] ) {
				//控制器业务功能
				foreach ( $m['budings']['business'] as $c ) {
					foreach ( $c['do'] as $d ) {
						$identifying = 'controller/' . $c['controller'] . '/' . $d['do'];
						$this->formatModuleAccessData( $modules, $name, $identifying, $c['title'], $d['title'], $permission, "?m={$name}&action=controller/{$c['controller']}/{$d['do']}&a=1&mark=package", 'fa fa-pencil-square-o' );
					}
				}
			}
		}

		return $modules;
	}

	/**
	 * 用户在站点可以使用的扩展模块数据
	 * 只显示可用的没有权限的模块不包含
	 *
	 * @param int $siteId 站点编号
	 * @param int $uid 用户编号
	 *
	 * @return mixed
	 */
	public function getBySiteUser( $siteId = 0, $uid = 0 ) {
		static $cache = [ ];
		$name = "cache_{$siteId}_{$uid}";
		if ( ! isset( $cache[ $name ] ) ) {
			$siteId = $siteId ?: SITEID;
			$uid    = $uid ?: v( 'user.info.uid' );
			/**
			 * 插件模块列表
			 */
			$permission = UserPermission::where( 'siteid', $siteId )->where( 'uid', $uid )->lists( 'type,permission' );
			$modules    = v( 'site.modules' );
			if ( isset( $permission['system'] ) ) {
				unset( $permission['system'] );
				$modules = array_intersect_key( $modules, $permission );
			}
			$cache[ $name ] = $modules;
		}

		return $cache[ $name ];
	}

	/**
	 * 按行业获取当前站点的模块列表
	 * 根据当前使用站点拥有的模块获取
	 * 系统管理员获取所有模块
	 *
	 * @param array $modules 限定模块(只有这些模块获取)
	 *
	 * @return array
	 */
	public function getModulesByIndustry( $modules = [ ] ) {
		$data = [ ];
		foreach ( (array) v( 'site.modules' ) as $m ) {
			if ( in_array( $m['name'], $modules ) && $m['is_system'] == 0 ) {
				$data[ $this->industry[ $m['industry'] ] ][] = [
					'title'   => $m['title'],
					'name'    => $m['name'],
					'preview' => $m['preview'],
					'thumb'   => $m['thumb'],
				];
			}
		}

		return $data;
	}

	/**
	 * 模块标题列表
	 * @return array
	 */
	public function getModuleTitles() {
		return [
			'business'  => '主要业务',
			'customer'  => '客户关系',
			'marketing' => '营销与活动',
			'tools'     => '常用服务与工具',
			'industry'  => '行业解决方案',
			'other'     => '其他'
		];
	}

	/**
	 * 获取模块配置
	 *
	 * @param string $module 模块名称
	 *
	 * @return array
	 */
	public function getModuleConfig( $module = '' ) {
		$module = $module ?: v( 'module.name' );
		$config = ModuleSetting::where( 'siteid', SITEID )->where( 'module', $module )->pluck( 'config' );

		return $config ? json_decode( $config, true ) : [ ];
	}

	/**
	 * 获取站点扩展模块数据
	 *
	 * @param string $siteId 网站编号
	 *
	 * @return array
	 */
	public function getSiteExtModules( $siteId ) {
		$module = SiteModules::where( 'siteid', $siteId )->lists( 'module' );

		return $module ? Modules::whereIn( 'name', $module )->get() : [ ];
	}

	/**
	 * 获取站点扩展模块名称列表
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtModulesName( $siteId ) {
		return SiteModules::where( 'siteid', $siteId )->lists( 'module' ) ?: [ ];
	}

	/**
	 * 获取拥有桌面主面访问的模块列表
	 * @return array
	 */
	public function getModuleHasWebPage() {
		$modules = array_keys( $this->getSiteAllModules() );

		return Db::table( 'modules' )
		         ->field( 'modules.mid,modules.title,modules.name,modules.is_system' )
		         ->join( 'modules_bindings', 'modules.name', '=', 'modules_bindings.module' )
		         ->whereIn( 'modules.name', $modules )
		         ->where( 'modules_bindings.entry', 'web' )
		         ->groupBy( 'modules.name' )
		         ->get();
	}

	/**
	 * 从系统中删除模块
	 *
	 * @param string $name 模块标识
	 *
	 * @return bool
	 */
	public function remove( $name ) {
		$module = Db::table( 'modules' )->where( 'name', $name )->first();
		if ( empty( $module ) || $module['is_system'] == 1 ) {
			message( '模块不存或者模块为系统模块无法删除', 'back', 'error' );
		}
		//执行模块本身的卸载程序
		$class = 'addons\\' . $name . '\system\Setup';
		call_user_func_array( [ new $class, 'uninstall' ], [ ] );

		//更新套餐数据
		\Package::removeModule( $name );
		foreach ( $this->relationTables as $t ) {
			Db::table( $t )->where( 'module', $name )->delete();
		}
		//删除模块使用的微信规则与关键词数据
		\Wx::removeRuleByModule( $name );
		Modules::where( 'name', $name )->delete();

		//更新所有站点缓存
		\Site::updateAllCache();

		return true;
	}

}