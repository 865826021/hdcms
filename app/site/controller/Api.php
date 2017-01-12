<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace app\site\controller;
/**
 * 微信请求接口
 * Class api
 * @package module\controller
 * @author 向军
 */
class Api {
	protected $instance;

	public function __construct() {
		//与微信官网通信绑定验证
		\WeChat::valid();
		$this->instance = \WeChat::instance( 'message' );
	}

	/**
	 * 接口业务处理
	 */
	public function deal() {
		//消息定阅处理
		$this->subscribe();
		//文本消息时进行处理
		if ( $this->instance->isTextMsg() ) {
			$message = $this->instance->getMessage();
			$this->text( $message->Content );
		}
		//菜单关键词消息
		if ( $this->instance->isClickEvent() ) {
			$this->text( $message->EventKey );
		}
		//默认消息回复
		$this->defaultMessage();
	}

	/**
	 * 如果是文本消息时获取消息的规则编号
	 *
	 * @param string $content 文本内容
	 *
	 * @return array
	 */
	protected function text( $content ) {
		$content = trim( $content );
		$sql     = "SELECT * FROM ".tablename( 'rule' ).' AS r INNER JOIN '.tablename( 'rule_keyword' )
		          ." as k ON r.rid = k.rid WHERE k.status=1 AND k.siteid=".SITEID." ORDER BY k.rank DESC,type DESC";
		$rules   = Db::query( $sql );
		$content = strtolower( $content );
		foreach ( $rules as $rule ) {
			$rule['content'] = strtolower( $rule['content'] );
			$isFind          = false;
			switch ( $rule['type'] ) {
				case 1:
					//完全匹配
					if ( $content == $rule['content'] ) {
						$isFind = true;
					}
					break;
				case 2:
					//部分匹配
					if ( strpos( $content, $rule['content'] ) !== false ) {
						$isFind = true;
					}
					break;
				case 3:
					//正则匹配
					if ( preg_match( '/'.$rule['content'].'/i', $content ) ) {
						$isFind = true;
					}
					break;
				case 4:
					//直接托管
					$isFind = true;
					break;
			}
			//根据找到的模块执行处理消息动作
			if ( $isFind === true ) {
				$this->moduleProcessing( $rule['module'] );
			}
		}
	}

	/**
	 * 处理信息
	 *
	 * @return array
	 */
	protected function processing() {
		//根据获取的消息类型检测模型是否处理
		$msgType = $this->msgType();
		foreach ( v( 'site.modules' ) as $module ) {
			//模块可以处理该消息类型时
			if ( in_array( $msgType, $module['processors'] ) ) {
				$this->moduleProcessing();
			}
		}
	}

	/**
	 * 根据模块名执行模块处理消息任务
	 *
	 * @param string $module 模块名称
	 * @param int $rid 规则编号,只有在文本消息时才会有值
	 */
	protected function moduleProcessing( $module, $rid = 0 ) {
		$class = ( $module['is_system'] == 1 ? '\module\\' : '\addons\\' ).$module['name'].'\system\Processor';
		if ( class_exists( $class ) ) {
			$obj = new $class();
			if ( $obj->handle( $rid ) === true ) {
				//处理完成
				exit;
			}
		}
	}

	/**
	 * 没有任何模块处理消息时回复默认消息
	 * @return mixed
	 */
	protected function defaultMessage() {
		//关注消息处理
		if ( $this->instance->isSubscribeEvent() ) {
			$this->text( v( 'site.setting.welcome' ) );
			$this->instance->text( v( 'site.setting.welcome' ) );
		} else {
			/**
			 * 没有任何模块处理这个消息时回复系统消息
			 * 先看系统消息能不能做为关键词进行处理
			 * 不能的话直接回复
			 */
			$this->text( v( 'site.setting.default_message' ) );
			$this->instance->text( v( 'site.setting.default_message' ) );
		}
	}

	/**
	 * 模块处理订阅处理
	 *
	 * @return mixed
	 */
	protected function subscribe() {
		//根据获取的消息类型检测模型是否处理
		$msgType = $this->msgType();
		foreach ( v( 'site.modules' ) as $module ) {
			if ( in_array( $msgType, $module['subscribes'] ) ) {
				$class = ( $module['is_system'] == 1 ? '\module\\' : '\addons\\' ).$module['name'].'\system\Subscribe';
				if ( class_exists( $class ) ) {
					$instance = new $class;
					if ( method_exists( $instance, 'handle' ) ) {
						return call_user_func_array( [ $instance, 'handle' ], [ ] );
					}
				}
			}
		}
	}

	/**
	 * 获取消息类型
	 * @return mixed
	 */
	protected function msgType() {
		//事件消息时取Event属性做为消息类型
		$message = $this->instance->getMessage();

		return $message->MsgType == 'event' ? $message->Event : $message->MsgType;
	}
}