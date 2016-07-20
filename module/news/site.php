<?php namespace module\news;

use module\hdSite;


/**
 * 图文消息管理
 *
 * @author 向军
 * @url http://open.hdcms.com
 */
class site extends hdSite {
	//构造函数
	public function __construct() {
		//定义目录
		define( '__TEMPLATE__', "theme/default/mobile" );
		parent::__construct();
	}

	//图文消息回复
	public function doWebShow() {
		$id      = q( 'get.id', 0, 'intval' );
		$article = Db::table( 'reply_news' )->where( 'id', $id )->first();
		$tpl     = __TEMPLATE__ . '/article.html';
		View::with( 'hdcms', $article );
		View::make( $tpl );
	}
}