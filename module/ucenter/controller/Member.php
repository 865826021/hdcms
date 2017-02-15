<?php namespace module\ucenter\controller;

/**
 * 会员中心
 * Class Member
 * @package module\ucenter\controller
 */
class Member extends Auth {

	//会员中心
	public function index() {
		return view( $this->template . '/index.html' );

	}
}