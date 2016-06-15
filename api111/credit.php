<?php namespace api;
class credit {
	/**
	 * 根据积分字段获取中文描述
	 *
	 * @param $name
	 * <code>
	 *  api('credit')->getTitle('credit1');
	 * </code>
	 *
	 * @return string 积分中文描述
	 */
	public function getTitle( $name ) {
		return v( 'setting.creditnames.' . $name . '.title' );
	}
}