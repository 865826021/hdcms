<?php namespace module\uc;
class pay {
	//回调通知
	public function doWebNotify() {
		if ( $_GET['code'] == 'SUCCESS' ) {
			//模块业务处理
			Db::table( 'balance' )->where( 'tid', $_GET['tid'] )->update( [ 'status' => 1 ] );
			message( '支付成功,系统将跳转到会员中心', web_url( 'entry/home' ), 'success' );
		} else {
			message( '支付成功,系统将跳转到会员中心', web_url( 'entry/home' ), 'error' );
		}
	}

	//异步通知
	public function doWebAsync( $res ) {
		file_put_contents( 'ccccccc.txt', var_export( $res, TRUE ) );
	}
}