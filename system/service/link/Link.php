<?php namespace system\service\link;

/**
 * 链接服务
 * 用于生成路由地址
 * Class Link
 * @package system\service\cloud
 */
class Link {
	/**
	 * 根据路由规则生成链接
	 *
	 * @param $router 路由规则
	 * @param array $field 参数变量
	 *
	 * @return mixed|string
	 */
	public function get( $router, $field = [] ) {
		//静态规则
		$url = __WEB__ . '/' . $router;
		foreach ( $field as $k => $v ) {
			$url = str_replace( '{' . $k . '}', $v, $url );
		}

		return $url;
	}

	/**
	 * 文章模块栏目伪静态
	 *
	 * @param int $field 栏目字段数据
	 * @param int |null $page null:保存{page}
	 *
	 * @return mixed|string
	 */
	public function category( $field, $page = 1 ) {
		//静态规则
		if ( ! is_null( $page ) ) {
			$field['page'] = 1;
		}
		$url = __WEB__ . '/article{siteid}-{cid}-{page}.html';
		foreach ( $field as $k => $v ) {
			$url = str_replace( '{' . $k . '}', $v, $url );
		}

		return $url;
	}

	/**
	 * 文章模块内容伪静态
	 *
	 * @param int $field 内容字段数据
	 *
	 * @return mixed|string
	 */
	public function content( $field ) {
		$url = __WEB__ . '/article{siteid}-{aid}-{cid}-{mid}.html';
		foreach ( $field as $k => $v ) {
			$url = str_replace( '{' . $k . '}', $v, $url );
		}

		return $url;
	}
}







