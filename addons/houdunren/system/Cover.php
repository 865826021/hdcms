<?php namespace addons\houdunren\system;

/**
 * 模块功能封面
 * 功能封面配合图文消息工作
 * 管理员在后台定义好图文消息后
 * 当用户点击图文消息就会执行本类中的相应函数
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\HdCover;

class Cover extends HdCover {
	//微信入口
	public function entry() {
		echo '这是点击封面回复(微信图文消息)后执行';
	}


}