<?php namespace addons\access\system;

/**
 * 模块功能封面
 * 功能封面配合图文消息工作
 * 管理员在后台定义好图文消息后
 * 当用户点击图文消息就会执行本类中的相应函数
 * @author fds
 * @url http://open.hdcms.com
 */
use module\HdCover;

class Cover extends HdCover {
	//功能封面1
	public function g1() {
		echo '这是点击封面回复(微信图文消息)后执行';
	}

	//功能封面2
	public function g2() {
		echo '这是点击封面回复(微信图文消息)后执行';
	}


}