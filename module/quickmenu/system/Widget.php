<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\quickmenu\system;
/**
 * 页面组件
 * Class Widget
 * @package module\quickmenu\system
 * 调用方法: {{widget('module.quickmenu.system.widget.menu')}};
 */
class Widget {
	//底部快捷导航
	public function menu() {
		$res = Db::table( 'web_page' )->where( 'siteid', SITEID )->where( 'type', 'quickmenu' )->first();
		if ( $res ) {
			$params = json_decode( $res['params'], true );
			if ( empty( $params['modules'] ) ) {
				return '<div style="height:60px;"></div>' . $res['html'];
			} else {
				foreach ( $params['modules'] as $v ) {
					if ( $v['mid'] == v( 'module.mid' ) ) {
						return '<div style="height:60px;"></div>' . $res['html'];
					}
				}
			}
		}
	}
}