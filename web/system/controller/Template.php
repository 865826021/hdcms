<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\system\controller;

use system\model\Site;
use system\model\User;

/**
 * 文章模板管理
 * Class Template
 * @package web\system\controller
 * @author 向军
 */
class Template {
	protected $db;

	public function __construct() {
		if ( ! ( new User() )->isSuperUser() ) {
			message( '只有系统管理员可以执行套餐管理', 'back', 'error' );
		}
		$this->db = new \system\model\Template();
	}

	//设置新模板
	public function design() {
		if ( IS_POST ) {
			//字段基本检测
			Validate::make( [
				[ 'title', 'required', '模板名称不能为空' ],
				[ 'type', 'required', '请选择行业类型' ],
				[ 'name', 'regexp:/^[a-z]\w+$/i', '模板标识必须以英文字母开始, 后跟英文,字母,数字或下划线' ],
				[ 'version', 'regexp:/^[\d\.]+$/i', '请设置版本号, 版本号只能为数字或小数点' ],
				[ 'description', 'required', '模板简述不能为空' ],
				[ 'author', 'required', '作者不能为空' ],
				[ 'url', 'required', '请输入发布url' ],
				[ 'thumb', 'required', '模板缩略图不能为空' ],
				[ 'position', 'regexp:/^\d+$/', '微站导航菜单数量必须为数字' ],
			] );
			//验证失败
			if ( Validate::fail() ) {
				message( Validate::getError(), 'back', 'error' );
			};
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
			View::make();
		}
	}

	//创建manifest.xml文件
	private function createManifestFile() {
		//------------------------- 创建xml文件 start
		//创建xml文件
		$xml_data = [
			'name'        => [ '@cdata' => $_POST['name'] ],
			'title'       => [ '@cdata' => $_POST['title'] ],
			'url'         => [ '@cdata' => $_POST['url'] ],
			'type'        => [ '@cdata' => $_POST['type'] ],
			'version'     => [ '@cdata' => $_POST['version'] ],
			'description' => [ '@cdata' => $_POST['description'] ],
			'author'      => [ '@cdata' => $_POST['author'] ],
			'position'    => [ '@cdata' => $_POST['position'] ],
			'thumb'       => [ '@cdata' => $_POST['thumb'] ],
		];
		$manifest = Xml::toXml( 'manifest', $xml_data );
		file_put_contents( 'theme/' . $_POST['name'] . '/manifest.xml', $manifest );
	}

	//已经安装模板
	public function installed() {
		$template = $this->db->get();
		foreach ( $template as $k => $m ) {
			$template[ $k ]['thumb']         = is_file( "theme/{$m['name']}/{$m['thumb']}" ) ? "theme/{$m['name']}/{$m['thumb']}" : "resource/images/nopic_small.jpg";
			$template[ $k ]['template_type'] = is_file( "theme/{$m['name']}/manifest.xml" ) ? '本地模板' : '应用商店模板';
		}
		View::with( 'template', $template );
		View::make();
	}

	//安装新模板列表
	public function prepared() {
		$modules  = $this->db->lists( 'name' );
		$dirs     = \Dir::tree( 'theme' );
		//本地模板
		$locality = [ ];
		foreach ( $dirs as $d ) {
			if ( $d['type'] == 'dir' && is_file( $d['path'] . '/manifest.xml' ) ) {
				$xml   = Xml::toArray( file_get_contents( $d['path'] . '/manifest.xml' ) );
				$thumb = $xml['manifest']['thumb']['@cdata'];
				//去除已经安装的模板
				if ( ! in_array( $xml['manifest']['name']['@cdata'], $modules ) ) {
					//预览图片
					$x['thumb']             = is_file( $d['path'] . '/' . $thumb ) ? $d['path'] . '/' . $thumb : 'resource/images/nopic_small.jpg';
					$x['name']              = $xml['manifest']['name']['@cdata'];
					$x['title']             = $xml['manifest']['title']['@cdata'];
					$x['version']           = $xml['manifest']['version']['@cdata'];
					$x['description']       = $xml['manifest']['description']['@cdata'];
					$x['author']            = $xml['manifest']['author']['@cdata'];
					$locality[ $x['name'] ] = $x;
				}
			}
		}
		View::with( 'locality', $locality )->make();
	}

	//安装模板
	public function install() {
		//模板安装检测
		if ( $m = $this->db->where( 'name', $_GET['name'] )->first() ) {
			message( $m['title'] . '模板已经安装或已经存在系统模板, 你可以卸载后重新安装', 'back', 'error' );
		}
		$manifestFile = 'theme/' . $_GET['name'] . '/manifest.xml';
		if ( ! is_file( $manifestFile ) ) {
			message( '模板缺少 manifest.xml 文件', 'back', 'error' );
		}

		if ( IS_POST ) {
			//获取模板xml数据
			$manifest = Xml::toArray( file_get_contents( $manifestFile ) );
			//整合添加到模板表中的数据
			$moduleData = [
				'name'        => $manifest['manifest']['name']['@cdata'],
				'version'     => $manifest['manifest']['version']['@cdata'],
				'description' => $manifest['manifest']['description']['@cdata'],
				'title'       => $manifest['manifest']['title']['@cdata'],
				'url'         => $manifest['manifest']['url']['@cdata'],
				'type'        => $manifest['manifest']['type']['@cdata'],
				'author'      => $manifest['manifest']['author']['@cdata'],
				'rule'        => $manifest['manifest']['rule']['@attributes']['embed'] ? 1 : 0,
				'thumb'       => $manifest['manifest']['thumb']['@cdata'],
				'position'    => $manifest['manifest']['position']['@cdata'],
				'is_system'   => 0,
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
			( new Site() )->updateAllSiteCache();
			message( "模板安装成功", u( 'installed' ) );
		}
		$manifest = Xml::toArray( file_get_contents( $manifestFile ) );
		$package  = Db::table( 'package' )->get();
		View::with( 'template', $manifest['manifest'] )->with( 'package', $package )->make();
	}

	//卸载模板
	public function uninstall() {
		if ( ! isset( $_GET['confirm'] ) ) {
			confirm( '确定删除模板吗？', u( 'uninstall', [
				'confirm' => 1,
				'name'    => $_GET['name']
			] ), u( 'uninstall', [ 'confirm' => 0, 'name' => $_GET['name'] ] ) );
		}
		$this->db->remove( $_GET['name'], $_GET['confirm'] );
		( new Site() )->updateAllSiteCache();
		message( '模板卸载成功', u( 'installed' ) );
	}
}