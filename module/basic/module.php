<?php namespace module\basic;

use module\hdModule;
use system\model\ReplyBasic;

/**
 * 文本消息处理
 * Class Module
 * @package module\basic
 */
class Module extends HdModule {

	public function __construct() {
		parent::__construct();
		service( 'user' )->auth( 'reply_basic', 'system' );
	}

	public function settingsDisplay( $settings ) {
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if ( IS_POST ) {
			//字段验证, 并获得正确的数据$data
			//$this->saveSettings($data);
		}
		//这里来展示设置项表单
		//View::make($this->template . '/setting.html');
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
		if ( empty( $_POST['content'] ) ) {
			return '内容不能为空';
		}

		return '';
	}

	public function fieldsSubmit( $rid ) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		$ReplyBasic = new ReplyBasic();
		$ReplyBasic->where( 'rid', $rid )->delete();
		$content = json_decode( Request::post( 'content' ), TRUE );
		foreach ( (array) $content as $c ) {
			$ReplyBasic['rid']     = $rid;
			$ReplyBasic['content'] = $c['content'];
			$ReplyBasic->save();
		}
	}

	public function ruleDeleted( $rid ) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		model( 'ReplyBasic' )->where( 'rid', $rid )->delete();
	}
}