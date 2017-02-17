<?php namespace addons\store\system;

/**
 * 模块模板视图自定义标签处理
 * @author 向军
 * @url http://www.hdcms.com
 */
class Tag {
	/**
	 * 标签定义
	 *
	 * @param array $attr 标签使用的属性
	 * @param string $content 块标签包裹的内容
	 *
	 * @return string
	 * 调用方法: <tag action="store.show" id="1" name="hdphp"></tag>
	 */
	public function show( $attr, $content ) {
		return '这是标签内容';
	}
}