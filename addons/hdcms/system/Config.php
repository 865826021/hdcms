<?php namespace addons\hdcms\system;

/**
 * 模块配置管理
 * 用于管理当前模块的配置项
 * 每个模块配置是独立管理的互不影响
 * @author 后盾向军 <2300071698@qq.com>
 * @qq 2300071698
 * @url http://www.hdcms.com
 */
use module\hdModule;

class Config extends hdModule {
	public function settingsDisplay() {
		if ( IS_POST ) {
			//将新配置数据保存
			$this->saveConfig( Request::post() );
			message( '更新配置项成功', 'refresh', 'success' );
		}
		//分配
		\View::with( 'field', $this->config );

		return view( $this->template . '/config.html' );
	}
}