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
}







