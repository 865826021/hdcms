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
	protected $instance;

	public function __construct() {
		//加载站点缓存
		( new Site() )->loadSite();
		//与微信官网通信绑定验证
		\Weixin::valid();
		$this->instance = \Weixin::instance( 'message' );
	}

	/**
	 * 接口业务处理
	 */
	public function deal() {
		//接收普通消息
		$this->message();
		//事件消息
		$this->event();
		//按钮事件处理
		$this->button();
	}

	/**
	 * 默认消息处理
	 *
	 * @param string $content 内容
	 */
	protected function defaultMessage( $content ) {
		$info = $this->text( $content );
		if ( ! is_null( $info ) ) {
			$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
		}
		$this->instance->text( $content );
	}

	/**
	 * 文本消息处理
	 *
	 * @param string $content 文本内容
	 *
	 * @return array
	 */
	protected function text( $content ) {
		$content = trim( $content );
		$sql     = "SELECT * FROM " . tablename( 'rule_keyword' ) . " WHERE status=1 ";
		$sql .= "AND siteid=" . SITEID . " ORDER BY rank DESC,type DESC";
		$keys    = Db::query( $sql );
		$content = strtolower( $content );
		foreach ( $keys as $key ) {
			$key['content'] = strtolower( $key['content'] );
			$rid            = '';
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
				return [ 'rid' => $rid, 'module' => $key['module'] ];
			}
		}
	}

	/**
	 * 模块处理信息
	 *
	 * @param string $moduleName 模块名称
	 *
	 * @return array
	 */
	protected function moduleProcessing( $messageType, $moduleName = '', $rid = 0 ) {
		foreach ( v( 'modules' ) as $module ) {
			//有模块名时,只对该模块进行处理,没有时全部模块都进行操作
			if ( $moduleName !== '' && $module['name'] != $moduleName ) {
				continue;
			}
			if ( in_array( $messageType, $module['processors'] ) ) {
				$class = ( $module['is_system'] == 1 ? '\module\\' : '\addons\\' ) . $module['name'] . '\processor';
				if ( class_exists( $class ) ) {
					$obj = new $class();
					if ( $obj->handle( $rid ) === TRUE ) {
						//处理完成
						exit;
					}
				}
			}
		}
	}

	/**
	 * 模块处理订阅处理
	 *
	 * @param string $moduleName 模块名称
	 *
	 * @return array
	 */
	protected function moduleSubscribe( $messageType ) {
		foreach ( v( 'modules' ) as $module ) {
			if ( in_array( $messageType, $module['subscribes'] ) ) {
				$class = ( $module['is_system'] == 1 ? '\module\\' : '\addons\\' ) . $module['name'] . '\subscribe';
				if ( class_exists( $class ) ) {
					$obj = new $class();
					if ( method_exists( $obj, 'handle' ) ) {
						$obj->handle();
					}
				}
			}
		}
	}

	/**
	 * 事件消息
	 */
	protected function event() {
		$message = $this->instance->getMessage();

		//关注事件
		if ( $this->instance->isSubscribeEvent() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'subscribe' );
			//消息回复处理
			$info = $this->text( v( 'setting.welcome' ) );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
			//没有匹配的时,使用系统默认回复
			$this->defaultMessage( v( 'setting.welcome' ) );
		}
		//取消关注
		if ( $this->instance->isUnSubscribeEvent() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'unsubscribe' );
			//消息回复处理
			$this->moduleProcessing( 'subscribe' );
		}
		//未关注用户扫描二维码
		if ( $this->instance->isSubscribeScanEvent() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'scan' );
			//消息回复处理
			$this->moduleProcessing( 'scan' );
		}
		//关注用户二维码事件
		if ( $this->instance->isScanEvent() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'scan' );
			//消息回复处理
			$this->moduleProcessing( 'scan' );
		}
		//上报地理位置事件
		if ( $this->instance->isLocationEvent() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'track' );
			//消息回复处理
			$this->moduleProcessing( 'track' );
		}
	}

	/**
	 * 接收普通消息
	 */
	protected function message() {
		$message = $this->instance->getMessage();

		//文本消息
		if ( $this->instance->isTextMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'text' );
			//消息处理
			$info = $this->text( $message->Content );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
			//没有匹配的时,使用系统默认回复
			$this->defaultMessage( v( 'setting.default_message' ) );
		}
		//图片消息
		if ( $this->instance->isImageMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'image' );
		}
		//语音消息
		if ( $this->instance->isVoiceMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'voice' );
		}
		//视频消息
		if ( $this->instance->isVideoMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'video' );
		}
		//小视频消息
		if ( $this->instance->isSmallVideoMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'shortvideo' );
		}
		//地址消息
		if ( $this->instance->isLocationMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'location' );
		}
		//地址消息
		if ( $this->instance->isLocationMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'location' );
		}
		//链接消息
		if ( $this->instance->isLinkMsg() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'link' );
		}
	}

	/**
	 * 按钮事件处理
	 */
	protected function button() {
		$message = $this->instance->getMessage();
		//菜单关键词消息
		if ( $this->instance->isClickEvent() ) {
			//消息定阅处理
			$this->moduleSubscribe( 'click' );
			//消息回复
			$info = $this->text( $message->EventKey );
			file_put_contents( 'a.txt', var_export( $info, TRUE ) );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
		//扫码推事件的事件推送
		if ( $this->instance->isScancodePush() ) {
			//消息处理
			$info = $this->text( $message->EventKey );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
		//扫码推事件且弹出“消息接收中”提示框的事件推送
		if ( $this->instance->isScancodeWaitmsg() ) {
			//消息处理
			$info = $this->text( $message->EventKey );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
		//按钮拍照事件
		if ( $this->instance->isPicSysphoto() ) {
			//消息处理
			$info = $this->text( $message->EventKey );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
		//弹出拍照或者相册发图的事件推送
		if ( $this->instance->isPicPhotoOrAlbum() ) {
			$info = $this->text( $message->EventKey );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
		//弹出微信相册发图器的事件推送
		if ( $this->instance->isPicWeixin() ) {
			$info = $this->text( $message->EventKey );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
		//弹出地理位置选择器的事件推送
		if ( $this->instance->isLocationSelect() ) {
			$info = $this->text( $message->EventKey );
			if ( ! is_null( $info ) ) {
				$this->moduleProcessing( 'text', $info['module'], $info['rid'] );
			}
		}
	}
}