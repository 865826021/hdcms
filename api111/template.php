<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace api;
//微站模板
class template {
	/**
	 * 获取模板
	 *
	 * @param int $type 模板类型
	 *
	 * @return mixed
	 */
	public function get( $type = 0 ) {
		if ( $type ) {
			$data = Db::table( 'template' )->where( 'type', '=', $type )->get();
		} else {
			$data = Db::table( 'template' )->get();
		}

		return $data;
	}

	/**
	 * 获取模板位置数据
	 *
	 * @param $tid 模板编号
	 *
	 * @return array
	 * array(
	 *  1=>'位置1',
	 *  2=>'位置2',
	 * )
	 */
	public function getPositionData( $tid ) {
		$position = Db::table( 'template' )->where( 'tid', $tid )->pluck( 'position' );
		$data     = [ ];
		if ( $position ) {
			for ( $i = 1;$i <= $position;$i ++ ) {
				$data[ $i ] = '位置' . $i;
			}
		}

		return $data;
	}


}