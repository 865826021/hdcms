<?php namespace module\article\service;

use module\HdService;

/**
 * 链接管理服务
 * Class Url
 * @package module\article\service
 */
class Url extends HdService {
	/**
	 * 生成文章页静态地址
	 *
	 * @param $field 文章字段
	 *
	 * @return string 生成的url
	 */
	public function content( $field ) {
		//栏目缓存
		static $category = [ ];
		if ( empty( $category[ $field['category_cid'] ] ) ) {
			$category[ $field['category_cid'] ] = Db::table( 'web_category' )->find( $field['category_cid'] );
		}

		//静态规则
		return __WEB__ . '/' . str_replace( [ '{aid}', '{cid}', '{siteid}' ], [
			$field['aid'],
			$field['category_cid'],
			SITEID
		], $category[ $field['category_cid'] ]['html_content'] );
	}
}