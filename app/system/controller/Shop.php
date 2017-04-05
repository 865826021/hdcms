<?php namespace app\system\controller;

use system\model\Modules;

/**
 * 应用商店
 * Class Shop
 * @package app\system\controller
 */
class Shop {
	public function __construct() {
		\User::superUserAuth();
		//验证云帐号
		\Cloud::checkAccount();
	}

	//应用商店
	public function lists() {
		if ( IS_POST ) {
			//远程获取应用列表
			$apps = \Cloud::apps( Request::get( 'type' ), Request::get( 'page' ) ) ?: [];
			ajax( $apps );
		}

		return view();
	}

	//已购应用
	public function buy() {
		if ( IS_POST ) {
			//远程获取已经购买的应用列表
			$apps = \Cloud::apps( Request::get( 'type' ), Request::get( 'page' ), 'buy' ) ?: [];
			ajax( $apps );
		}

		return view();
	}

	/**
	 * 更新模块列表
	 */
	public function upgradeLists() {
		if ( IS_POST ) {
			$apps = \Cloud::getModuleUpgradeLists();
			ajax( $apps );
		}

		return view( 'upgradeLists' );
	}

	/**
	 * 根据编号更新模块
	 * 模板不能更新
	 */
	public function upgrade() {
		if ( IS_POST ) {
			//更新模块
			$apps = \Cloud::upgradeModuleByName( Request::get( 'name' ) );
			ajax( $apps );
		}

		return view();
	}

	//安装云模块
	public function install() {
		\Cloud::downloadApp( Request::get( 'type' ), Request::get( 'id' ) );
	}
}