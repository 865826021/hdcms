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
		return view( 'lists' );
	}

	/**
	 * 远程获取应用列表
	 * 包括模块与模板
	 */
	public function getCloudLists() {
		$apps = \Cloud::apps( Request::get( 'type' ), Request::get( 'page' ) ) ?: [ ];
		ajax( $apps );
	}

	//安装云模块
	public function install() {
		if ( IS_POST ) {
			//下载文件
			\Cloud::downloadApp( Request::get( 'type' ), Request::get( 'id' ) );
		}

		return view();
	}
}