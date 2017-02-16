<?php namespace app\system\controller;

/**
 * 应用商店
 * Class Shop
 * @package app\system\controller
 */
class Shop {
	public function __construct() {
		\User::superUserAuth();
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
		$apps = \Cloud::apps(Request::get('type'),Request::get('startId'))?:[];
		ajax($apps);
	}
}