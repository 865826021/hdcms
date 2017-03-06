<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use app\system\controller\part\Business;
use app\system\controller\part\Config;
use app\system\controller\part\Cover;
use app\system\controller\part\Domain;
use app\system\controller\part\Helper;
use app\system\controller\part\Init;
use app\system\controller\part\Navigate;
use app\system\controller\part\Pay;
use app\system\controller\part\Processor;
use app\system\controller\part\Rule;
use app\system\controller\part\Service;
use app\system\controller\part\Setup;
use app\system\controller\part\Subscribe;
use app\system\controller\part\Tag;
use houdunwang\request\Request;
use system\model\Modules;
use system\model\ModulesBindings;
use system\model\Package;

/**
 * 模块管理
 * Class Module
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Module {
	public function __construct() {
		\User::superUserAuth();
	}

	//将本地开发模块生成压缩包
	public function createZip() {
		$name = Request::get( 'name' );
		//更改当前目录
		chdir( 'addons' );
		//设置压缩文件名
		\Zip::PclZip( $name . ".zip" );
		//压缩目录
		\Zip::create( "{$name}" );
		\File::download( $name . ".zip", $name . '.zip' );
		@unlink( $name . ".zip" );
	}

	//已经安装模块
	public function installed() {
		$modules = Db::table( 'modules' )->orderBy( 'mid', 'desc' )->get();
		foreach ( $modules as $k => $m ) {
			//本地模块
			$modules[ $k ]['thumb'] = ( $m['is_system'] == 1 ? 'module' : 'addons' ) . "/{$m['name']}/{$m['thumb']}";
		}

		return view()->with( [ 'modules' => $modules ] );
	}

	//安装新模块列表
	public function prepared() {
		$modules = Modules::lists( 'name' );
		//本地模块
		$locality = [ ];
		foreach ( Dir::tree( 'addons' ) as $d ) {
			if ( $d['type'] == 'dir' && is_file( $d['path'] . '/package.json' ) ) {
				$config = json_decode( file_get_contents( $d['path'] . '/package.json' ), true );
				//去除已经安装的模块和远程模块
				if ( ! in_array( $config['name'], $modules ) && ! is_file( $d['path'] . '/cloud.app' ) ) {
					$locality[ $config['name'] ] = $config;
				}
			}
		}

		return view()->with( 'locality', $locality );
	}

	//格式化package.json数据
	protected function formatPackageJson( $data ) {
		if ( empty( $data['web']['entry']['title'] ) || empty( $data['web']['entry']['do'] ) ) {
			$data['web']['entry'] = [ ];
		}
		//桌面会员中心
		foreach ( $data['web']['member'] as $k => $d ) {
			if ( empty( $d['title'] ) || empty( $d['do'] ) ) {
				unset( $data['web']['member'][ $k ] );
			}
		}
		//移动端导航设置
		foreach ( $data['mobile']['home'] as $k => $d ) {
			if ( empty( $d['title'] ) || empty( $d['do'] ) ) {
				unset( $data['mobile']['home'][ $k ] );
			}
		}
		//移动端会员中心
		foreach ( $data['mobile']['member'] as $k => $d ) {
			if ( empty( $d['title'] ) || empty( $d['do'] ) ) {
				unset( $data['mobile']['member'][ $k ] );
			}
		}
		//封面回复
		foreach ( $data['cover'] as $k => $d ) {
			if ( empty( $d['title'] ) || empty( $d['do'] ) ) {
				unset( $data['cover'][ $k ] );
			}
		}
		//业务动作

		foreach ( $data['business'] as $k => $d ) {
			//检测动作完整性
			foreach ( $data['business'][ $k ]['action'] as $n => $m ) {
				if ( empty( $m['title'] ) || empty( $m['do'] ) ) {
					unset( $data['business'][ $k ]['action'][ $n ] );
				}
			}
			//控制器数据不完整时删除
			if ( empty( $d['title'] ) || empty( $d['controller'] ) || empty( $data['business'][ $k ]['action'] ) ) {
				unset( $data['business'][ $k ] );
			}
		}

		return $data;
	}

	//设计新模块
	public function design() {
		if ( IS_POST ) {
			//模块结构数据
			$data = $this->formatPackageJson( json_decode( Request::post( 'data' ), true ) );
			//字段基本检测
			Validate::make( [
				[ 'title', 'required', '模块名称不能为空' ],
				[ 'industry', 'required', '请选择行业类型' ],
				[ 'name', 'regexp:/^[a-z]+$/', '模块标识必须以英文小写字母构成' ],
				[ 'version', 'regexp:/^[\d\.]+$/i', '请设置版本号, 版本号只能为数字或小数点' ],
				[ 'resume', 'required', '模块简述不能为空' ],
				[ 'detail', 'required', '请输入详细介绍' ],
				[ 'author', 'required', '作者不能为空' ],
				[ 'url', 'required', '请输入发布url' ],
				[ 'compatible_version', 'required', '请选择兼容版本' ],
				[ 'thumb', 'required', '模块缩略图不能为空' ],
				[ 'cover', 'required', '模块封面图不能为空' ],
			], $data );

			//模块标识转小写
			$data['name'] = strtolower( $data['name'] );
			$dir          = "addons/" . $data['name'];
			//检查插件是否存在
			if ( is_dir( "module/{$data['name']}" ) || is_dir( $dir ) ) {
				message( '模块已经存在,请更改模块标识', 'back', 'error' );
			}
			//系统关键字不允许定义为模块标识
			if ( in_array( $data['name'], [
				'user',
				'system',
				'houdunwang',
				'houdunyun',
				'hdphp',
				'hd',
				'hdcms',
				'xj'
			] ) ) {
				message( '模块已经存在,请更改模块标识', '', 'error' );
			}
			//创建目录创建安全文件
			foreach ( [ 'controller', 'template', 'service/template', 'model', 'system', 'system/template' ] as $d ) {
				if ( ! mkdir( "{$dir}/{$d}", 0755, true ) ) {
					message( '模块目录创建失败,请修改addons目录的权限', 'back', 'error' );
				}
				file_put_contents( "{$dir}/{$d}/index.html", 'Not allowed to access' );
			}
			//模块图片处理
			$info  = pathinfo( $data['thumb'] );
			$thumb = $dir . '/thumb.' . $info['extension'];
			copy( $data['thumb'], $thumb );
			$data['thumb'] = 'thumb.' . $info['extension'];
			$info          = pathinfo( $data['preview'] );
			$preview       = $dir . '/cover.' . $info['extension'];
			copy( $data['preview'], $preview );
			$data['preview'] = 'cover.' . $info['extension'];
			//初始创建模块需要的脚本文件
			Config::make( $data );
			Rule::make( $data );
			Tag::make( $data );
			Subscribe::make( $data );
			Processor::make( $data );
			Cover::make( $data );
			Navigate::make( $data );
			Business::make( $data );
			Setup::make( $data );
			Service::make( $data );
			Init::make( $data );
			Pay::make( $data );
			Helper::make( $data );
			file_put_contents( $dir . '/package.json', json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
			message( '模块创建成功', 'prepared' );
		}

		return view();
	}

	//安装模块
	public function install() {
		//模块安装检测
		$module = Request::get( 'module' );
		$dir    = "addons/{$module}";
		if ( \Module::isInstall( $module ) ) {
			message( '模块已经安装或已经存在系统模块, 你可以卸载后重新安装', u( 'module.prepared' ), 'error' );
		}
		//获取模块xml数据
		$config = json_decode( file_get_contents( "$dir/package.json" ), true );
		if ( IS_POST ) {
			//权限标识处理
			$permissions = [ ];
			foreach ( (array) preg_split( '/\n/', $config['permissions'] ) as $v ) {
				$d = explode( ':', $v );
				if ( count( $d ) == 2 ) {
					$permissions[] = [ 'title' => trim( $d[0] ), 'do' => trim( $d[1] ) ];
				}
			}
			//添加到模块系统中
			$model                = new Modules();
			$model['name']        = $config['name'];
			$model['version']     = $config['version'];
			$model['industry']    = $config['industry'];
			$model['title']       = $config['title'];
			$model['url']         = $config['url'];
			$model['resume']      = $config['resume'];
			$model['detail']      = $config['detail'];
			$model['author']      = $config['author'];
			$model['rule']        = $config['rule'];
			$model['thumb']       = $config['thumb'];
			$model['preview']     = $config['preview'];
			$model['tag']         = $config['tag'];
			$model['is_system']   = 0;
			$model['subscribes']  = $config['subscribes'];
			$model['processors']  = $config['processors'];
			$model['setting']     = $config['setting'];
			$model['middleware']  = $config['middleware'];
			$model['crontab']     = $config['crontab'];
			$model['router']      = $config['router'];
			$model['domain']      = $config['domain'];
			$model['permissions'] = $permissions;
			$model['locality']    = ! is_file( $dir . '/cloud.hd' ) ? 1 : 0;
			$model->save();
			//执行模块安装程序
			$class = 'addons\\' . $config['name'] . '\system\Setup';
			if ( class_exists( $class ) && method_exists( $class, 'install' ) ) {
				call_user_func_array( [ new $class, 'install' ], [ ] );
			}
			//添加模块动作表数据
			if ( ! empty( $config['web']['entry'] ) ) {
				$d           = $config['web']['entry'];
				$d['module'] = $module;
				$d['entry']  = 'web';
				ModulesBindings::insert( $d );
			}
			if ( ! empty( $config['web']['member'] ) ) {
				foreach ( $config['web']['member'] as $d ) {
					$d['entry']  = 'member';
					$d['module'] = $module;
					ModulesBindings::insert( $d );
				}
			}
			if ( ! empty( $config['mobile']['home'] ) ) {
				foreach ( $config['mobile']['home'] as $d ) {
					$d['entry']  = 'home';
					$d['module'] = $module;
					ModulesBindings::insert( $d );
				}
			}

			if ( ! empty( $config['mobile']['member'] ) ) {
				foreach ( $config['mobile']['member'] as $d ) {
					$d['entry']  = 'profile';
					$d['module'] = $module;
					ModulesBindings::insert( $d );
				}
			}
			if ( ! empty( $config['cover'] ) ) {
				foreach ( $config['cover'] as $d ) {
					$d['entry']  = 'cover';
					$d['module'] = $module;
					ModulesBindings::insert( $d );
				}
			}
			if ( ! empty( $config['business'] ) ) {
				foreach ( $config['business'] as $d ) {
					if ( ! empty( $d['action'] ) ) {
						$d['entry']  = 'business';
						$d['module'] = $module;
						$d['do']     = json_encode( $d['action'], JSON_UNESCAPED_UNICODE );
						ModulesBindings::insert( $d );
					}
				}
			}
			//在服务套餐中添加模块
			if ( ! empty( $_POST['package'] ) ) {
				$package = Db::table( 'package' )->whereIn( 'name', $_POST['package'] )->get();
				foreach ( $package as $p ) {
					$p['modules'] = json_decode( $p['modules'], true );
					if ( empty( $p['modules'] ) ) {
						$p['modules'] = [ ];
					}
					$p['modules'][] = $module;
					$p['modules']   = json_encode( array_unique( $p['modules'] ), JSON_UNESCAPED_UNICODE );
					Package::where( 'name', $p['name'] )->update( $p );
				}
			}
			//远程模块更新模块数据与删除package.json
			if ( is_file( $dir . '/cloud.app' ) ) {
				\Dir::delFile( $dir . '/package.json' );
				//设置云信息包括云模块编译时间
				$config = include $dir . '/cloud.app';
				$data   = [ 'locality' => 0, 'build' => $config['zip']['build'] ];
				Modules::where( 'name', $config['name'] )->update( $data );

			}
			\Site::updateAllCache();
			message( "模块安装成功", 'installed' );
		}
		$package = Package::get();

		return view()->with( 'module', $config )->with( 'package', $package );
	}

	//卸载模块
	public function uninstall() {
		if ( ! \Module::remove( Request::get( 'module' ) ) ) {
			message( \Module::getError(), '', 'error' );
		}
		message( '模块卸载成功', u( 'installed' ) );
	}
}