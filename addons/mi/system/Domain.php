<?php namespace addons\mi\system;

use module\HdDomain;

/**
 * 模块域名设置
 * Class Domain
 */
class Domain extends HdDomain {

	public function set() {
		if ( IS_POST ) {
			$this->save();
			message( '域名保存成功', 'back', 'success' );
		}
		View::with( 'domain', $this->get() );

		return view( $this->template . '/domain.html' );
	}
}