<?php namespace system\service\web;
use system\service\Common;

/**
 * 站点管理服务
 * Class Web
 * @package system\service\web
 */
class Web extends Common {
	/**
	 * 检测指定的官网编号是否属于当前站点
	 *
	 * @param int $id 站点编号
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return Db::table( 'web' )->where( 'siteid', SITEID )->where( 'id', $id )->first() ? true : false;
	}

	/**
	 * 删除文章站点
	 *
	 * @param $webId 站点编号
	 *
	 * @return bool
	 */
	public function del( $webId ) {
		//删除栏目
		Db::table( 'web_category' )->where( 'web_id', $webId )->delete();
		//删除文章
		Db::table( 'web_article' )->where( 'web_id', $webId )->delete();
		//删除站点
		Db::table( 'web' )->where( 'id', $webId )->delete();
		//删除回复规则
		$rid = Db::table( 'reply_cover' )->where( 'web_id', $webId )->pluck( 'rid' );
		\Wx::removeRule( $rid );

		return true;
	}
}