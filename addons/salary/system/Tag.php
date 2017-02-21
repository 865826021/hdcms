<?php namespace addons\salary\system;

/**
 * 模块模板视图标签
 * 支持模块间调用
 * @author 后盾团队
 * @url http://www.hdcms.com
 */
class Tag {
	/**
	 * 学生列表
	 *
	 * @param $attr
	 * @param $content
	 *
	 * @return string
	 * 调用方法: <tag action="salary.lists" order="id desc"></tag>
	 */
	public function lists( $attr, $content ) {
		$order = isset( $attr['order'] ) ? $attr['order'] : 'wage desc';
		$row   = isset( $attr['row'] ) ? $attr['row'] : 100;
		$php   = <<<str
<?php
	\$info  = preg_split( '/\s+/', trim( '$order' ) );
	\$_db    = Db::table( 'salary_student' )->limit( $row )->orderBy( \$info[0], \$info[1] )->get();
	foreach(\$_db as \$field){
?>
str;
		$php .= $content;
		$php .= '<?php }?>';

		return $php;
	}
}