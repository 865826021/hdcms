<?php namespace app\component\controller;

/**
 * 字体
 * Class Font
 * @package app\component\controller
 */
class Font {
	//字体列表
	public function font() {
		auth();
		return View::make();
	}
}