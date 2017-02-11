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
use app\system\controller\part\Navigate;
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
	}

	//获取云商店的模块
	public function getCloudModules() {
		$modules = \Cloud::modules() ?: [ ];
		ajax( json_encode( $modules ) );
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
				//去除已经安装的模块
				if ( ! in_array( $config['name'], $modules ) ) {
					//预览图片
					$x['thumb']             = $config['thumb'];
					$x['name']              = $config['name'];
					$x['title']             = $config['title'];
					$x['version']           = $config['version'];
					$x['detail']            = $config['detail'];
					$x['author']            = $config['author'];
					$x['locality']          = ! is_file( 'addons/' . $x['name'] . '/cloud.hd' ) ? 1 : 0;
					$locality[ $x['name'] ] = $x;
				}
			}
		}

		return view()->with( 'locality', $locality );
	}

	//设计新模块
	public function design() {
		if ( IS_POST ) {
			//模块结构数据
			$data = json_decode( q( 'post.data' ), JSON_UNESCAPED_UNICODE );
			//字段基本检测
			Validate::make( [
				[ 'title', 'required', '模块名称不能为空' ],
				[ 'industry', 'required', '请选择行业类型' ],
				[ 'name', 'regexp:/^[a-z]\w+$/i', '模块标识必须以英文字母开始, 后跟英文,字母,数字或下划线' ],
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
			Service::make($data);
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
			message( '模块已经安装或已经存在系统模块, 你可以卸载后重新安装', 'back', 'error' );
		}
		//获取模块xml数据
		$config = json_decode( file_get_contents( "$dir/package.json" ), true );
		if ( IS_POST ) {
			//执行安装指令
			$class = 'addons\\' . $config['name'] . '\system\Setup';
			call_user_func_array( [ new $class, 'install' ], [ ] );
			//整合添加到模块表中的数据
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
			$model['crontab']     = $config['crontab'];
			$model['router']      = $config['router'];
			$model['permissions'] = preg_split( '/\n/', $config['permission'] );
			$model['locality']    = ! is_file( $dir . '/cloud.hd' ) ? 1 : 0;
			$model->save();
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
			\Site::updateAllCache();
			message( "模块安装成功", 'installed' );
		}

		//远程应用先下载后安装
		if ( ! is_file( $dir . '/package.json' ) ) {
			go( u( 'download', [ 'module' => $module ] ) );
		}
		$package = Package::get();

		return view()->with( 'module', $config )->with( 'package', $package );
	}

	//下载远程模块
	public function download() {
		if ( IS_POST ) {
			$module = q( 'get.module' );
			$app    = Curl::get( c( 'api.cloud' ) . '?a=site/GetLastAppInfo&t=web&siteid=1&m=store&type=addons&module=' . $module );
			$app    = json_decode( $app, true );
			if ( $app ) {
				$package = Curl::post( c( 'api.cloud' ) . '?a=site/download&t=web&siteid=1&m=store&type=addons', [ 'file' => $app['data']['package'] ] );
				file_put_contents( 'tmp.zip', $package );
				//释放压缩包
				Zip::PclZip( 'tmp.zip' );//设置压缩文件名
				Zip::extract( " . " );//解压缩
				file_put_contents( 'addons/' . $module . '/cloud.hd', json_encode( $app['data'], JSON_UNESCAPED_UNICODE ) );
				message( '模块下载成功,准备安装', '', 'success' );
			}
			message( '应用商店不存在模块', '', 'error' );
		}
		View::make();
	}

	//卸载模块
	public function uninstall() {
		$module = Request::get( 'module' );
		//更改错误为直接显示
		c( 'validate.dispose', 'show' );
		if ( ! isset( $_GET['confirm'] ) ) {
			confirm( '卸载模块时同时删除模块数据吗？', u( 'uninstall', [
				'confirm' => 1,
				'module'  => $module
			] ), u( 'uninstall', [ 'confirm' => 0, 'module' => $module ] ) );
		}
		if ( ! \Module::remove( $module, $_GET['confirm'] ) ) {
			message( \Module::getError(), '', 'error' );
		}
		message( '模块卸载成功', u( 'installed' ) );
	}

}