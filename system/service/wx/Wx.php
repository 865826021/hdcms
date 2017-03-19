<?php namespace system\service\wx;

use system\model\ReplyCover;
use system\model\Rule;
use system\model\RuleKeyword;

/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
//服务功能类
class Wx {
	/**
	 * 使用微信openid自动登录
	 */
	public function loginByOpenid() {
		//认证订阅号或服务号,并且开启自动登录时获取微信帐户openid自动登录
		if ( $info = \Weixin::instance( 'oauth' )->snsapiUserinfo() ) {
			$user = $this->where( 'openid', $info['openid'] )->first();
			if ( ! $user ) {
				//帐号不存在时使用openid添加帐号
				$data['openid']   = $info['openid'];
				$data['nickname'] = $info['nickname'];
				$data['icon']     = $info['headimgurl'];
				if ( $uid = ! $this->add( $data ) ) {
					message( $this->getError(), 'back', 'error' );
				}
				$user = Db::table( 'member' )->where( 'uid', $uid )->first();
			}
			Session::set( 'member', $user );

			return true;
		}

		return false;
	}

	/**
	 * 获取公众号类型
	 *
	 * @param $level 公众号编号
	 *
	 * @return mixed
	 */
	public function chatNameBylevel( $level ) {
		$data = [ 1 => '普通订阅号', 2 => '普通服务号', 3 => '认证订阅号', 4 => '认证服务号/认证媒体/政府订阅号' ];

		return $data[ $level ];
	}

	/**
	 * 保存回复规则
	 *
	 * @param $data =[
	 *  rid=>'规则编号(编辑时需要设置)',
	 *  name=>'规则名称',
	 *  module=>'模块名称',
	 *  rank=>'排序',
	 *  status=>'是否开启'
	 *  keywords=>[
	 *      [
	 *          content=>'关键词内容'
	 *          type=>'关键词类型 1: 完全匹配  2:包含  3:正则',
	 *          rank=>'排序',
	 *          status=>'是否开启'
	 *      ]
	 *  ]
	 * ];
	 *
	 * @return bool
	 */
	public function rule( $data ) {
		$Rule           = ! empty( $data['rid'] ) ? Rule::find( $data['rid'] ) : new Rule();
		$data['rid']    = isset( $data['rid'] ) ? $data['rid'] : 0;
		$data['rank']   = isset( $data['rank'] ) ? $data['rank'] : 0;
		$data['rank']   = isset( $data['istop'] ) && $data['istop'] == 1 ? 255 : min( 255, intval( $data['rank'] ) );
		$data['module'] = isset( $data['module'] ) ? $data['module'] : v( 'module.name' );
		foreach ( $data as $field => $v ) {
			$Rule[ $field ] = $v;
		}
		$insertRid = $Rule->save();
		$rid       = empty( $data['rid'] ) ? $insertRid : $data['rid'];
		/**
		 * 添加回复关键词
		 * 先删除旧的回复规则
		 */
		RuleKeyword::where( 'rid', $rid )->delete();
		if ( isset( $data['keywords'] ) ) {
			foreach ( $data['keywords'] as $keyword ) {
				//如果关键词已经被使用不允许添加
				if ( ! RuleKeyword::where( 'siteid', SITEID )->where( 'content', $keyword['content'] )->get() ) {
					if ( ! empty( $keyword['content'] ) ) {
						$keywordModel = new RuleKeyword();
						foreach ( $keyword as $field => $value ) {
							$keywordModel[ $field ] = $value;
						}
						$keywordModel['rid'] = $rid;
						$keywordModel->save();
					}
				}
			}
		}

		return $rid;
	}

	/**
	 * 删除回复规则与回复关键字
	 *
	 * @param $rid
	 *
	 * @return bool
	 */
	public function removeRule( $rid ) {
		$tables = [
			'rule',
			'rule_keyword',
			'reply_cover',
			'reply_basic',
			'reply_image',
			'reply_news'
		];
		if ( $rid ) {
			foreach ( $tables as $tab ) {
				Db::table( $tab )->where( 'rid', $rid )->delete();
			}
		}

		return true;
	}

	/**
	 * 添加图文回复
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	public function cover( $data ) {
		if ( empty( $data['keyword'] ) || empty( $data['title'] ) || empty( $data['description'] ) || empty( $data['thumb'] ) || empty( $data['url'] ) ) {
			message( '不能添加图文消息，参数错误', '', 'error' );
		}
		//添加回复规则
		//回复规则唯一标识，用于确定唯一个图文回复
		$hash           = v( 'module.name' ) . '#' . md5( $data['url'] );
		$rid            = Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', $hash )->pluck( 'rid' );
		$rule['rid']    = $rid;
		$rule['name']   = $hash;
		$rule['module'] = 'cover';
		//回复关键词
		$rule['keywords'] = [ [ 'content' => $data['keyword'] ] ];
		$rid              = $this->rule( $rule );

		//封面回复
		$cover                = Db::table( 'reply_cover' )->where( 'siteid', SITEID )->where( 'hash', $hash )->first();
		$model                = empty( $cover['id'] ) ? new ReplyCover() : ReplyCover::find( $cover['id'] );
		$model['hash']        = $hash;
		$model['rid']         = $rid;
		$model['title']       = $data['title'];
		$model['description'] = $data['description'];
		$model['thumb']       = $data['thumb'];
		$model['url']         = $data['url'];
		$model['module']      = v( 'module.name' );

		return $model->save();
	}

	/**
	 * 删除图文消息
	 *
	 * @param string $url 图文消息链接地址
	 *
	 * @return bool
	 */
	public function removeCover( $url ) {
		$hash = v( 'module.name' ) . '#' . md5( $url );
		$rid  = Db::table( 'rule' )->where( 'siteid', SITEID )->where( 'name', $hash )->pluck( 'rid' );

		return $this->removeRule( $rid );
	}

	/**
	 * 根据链接获取微信关键词信息
	 *
	 * @param string $url 图文消息链接
	 *
	 * @return mixed
	 */
	public function getCoverByUrl( $url ) {
		$hash = v( 'module.name' ) . '#' . md5( $url );

		return Db::table( 'rule' )->join( 'rule_keyword', 'rule.rid', '=', 'rule_keyword.rid' )
		         ->where( 'rule.name', $hash )
		         ->field( 'rule.rid,rule_keyword.content as keyword,rule.siteid,rule.module,rule.status' )
		         ->first();
	}

	/**
	 * 根据模块名称删除模块相关数据
	 *
	 * @param $module
	 */
	public function removeRuleByModule( $module ) {
		$rids = Db::table( 'rule' )->where( 'name', 'like', "%{$module}:%" )->lists( 'rid' );
		foreach ( $rids as $rid ) {
			$this->removeRule( $rid );
		}
	}
}