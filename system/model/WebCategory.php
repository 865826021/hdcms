<?php namespace system\model;

/**
 * 官网栏目管理
 * Class WebCategory
 * @package system\model
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class WebCategory extends Common {
	protected $table = 'web_category';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'title', 'required', '栏目标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'orderby', 'num:0,255', '排序数字为0~255之间的字符', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'status', 'num:0,1', '栏目状态为0或1', self::EXIST_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'pid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 'intval', 'function', self::NOT_EXIST_AUTO, self::MODEL_BOTH ],
		[ 'icontype', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'template_tid', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'linkurl', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'ishomepage', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'css', 'json_encode', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'isnav', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'web_id', 0, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'status', 1, 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
	];

	/**
	 * 获取树状栏目
	 * 指定cid时过滤掉cid及其子栏目
	 *
	 * @param int $cid
	 *
	 * @return mixed
	 */
	public function getLevelCategory( $cid = 0 ) {
		$category = Db::table( 'web_category' )->where( 'siteid', SITEID )->get();
		if ( $category ) {
			$category = Data::tree( $category, 'title', 'cid', 'pid' );
			if ( $cid ) {
				//编辑时在栏目选择中不显示自身与子级栏目
				foreach ( $category as $k => $v ) {
					if ( $v['cid'] == $cid || Data::isChild( $category, $v['cid'], $cid ) ) {
						unset( $category[ $k ] );
					}
				}
			}

			return $category;
		}
	}
}