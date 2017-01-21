<?php namespace app\system\controller\part;

/**
 * 模块消息定阅处理
 * Class Subscribe
 * @package app\system\controller\part
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Subscribe {
	public static function make( $data ) {
		foreach ( $data['subscribes'] as $s ) {
			//定阅了任何一个类型的消息时都创建脚本处理程序
			if ( ! empty( $s ) ) {
				return self::php( $data );
			}
		}
	}

	protected static function php( $data ) {
		$tpl = <<<php
<?php namespace addons\\{$data['name']}\\system;

/**
 * 测试模块消息订阅器
 *
 * @author {$data['author']}
 * @url http://open.hdcms.com
 */
use module\\hdSubscribe;
class Subscribe extends hdSubscribe{
	
	/**
	 * 微信消息定阅处理
	 * 微信有新消息后会发到这个方法
	 * 本方法只做微信消息分析
	 * 不要在这里直接回复微信消息,否则会影响整个系统的稳定性
	 * 微信消息类型很多, 系统已经内置了"后盾网微信接口SDK"
	 * 要更全面的使用本功能请查看 SDK文档
	 * @author {$data['author']}
	 * @url http://www.hdcms.com
	 */
	public function handle(){
		p(\$this->message());
	}
}
php;
		file_put_contents( "addons/{$data['name']}/system/Subscribe.php", $tpl );
	}
}