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

class Config extends Model {
	protected $table = 'config';
	protected $auto  = [ ];

	/**
	 * 获取配置项
	 *
	 * @param string $name 名称
	 *
	 * @template m('Config')->getByName('site');
	 * @source m('Config')->getByName('site');
	 * @return array|string
	 */
	public function getByName( $name ) {
		$val = $this->where( 'id', 1 )->pluck( $name );

		return $val ? json_decode( $val, TRUE ) : [ ];
	}
}