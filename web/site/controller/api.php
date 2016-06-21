<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace web\site\controller;

use system\model\Site;

/**
 * 微信请求接口
 * Class api
 * @package module\controller
 * @author 向军
 */
class Api {
	//系统模块
	protected $systemModules = [ 'basic', 'news', 'image', 'cover' ];

	//构造函数
	public function __construct() {
		//加载站点缓存
		( new Site() )->loadSite();
		//与微信官网通信绑定验证
		\Weixin::valid();
	}

	//接口业务处理
	public function deal() {
		$instance = \Weixin::instance( 'message' );
		$message  = $instance->getMessage();
		//文本消息
		if ( $instance->isTextMsg() ) {
			$this->processor( $message->Content );
			//没有匹配的时,使用系统默认回复
			if ( ! $this->processor( v( 'setting.default_message' ) ) ) {
				$instance->text( v( 'setting.default_message' ) );
			}
		}
		//关注事件
		if ( $instance->isSubscribeEvent() ) {
			if ( ! $this->processor( v( 'setting.welcome' ) ) ) {
				$instance->text( v( 'setting.welcome' ) );
			}
		}
	}

	//消息处理
	private function processor( $content ) {
		$sql = "SELECT * FROM " . tablename( 'rule_keyword' ) . " WHERE status=1 ";
		$sql .= "AND siteid=" . SITEID . " ORDER BY rank DESC,type DESC";
		$keys = Db::query( $sql );
		foreach ( $keys as $key ) {
			$rid = '';
			switch ( $key['type'] ) {
				case 1:
					//完全匹配
					if ( $content == $key['content'] ) {
						$rid = $key['rid'];
					}
					break;
				case 2:
					//部分匹配
					if ( strpos( $content, $key['content'] ) !== FALSE ) {
						$rid = $key['rid'];
					}
					break;
				case 3:
					//正则匹配
					if ( preg_match( '/' . $key['content'] . '/i', $content ) ) {
						$rid = $key['rid'];
					}
					break;
				case 4:
					//直接托管
					$rid = $key['rid'];
					break;
			}

			if ( ! empty( $rid ) ) {
				if ( in_array( $key['module'], $this->systemModules ) ) {
					//系统模块
					$class = '\module\\' . $key['module'] . '\processor';
				} else {
					$class = '\addons\\' . $key['module'] . '\processor';
				}
				if ( class_exists( $class ) ) {
					$obj = new $class( $key['module'], $content );
					if ( $obj->handle( $rid, $content ) === TRUE ) {
						//处理完成
						exit;
					}
				}
			}
		}
	}
}