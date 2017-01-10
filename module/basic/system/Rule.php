<?php namespace module\basic\system;

use module\HdRule;
use system\model\ReplyBasic;

/**
 * 文本消息处理
 * Class Module
 * @package module\basic
 */
class Rule extends HdRule {

	public function __construct() {
		parent::__construct();
		\User::auth( 'reply_basic', 'system' );
	}

	public function fieldsDisplay( $rid = 0 ) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		$contents = [ ];
		if ( $rid ) {
			//编辑时读取原数据
			$contents = Db::table( 'reply_basic' )->where( 'rid', $rid )->get();
		}
		View::with( 'contents', json_encode( $contents ?: [ ] ) );

		return View::fetch( $this->template . '/fieldsDisplay.html' );
	}

	public function fieldsValidate( $rid = 0 ) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		if ( ! Request::post( 'content' ) ) {
			return '内容不能为空';
		}

		return '';
	}

	public function fieldsSubmit( $rid ) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		ReplyBasic::where( 'rid', $rid )->delete();
		$content = json_decode( Request::post( 'content' ), true );
		foreach ( (array) $content as $c ) {
			$model            = new ReplyBasic();
			$model['rid']     = $rid;
			$model['content'] = $c['content'];
			$model->save();
		}
	}

	public function ruleDeleted( $rid ) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		ReplyBasic::where( 'rid', $rid )->delete();
	}
}