<?php namespace module\material\model;

use hdphp\model\Model;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
class Material extends Model {
	protected $table = 'material';

	protected $auto
		= [
			[ 'data', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'file', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'media_id', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'url', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'siteid', SITEID, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'createtime', 'time', 'function', self::EMPTY_AUTO, self::MODEL_INSERT ],
			[ 'status', 1, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ]
		];
	protected $filter
		= [
			[ 'id', self::EMPTY_FILTER, self::MODEL_BOTH ]
		];

	/**
	 * 获取分页数据
	 *
	 * @param $type
	 *
	 * @return array
	 */
	public function getLists( $type ) {
		$count = $this->where( 'type', $type )->where( 'status', 1 )->where( 'siteid', SITEID )->count();
		$page  = Page::row( 10 )->make( $count );
		$data  = $this->where( 'type', $type )
		              ->orderBy( 'id', 'DESC' )
		              ->where( 'status', 1 )
		              ->where( 'siteid', SITEID )
		              ->limit( Page::limit() )
		              ->get();

		return [ 'page' => $page, 'data' => $data ];
	}
}