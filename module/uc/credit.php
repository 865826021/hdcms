<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\uc;

use module\hdSite;
use system\model\CreditsRecord;

/**
 * 会员中心积分余额管理
 * Class credit
 * @package module\uc
 * @author 向军
 */
class credit extends hdSite {
	public function __construct() {
		parent::__construct();
	}

	//积分/余额列表
	public function doWebLists() {
		$Model = new CreditsRecord();
		//会员信息
		$user = Db::table( 'member' )->where( 'uid', Session::get( 'member.uid' ) )->first();
		if ( $timerange = q( 'get.timerange' ) ) {
			//有筛选时间的
			$timerange = explode( '至', $timerange );
			$total     = $Model->where( 'uid', Session::get( 'member.uid' ) )
			                   ->where( 'credittype', q( 'get.type' ) )
			                   ->where( 'createtime', '>=', strtotime( $timerange[0] ) )
			                   ->where( 'createtime', '<=', strtotime( $timerange[1] ) )
			                   ->count();
			$page      = Page::row( 8 )->make( $total );
			$data      = $Model->where( 'uid', Session::get( 'member.uid' ) )
			                   ->where( 'credittype', q( 'get.type' ) )
			                   ->where( 'createtime', '>=', strtotime( $timerange[0] ) )
			                   ->where( 'createtime', '<=', strtotime( $timerange[1] ) )
			                   ->limit( Page::limit() )
			                   ->get();
		} else {
			$total = $Model->where( 'uid', Session::get( 'member.uid' ) )->where( 'credittype', q( 'get.type' ) )->count();
			$page  = Page::row( 8 )->make( $total );
			$data  = $Model->where( 'uid', Session::get( 'member.uid' ) )->where( 'credittype', q( 'get.type' ) )->limit( Page::limit() )->get();
		}

		//收入
		$income = $Model->where( 'num', '>', 0 )->where( 'uid', Session::get( 'member.uid' ) )->where( 'credittype', q( 'get.type' ) )->sum( 'num' );
		//支出
		$expend = $Model->where( 'num', '<', 0 )->where( 'uid', Session::get( 'member.uid' ) )->where( 'credittype', q( 'get.type' ) )->sum( 'num' );
		View::with( [ 'income' => $income, 'expend' => $expend ] );
		View::with( 'page', $page );
		View::with( 'user', $user );
		View::with( 'data', $data );
		View::make( 'ucenter/credit_lists.html' );
	}
}