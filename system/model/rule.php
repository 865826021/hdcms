<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\model;

use hdphp\model\Model;

/**
 * 微信回复规则
 * Class Rule
 * @package system\model
 * @author 向军
 */
class Rule  extends Model{
	/**
	 * 添加关键词
	 *
	 * @param $data
	 *
	 * @return array
	 * $data=array(
	 * 'name'=>'规则名称'
	 * 'module'=>'模块标识',
	 * 'rank'=>'排序(选填,默认为0)',
	 * 'status'=>'开启(选填,默认为1)',
	 * 'rid'=>'编辑时的原规则编号(选填,存在时编辑原规则)'
	 * 'keyword'=>array(
	 *  array(
	 *      'content'=>'关键词',
	 *      'type'=>'类型 1完全匹配,2包含 3正则表达式 4直接托管 (选填 默认为1)',
	 *      'rank'=>'排序(选填,默认为0)',
	 *      'status'=>'状态(选填,默认为1)',
	 *  )
	 * )
	 * )
	 */
	public function store( $data ) {
		//添加回复规则
		if ( empty( $data['name'] ) || empty( $data['module'] ) ) {
			return FALSE;
		}
		$data['siteid'] = isset( $data['siteid'] ) ? $data['siteid'] : v( 'site.siteid' );//站点编号
		$data['rank']   = isset( $data['rank'] ) ? max( 255, intval( $data['rank'] ) ) : 0;//排序
		$data['status'] = isset( $data['status'] ) ? $data['status'] : 1;//开启
		$rid            = Db::table( 'rule' )->replaceGetId( $data );
		//添加关键词,如果是编辑时删除原关键词
		if ( ! empty( $data['rid'] ) && $data['rid'] > 0 ) {
			Db::table( 'rule_keyword' )->where( 'rid', $data['rid'] )->delete();
		}
		foreach ( $data['keyword'] as $v ) {
			if ( empty( $v['content'] ) ) {
				//内容为空时忽略
				continue;
			}
			$v['rid']    = $rid;
			$v['siteid'] = v( 'site.siteid' );
			$v['module'] = $data['module'];
			$v['rank']   = isset( $v['rank'] ) ? min( 255, intval( $v['rank'] ) ) : 0;//排序
			$v['type']   = isset( $v['type'] ) ? $v['type'] : 1;//开启
			$v['status'] = isset( $v['status'] ) ? $v['status'] : 1;//开启
			Db::table( 'rule_keyword' )->insert( $v );
		}

		return $rid;
	}

	/**
	 * 获取关键词规则
	 *
	 * @param $rid 规则编号
	 *
	 * @return mixed
	 *
	 * 成功时返回:
	 * array(
	 *  'rid'=>'规则编号',
	 *  'siteid'=>'站点编号',
	 *  'name'=>'规则名称',
	 *  'module'=>'模块标识',
	 *  'rank'=>'排序',
	 *  'status'=>'排序',
	 *  'keyword=>array(
	 *      'id'=>'编号',
	 *      'rid'=>'规则编号',
	 *      'siteid'=>'站点编号',
	 *      'module'=>'模块名称',
	 *      'content'=>'关键词内容',
	 *      'type'=>'类型 1完全匹配,2包含 3正则表达式 4直接托管 (选填 默认为1)',
	 *      'rank'=>'排序',
	 *      'status'=>'状态'
	 *  )
	 * );
	 * 失败时返回 false
	 */
	public function getRuleInfo( $rid ) {
		$data = Db::table( 'rule' )->where( 'rid', $rid )->first();
		if ( empty( $data ) ) {
			return FALSE;
		}
		$data['keyword'] = $replyKeyword = Db::table( 'rule_keyword' )->orderBy( 'id', 'asc' )->where( 'rid', $rid )->get() ?: [ ];

		return $data;
	}

	/**
	 * 添加封面回复
	 *
	 * @param $cover
	 * array(
	 *  'id'=>'编号,存在时进行更新操作',
	 *  'siteid'=>'站点编号',
	 *  'web_id'=>'微站编号',
	 *  'rid'=>'回复规则编号',
	 *  'module'=>'模块标识',
	 *  'do'=>'动作,一般用在扩展模块',
	 *  'title'=>'标题',
	 *  'description'=>'描述',
	 *  'thumb'=>'缩略图',
	 *  'url'=>'链接地址'
	 * )
	 *
	 * @return bool
	 */
	public function saveReplyCover( $cover ) {
		if ( empty( $cover['rid'] ) || empty( $cover['module'] ) || empty( $cover['title'] ) || empty( $cover['description'] )
		     || empty( $cover['thumb'] )
		     || empty( $cover['url'] )
		) {
			return FALSE;
		}
		$cover['siteid'] = v( "site.siteid" );
		$cover['web_id'] = empty( $cover['web_id'] ) ? 0 : $cover['web_id'];
		$cover['do']     = empty( $cover['do'] ) ? '' : $cover['do'];

		return Db::table( 'reply_cover' )->replaceGetId( $cover );
	}
}