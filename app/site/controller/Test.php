<?php namespace app\site\controller;
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

class Test {
	public function show() {
		$xml = file_get_contents( 'data/upgrade.xml' );

		$d = Xml::toArray( $xml );
		p( $d );
		$f = array_map( function ( $v ) {
			return trim( $v );
		}, preg_split( '/\r|\n/', $d['manifest']['files']['@cdata'] ) );
		p( $f );
	}
}