<?php namespace module\article\model;

use houdunwang\model\Model;

/**
 * 文章管理
 * Class WebContent
 * @package module\article\model
 */
class WebContent extends Model {
	protected $table = '';
	protected $allowFill = [ '*' ];
	protected $validate = [
		[ 'title', 'required', '文章标题不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'category_cid', 'required', '请选择文章栏目', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'content', 'required', '文章内容不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'description', 'required', '摘要不能为空', self::MUST_VALIDATE, self::MODEL_BOTH ],
		[ 'orderby', 'num:0,255', '排序只能是0~255之间的数字', self::EXIST_VALIDATE, self::MODEL_BOTH ],
	];
	protected $auto = [
		[ 'siteid', 'siteid', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'rid', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'iscommend', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'ishot', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'description', '', 'string', self::NOT_EXIST_AUTO, self::MODEL_INSERT ],
		[ 'source', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'author', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'linkurl', 0, 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
		[ 'orderby', 'intval', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'createtime', 'time', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'click', 'intval', 'function', self::MUST_AUTO, self::MODEL_BOTH ],
		[ 'thumb', '', 'string', self::EMPTY_AUTO, self::MODEL_INSERT ],
	];

	public function __construct() {
		$this->table = $this->tableName();
		parent::__construct();
	}

	/**
	 * 获取数据表
	 *
	 * @return string
	 */
	public function tableName() {
		return ( new WebModel() )->getModelTable( Request::get( 'mid' ) );
	}

	/**
	 * 删除文章
	 *
	 * @param $aid 文章编号
	 *
	 * @return bool
	 */
	public function del( $aid ) {

		return true;
	}
}