<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\Site;

/**
 * 文章模板管理
 * Class Template
 * @package web\system\controller
 * @author 向军
 */
class Template {
	protected $db;

	public function __construct() {
		service( 'user' )->superUserAuth();
		$this->db = new \system\model\Template();
	}

	//设置新模板
	public function design() {
		if ( IS_POST ) {
			//字段基本检测
			Validate::make( [
				[ 'title', 'required', '模板名称不能为空' ],
				[ 'industry', 'required', '请选择行业类型' ],
				[ 'name', 'regexp:/^[a-z]\w+$/i', '模板标识必须以英文字母开始, 后跟英文,字母,数字或下划线' ],
				[ 'version', 'regexp:/^[\d\.]+$/i', '请设置版本号, 版本号只能为数字或小数点' ],
				[ 'resume', 'required', '模板简述不能为空' ],
				[ 'author', 'required', '作者不能为空' ],
				[ 'url', 'required', '请输入发布url' ],
				[ 'thumb', 'required', '模板缩略图不能为空' ],
				[ 'position', 'regexp:/^\d+$/', '微站导航菜单数量必须为数字' ],
			] );
			//模板标识转小写
			$_POST['name'] = strtolower( $_POST['name'] );
			//模板缩略图
			if ( ! is_file( $_POST['thumb'] ) ) {
				message( '缩略图文件不存在', 'back', 'error' );
			}
			//检查插件是否存在
			if ( is_dir( 'theme/' . $_POST['name'] ) || $this->db->where( 'name', $_POST['name'] )->first() ) {
				message( '模板已经存在,请更改模板标识', 'back', 'error' );
			}
			if ( ! mkdir( 'theme/' . $_POST['name'], 0755, TRUE ) ) {
				message( '模板目录创建失败,请修改 theme 目录的权限', 'back', 'error' );
			}
			mkdir( 'theme/' . $_POST['name'] . '/mobile', 0755, TRUE );
			mkdir( 'theme/' . $_POST['name'] . '/web', 0755, TRUE );
			//缩略图处理
			$info = pathinfo( $_POST['thumb'] );
			copy( $_POST['thumb'], 'theme/' . $_POST['name'] . '/thumb.' . $info['extension'] );
			$_POST['thumb'] = 'thumb.' . $info['extension'];
			$this->createManifestFile();
			message( '模板创建成功', 'prepared', 'success' );
		} else {
			return view();
		}
	}

	//获取云商店的模块
	public function getCloudModules() {
		$data = Db::table( 'cloud' )->find( 1 );
		$post = [
			'type'      => 'theme',
			'uid'       => $data['uid'],
			'AppSecret' => $data['AppSecret']
		];
		$res  = \Curl::post( c( 'api.cloud' ) . '?a=site/GetUserApps&t=web&siteid=1&m=store', $post );
		$res  = json_decode( $res, 'true' );
		$apps = [ ];
		foreach ( $res['apps'] as $k => $v ) {
			$v          = json_decode( $v['xml'], TRUE );
			$apps[ $k ] = $v;
		}
		//缓存
		d( 'cloudTheme', $apps );
		echo json_encode( $apps );
	}

	//创建manifest.xml文件
	private function createManifestFile() {
		//------------------------- 创建xml文件 start
		//创建xml文件
		$xml_data['application'] = [
			'name'     => [ '@cdata' => $_POST['name'] ],
			'title'    => [ '@cdata' => $_POST['title'] ],
			'url'      => [ '@cdata' => $_POST['url'] ],
			'industry' => [ '@cdata' => $_POST['industry'] ],
			'version'  => [ '@cdata' => $_POST['version'] ],
			'resume'   => [ '@cdata' => $_POST['resume'] ],
			'author'   => [ '@cdata' => $_POST['author'] ],
			'position' => [ '@cdata' => $_POST['position'] ],
			'thumb'    => [ '@cdata' => $_POST['thumb'] ],
		];
		$manifest                = Xml::toXml( 'manifest', $xml_data );
		file_put_contents( 'theme/' . $_POST['name'] . '/manifest.xml', $manifest );
	}

	//已经安装模板
	public function installed() {
		$template = Db::table( 'template' )->get();
		foreach ( $template as $k => $m ) {
			$template[ $k ]['thumb']    = is_file( "theme/{$m['name']}/{$m['thumb']}" ) ? "theme/{$m['name']}/{$m['thumb']}" : "resource/images/nopic_small.jpg";
			$template[ $k ]['locality'] = ! is_file( "theme/{$m['name']}/cloud.hd" ) ? 1 : 0;
		}

		return view()->with( 'template', $template );
	}

	//生成压缩包
	public function createZip() {
		$name = q( 'get.name' );
		Zip::PclZip( "app.zip" );//设置压缩文件名
		Zip::create( "theme/{$name}" );//压缩目录
		\Tool::download( "app.zip", $name . '.zip' );
	}

	//安装新模板列表
	public function prepared() {
		$modules = $this->db->lists( 'name' );
		$dirs    = \Dir::tree( 'theme' );
		//本地模板
		$locality = [ ];
		foreach ( $dirs as $d ) {
			if ( $d['type'] == 'dir' && is_file( $d['path'] . '/manifest.xml' ) ) {
				if ( $xml = Xml::toArray( file_get_contents( $d['path'] . '/manifest.xml' ) ) ) {
					//本地模板
					$thumb = $xml['manifest']['application']['thumb']['@cdata'];
					//去除已经安装的模板
					if ( ! in_array( $xml['manifest']['application']['name']['@cdata'], $modules ) ) {
						//预览图片
						$x['thumb']             = is_file( $d['path'] . '/' . $thumb ) ? $d['path'] . '/' . $thumb : 'resource/images/nopic_small.jpg';
						$x['name']              = $xml['manifest']['application']['name']['@cdata'];
						$x['title']             = $xml['manifest']['application']['title']['@cdata'];
						$x['version']           = $xml['manifest']['application']['version']['@cdata'];
						$x['resume']            = $xml['manifest']['application']['resume']['@cdata'];
						$x['author']            = $xml['manifest']['application']['author']['@cdata'];
						$x['locality']          = ! is_file( 'theme/' . $x['name'] . '/cloud.hd' ) ? 1 : 0;
						$locality[ $x['name'] ] = $x;
					}
				}
			}
		}

		return view()->with( 'locality', $locality );
	}

	//安装模板
	public function install() {
		//模板安装检测
		if ( $m = $this->db->where( 'name', $_GET['name'] )->first() ) {
			message( $m['title'] . '模板已经安装或已经存在系统模板, 你可以卸载后重新安装', 'back', 'error' );
		}
		if ( IS_POST ) {
			$manifestFile = 'theme/' . $_GET['name'] . '/manifest.xml';
			if ( ! is_file( $manifestFile ) ) {
				message( '模板缺少 manifest.xml 文件', 'back', 'error' );
			}
			//获取模板xml数据
			$manifest = Xml::toArray( file_get_contents( $manifestFile ) );
			//整合添加到模板表中的数据
			$moduleData = [
				'name'       => $manifest['manifest']['application']['name']['@cdata'],
				'version'    => $manifest['manifest']['application']['version']['@cdata'],
				'resume'     => $manifest['manifest']['application']['resume']['@cdata'],
				'title'      => $manifest['manifest']['application']['title']['@cdata'],
				'url'        => $manifest['manifest']['application']['url']['@cdata'],
				'type'       => $manifest['manifest']['application']['type']['@cdata'],
				'author'     => $manifest['manifest']['application']['author']['@cdata'],
				'rule'       => $manifest['manifest']['application']['rule']['@attributes']['embed'] ? 1 : 0,
				'thumb'      => $manifest['manifest']['application']['thumb']['@cdata'],
				'position'   => $manifest['manifest']['application']['position']['@cdata'],
				'is_system'  => 0,
				'is_default' => 0,
				'locality'   => ! is_file( "theme/{$_GET['name']}/cloud.hd" ) ? 1 : 0,
			];
			Db::table( 'template' )->insertGetId( $moduleData );
			//在服务套餐中添加模板
			if ( ! empty( $_POST['package'] ) ) {
				$package = Db::table( 'package' )->whereIn( 'name', $_POST['package'] )->get();
				foreach ( $package as $p ) {
					$p['template'] = unserialize( $p['template'] );
					if ( empty( $p['template'] ) ) {
						$p['modules'] = [ ];
					}
					$p['template'][] = $_POST['name'];
					$p['template']   = serialize( array_unique( $p['template'] ) );
					Db::table( 'package' )->where( 'name', $p['name'] )->update( $p );
				}
			}
			service('site')->updateAllCache();
			message( "模板安装成功", u( 'installed' ) );
		}
		$xmlFile = 'theme/' . $_GET['name'] . '/manifest.xml';
		if ( ! is_file( $xmlFile ) ) {
			//下载模块
			go( u( 'download', [ 'name' => $_GET['name'] ] ) );
		}
		$manifest = Xml::toArray( file_get_contents( $xmlFile ) );
		$package  = Db::table( 'package' )->get();

		return view()->with( 'template', $manifest['manifest']['application'] )->with( 'package', $package );
	}

	//下载远程模块
	public function download() {
		if ( IS_POST ) {
			$module = q( 'get.name' );
			$app    = Curl::get( c( 'api.cloud' ) . '?a=site/GetLastAppInfo&t=web&siteid=1&m=store&type=theme&module=' . $module );
			$app    = json_decode( $app, TRUE );
			if ( $app['valid'] == 1 ) {
				$package = Curl::post( c( 'api.cloud' ) . '?a=site/download&t=web&siteid=1&m=store&type=theme', [ 'file' => $app['data']['package'] ] );
				file_put_contents( 'tmp.zip', $package );
				//释放压缩包
				Zip::PclZip( 'tmp.zip' );//设置压缩文件名
				Zip::extract( "." );//解压缩
				file_put_contents( 'theme/' . $module . '/cloud.hd', json_encode( $app['data'], JSON_UNESCAPED_UNICODE ) );
				message( '模块下载成功,准备安装', '', 'success' );
			}
			message( '应用商店不存在模板', '', 'error' );
		}

		return view();
	}

	//卸载模板
	public function uninstall() {
		$this->db->remove( $_GET['name'], $_GET['confirm'] );
		service('site')->updateAllCache();
		message( '模板卸载成功', u( 'installed' ) );
	}
}