<?php namespace module\article\system;

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
	 */
	public function show( $attr, $content ) {
		p($attr);
		p($content);
		return '这是标签内容';
	}
}