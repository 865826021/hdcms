<?php namespace addons\sina;

/**
 * 模块配置管理
 * 用于管理当前模块的配置项
 * 每个模块配置是独立管理的互不影响
 * @author author
 * @url http://www.hdcms.com
 */
use module\hdModule;

class Config extends hdModule {
	public function settingsDisplay() {
		if ( IS_POST ) {
			//将新配置数据保存
			$this->saveConfig();
			message( '更新配置项成功', 'refresh', 'success' );
		}
		//分配
		\View::with( 'field', $this->getConfig() );

		return view( $this->view . '/setting.html' );
	}
}