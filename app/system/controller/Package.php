<?php namespace app\system\controller;

use system\model\Package as PackageModel;

/**
 * 套餐管理
 * Class Package
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Package {
	public function __construct() {
		//系统管理员检测
		\User::superUserAuth();
	}

	//套餐列表
	public function lists() {
		$packages = PackageModel::get();
		$packages = $packages ? $packages->toArray() : [ ];
		foreach ( $packages as $k => $v ) {
			//套餐模块
			$modules                   = unserialize( $v['modules'] ) ?: [ ];
			$packages[ $k ]['modules'] = $modules ? Db::table( 'modules' )->whereIn( 'name', $modules )->lists( 'title' ) : [ ];
			//套餐模板
			$templates                  = unserialize( $v['template'] ) ?: [ ];
			$packages[ $k ]['template'] = $templates ? Db::table( 'template' )->whereIn( 'name', $templates )->lists( 'title' ) : [ ];
		}

		return view()->with( 'data', $packages );
	}

	//编辑&添加套餐
	public function post() {
		//套餐编号
		$id = Request::get( 'id' );
		if ( IS_POST ) {
			$model             = $id ? PackageModel::find( $id ) : new PackageModel();
			$model['name']     = Request::post( 'name' );
			$model['modules']  = Request::post( 'modules' );
			$model['template'] = Request::post( 'template' );
			$model->save();
			message( '套餐更新成功', 'lists' );
		}
		//编辑时获取套餐
		if ( $package = PackageModel::find( $id ) ) {
			$package             = $package->toArray();
			$package['modules']  = unserialize( $package['modules'] ) ?: [ ];
			$package['template'] = unserialize( $package['template'] ) ?: [ ];
		}
		$modules   = Db::table( 'modules' )->orderBy( 'is_system', 'DESC' )->get();
		$templates = Db::table( 'template' )->orderBy( 'is_system', 'DESC' )->get();

		return view()->with( [
			'modules'   => $modules,
			'templates' => $templates,
			'package'   => $package,
		] );
	}

	//删除套餐
	public function remove() {
		foreach ( (array) Request::post( 'id' ) as $id ) {
			\Package::remove( $id );
		}
		message( '删除套餐成功', 'back', 'success' );
	}
}