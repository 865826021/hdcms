<?php namespace addons\sina;

use houdunwang\view\build\TagBase;

/**
 * 模块模板视图自定义标签处理
 * @author author
 * @url http://www.hdcms.com
 */
class Tag extends TagBase {
	/**
	 * 标签名要以下划线+模块名(小写)开始
	 * block 块标签时为true,行标签时为false
	 * level 块可以嵌套层数,行标签不需要填充
	 */
	public $tags = [
		'article_foreach' => [ 'block' => true, 'level' => 5 ],
	];

	/**
	 * 标签定义
	 *
	 * @param array $attr 标签使用的属性
	 * @param string $content 块标签包裹的内容
	 *
	 * @return string
	 */
	public function _article_foreach( $attr, $content ) {
		return '这是标签内容';
	}
}