<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace addons\store\controller;

use addons\store\model\StoreBuy;
use addons\store\model\StoreHdcms;
use addons\store\model\StoreModule;
use addons\store\model\StoreTemplate;
use addons\store\model\StoreUser;
use addons\store\model\StoreZip;
use houdunwang\request\Request;
use system\model\Member;

/**
 * 云接口
 * Class Cloud
 * @package addons\store\controller
 */
class Cloud {
	/**
	 * 下载最新版HDCMS完整包资料
	 */
	public function downloadFullHdcms() {
		$model = Db::table( 'store_hdcms' )->orderBy( 'id', 'DESC' )->where( 'type', 'full' )->first();
		echo json_encode( $model, JSON_UNESCAPED_UNICODE );
	}

	/**
	 * 检测客户端发送请求时的云帐号权限
	 */
	public function checkCloudAccess() {
		$uid    = Request::get( 'uid' );
		$secret = Request::get( 'secret' );
		if ( ! $uid || ! $secret || ! StoreUser::where( 'uid', $uid )->where( 'secret', $secret )->first() ) {
			ajax( [ 'valid' => 0, 'message' => '云帐号连接失败,请重新登录云帐号' ] );
			die;
		}
	}

	/**
	 * 绑定云帐号
	 */
	public function connect() {
		$res = \Member::login( $_POST, true );
		if ( $res === true ) {
			//登录成功
			$model           = StoreUser::where( 'uid', Session::get( 'member_uid' ) )->first();
			$model['secret'] = $_POST['secret'];
			$model->save();
			ajax( [ 'message' => '连接成功', 'uid' => $model['uid'], 'valid' => 1 ] );
		} else {
			ajax( [ 'message' => $res, 'valid' => 0 ] );
		}
	}

	/**
	 * 检测用户提供的版本有无可用更新
	 */
	public function getUpgradeVersion() {
		$res = StoreHdcms::where( 'build', '>', $_POST['build'] )->where( 'type', 'upgrade' )->orderBy( 'id', 'asc' )->first();
		if ( empty( $res ) ) {
			ajax( [ 'message' => '你使用的是最新版HDCMS', 'valid' => 0 ] );
		} else {
			ajax( [ 'message' => '有新版本发布请进行更新', 'hdcms' => $res->toArray(), 'valid' => 1 ] );
		}
	}

	/**
	 * 用户更新CMS系统后修改安装数量
	 */
	public function updateHDownloadNum() {
		//增加下载数量
		Db::table( "store_hdcms" )->where( 'build', $_GET['build'] )->increment( 'update_site_num', 1 );
		ajax( [ 'message' => '更新安装数量成功', 'valid' => 1 ] );
	}

	/**
	 * 根据类型获取模块或模板列表
	 * 用于客户端后台应用商店显示
	 */
	public function apps() {
		$this->checkCloudAccess();
		switch ( Request::get( 'type' ) ) {
			case 'module':
				$db = StoreModule::paginate( 12 );
				break;
			case 'template':
				$db = StoreTemplate::paginate( 12 );
				break;
		}
		if ( $db ) {
			$data['valid']   = 1;
			$data['message'] = '获取成功';
			$data['apps']    = $db->toArray();
			$data['page']    = \Page::strList();
		} else {
			$data['valid']   = 0;
			$data['message'] = '获取应用列表失败,请修改再试';
		}
		echo json_encode( $data, true );
		exit;
	}

	/**
	 * 获取模块更新列表
	 */
	public function getModuleUpgradeLists() {
		$this->checkCloudAccess();
		$userModules = Request::post();
		$modules     = Db::table( 'store_module' )->whereIn( 'name', array_keys( $userModules ) )->get();
		$apps        = [ ];
		foreach ( $modules as $m ) {
			$zip = StoreZip::where( 'appid', $m['id'] )
			               ->where( 'build', '>', $userModules[ $m['name'] ] )
			               ->orderBy( 'id', 'ASC' )->first();
			if ( $zip ) {
				$m['file'] = $zip->toArray();
				$apps[]    = $m;
			}
		}
		if ( empty( $apps ) ) {
			$data['valid']   = 1;
			$data['message'] = '恭喜! 系统安装的模块都是最新版本。';
			$data['apps']    = $apps;
		} else {
			$data['valid']   = 1;
			$data['message'] = '模块更新列表获取成功';
			$data['apps']    = $apps;
		}
		echo json_encode( $data, true );
		exit;
	}

	/**
	 * 根据编号获取应用信息用于用户安装模块
	 * @return string
	 */
	public function getLastAppById() {
		$this->checkCloudAccess();
		$appTitle = Request::get( 'type' ) == 'module' ? '模块' : '模板';
		switch ( Request::get( 'type' ) ) {
			case 'module':
				$db = StoreModule::find( Request::get( 'id' ) );
				break;
			case 'template':
				$db = StoreTemplate::find( Request::get( 'id' ) );
				break;
		}

		/**
		 * 如果模块有售价时检测用户余额
		 * 如果用户已经购买过不进行扣费操作
		 */
		$where    = [
			[ 'uid', Request::get( 'uid' ) ],
			[ 'type', Request::get( 'type' ) ],
			[ 'name', $db['name'] ],
		];
		$StoreBuy = new StoreBuy();
		if ( $db['price'] > 0 && ! $StoreBuy->where( $where )->get() ) {
			//检测帐户余额
			$user = Member::find( Request::get( 'uid' ) );
			\Credit::change( [
				'uid'        => Request::get( 'uid' ),
				'credittype' => 'credit2',
				'num'        => - 1 * $db['price'],
				'remark'     => "用于购买{$appTitle}:{$db['title']}[{$db['name']}]"
			] );
			if ( $user['credit2'] < $db['price'] ) {
				$data['valid']   = 0;
				$data['message'] = '帐户余额不足,请充值后购买';
				ajax( $data );
			}
			//添加购买记录
			$StoreBuy->save( [
				'uid'  => Request::get( 'uid' ),
				'type' => Request::get( 'type' ),
				'name' => $db['name']
			] );
		}

		if ( $db ) {
			$data            = $db->toArray();
			$data['valid']   = 1;
			$data['package'] = json_decode( $data['package'], true );
			$data['zip']     = StoreZip::where( 'appid', $db['id'] )->orderBy( 'id', 'DESC' )->first()->toArray();
		} else {
			$data['valid']   = 0;
			$data['message'] = '获取应用失败,请稍后再试';
		}
		ajax( $data );
	}

	/**
	 * 获取模块更新
	 */
	public function getModuleUpgrade() {
		$this->checkCloudAccess();
		$post = Request::post();
		$data = Db::table( 'store_module' )->where( 'name', $post['name'] )->first();
		$zip  = Db::table( 'store_zip' )->where( 'appid', $data['id'] )
		          ->where( 'build', '>', $post['build'] )->first();
		if ( $zip && is_file( $zip['file'] ) ) {
			$data['valid']   = 1;
			$data['package'] = json_decode( $data['package'], true );
			$data['zip']     = $zip;
			$data['message'] = '获取模块更新数据成功';
		} else {
			$data['valid']   = 0;
			$data['message'] = '模块更新包失效';
		}

		return json_encode( $data );
	}

	/**
	 * 更新模块安装数量
	 * 模块安装成功后进行更新
	 */
	public function updateModuleInstallNum() {
		Db::table( 'store_zip' )->where( 'appid', Request::post( 'id' ) )->increment( 'install_nums', 1 );
	}
}