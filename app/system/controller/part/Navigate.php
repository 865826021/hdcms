<?php namespace app\system\controller\part;

/**
 * 模块的导航菜单
 * Class Processor
 * @package app\system\controller\part
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Navigate {
	public static function make( $data ) {
		$action = '';
		if ( $data['web']['entry'] ) {
			$action .= self::entry( $data['web']['entry'] )."\n\n";
		}

		if ( $data['web']['member'] ) {
			foreach ( $data['web']['member'] as $d ) {
				$action .= self::webMember( $d )."\n\n";
			}
		}
		if ( $data['mobile']['home'] ) {
			foreach ( $data['mobile']['home'] as $d ) {
				$action .= self::mobileHome( $d )."\n\n";
			}
		}
		if ( $data['mobile']['member'] ) {
			foreach ( $data['mobile']['member'] as $d ) {
				$action .= self::mobileMember( $d )."\n\n";
			}
		}
		if ( ! empty( $action ) ) {
			self::php( $data, $action );
		}
	}

	protected static function php( $data, $action ) {
		$tpl = <<<php
<?php namespace addons\\{$data['name']}\\controller;

/**
 * 模块导航菜单处理
 *
 * @author {$data['author']}
 * @url http://open.hdcms.com
 */
use module\HdController;

class Navigate extends HdController {

$action
}
php;
		file_put_contents( "addons/{$data['name']}/controller/Navigate.php", $tpl );
	}

	//桌面入口菜单
	protected static function entry( $d ) {
		return <<<php
	//{$d['title']} [桌面入口导航菜单]
    public function {$d['do']}() {
    }
php;
	}

	//桌面会员中心菜单
	protected static function webMember( $d ) {
		return <<<php
	//{$d['title']} [桌面会员中心菜单]
    public function {$d['do']}() {
    }
php;
	}

	//移动端首页菜单
	protected static function mobileHome( $d ) {
		return <<<php
	//{$d['title']} [移动端首页菜单]
    public function {$d['do']}() {
    }
php;
	}

	//移动端会员中心菜单
	protected static function mobileMember( $d ) {
		return <<<php
	//{$d['title']} [移动端会员中心菜单]
    public function {$d['do']}() {
    }
php;
	}
}