<?php namespace system\service\link;

	/**
	 * 链接服务
	 * 用于生成路由地址
	 * Class Link
	 * @package system\service\cloud
	 */
//Link::get('article','{aid}.html',$field);
class Link {
	public function get( $module, $router, $field = [ ] ) {
		//静态规则
		return __WEB__ . '/' . $module . '_' . siteid() . '_' .
		       str_replace( [ '{aid}', '{cid}' ], [
			       $field['aid'],
			       $field['category_cid']
		       ], $router );
	}
}







