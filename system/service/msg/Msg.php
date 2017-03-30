<?php namespace system\service\msg;

/**
 * 消息日志管理服务
 * Class Msg
 * @package system\service\pay
 */
use houdunwang\aliyunsms\Sms;
use houdunwang\session\Session;

class Msg {
	protected $error;

	/**
	 * 获取上次发送的验证码时间
	 * 大于0时不能发送
	 * @return int
	 */
	public function validCodeTime() {
		$time = Session::get( 'validCode.sendtime' ) + 60 - time();

		return $time > 0 ? $time : 0;
	}

	/**
	 * 验证码比对
	 *
	 * @param int $code 用户输入的验证码
	 *
	 * @return bool
	 */
	public function checkValidCode( $code ) {
		return $code == Session::get( 'validCode.code' );
	}

	/**
	 * 发送验证码
	 *
	 * @param string $account 邮箱/手机号
	 *
	 * @return bool
	 */
	public function sendValidCode( $account ) {
		if ( empty( $account ) ) {
			$this->error = '帐号不能为空';

			return false;
		}
		//检测是否在60秒内发送的
		$code = Session::get( 'validCode' );
		if ( $code && ( $code['sendtime'] + 60 ) > time() ) {
			$this->error = '请' . $this->validCodeTime() . '秒后发送';

			return false;
		}

		$siteName = v( 'site.info.name' );
		$code     = Tool::rand( 4 );
		if ( preg_match( '/\w+@\w+/', $account ) ) {
			$body   = "{$siteName}的验证码 {$code}。感谢使用 {$siteName} 服务";
			$status = Mail::send( $account, '', $siteName, $body );
			if ( $status ) {
				Session::set( 'validCode', [ 'code' => $code, 'sendtime' => time() ] );
			} else {
				$this->error = '验证码发送失败，请稍候再试';

				return false;
			}
		}
		if ( preg_match( '/^\d{11}$/', $account ) ) {
			$data['mobile']          = $account;
			$data['template_code']   = 'SMS_12840367';
			$data['vars']['code']    = $code;
			$data['vars']['product'] = v( 'site.info.name' );
			$status                  = Sms::send( $data );
			if ( $status ) {
				Session::set( 'validCode', [ 'code' => $code, 'sendtime' => time() ] );
			} else {
				$this->error = '验证码发送失败，请稍候再试<br/>' . Sms::getError();

				return false;
			}
		}

		return true;
	}

//	/**
//	 * 发送验证码邮件
//	 *
//	 * @param $email
//	 *
//	 * @return bool
//	 */
//	public function sendMailCode( $email ) {
//		if ( ! preg_match( '/\w+@\w+/', $email ) ) {
//			$this->error = '邮箱格式错误';
//
//			return false;
//		}
//		$siteName = v( 'site.info.name' );
//		$code     = Tool::rand( 4 );
//		Session::set( 'mailValid', $code );
//		$body   = "{$siteName}的验证码 {$code}。<br/>感谢使用 {$siteName} 服务";
//		$status = Mail::send( $email, '', $siteName, $body );
//		if ( $status ) {
//			return true;
//		} else {
//			$this->error = '验证码发送失败，请稍候再试';
//
//			return false;
//		}
//	}
//
//	/**
//	 * 发送手机验证码
//	 *
//	 * @param $mobile 手机号
//	 *
//	 * @return bool
//	 */
//	public function sendMobileCode( $mobile ) {
//		if ( ! preg_match( '/^\d{11}$/', $mobile ) ) {
//			$this->error = '手机号格式错误';
//
//			return false;
//		}
//		$code = Tool::rand( 4 );
//		Session::set( 'mobileValid', $code );
//		$data['mobile']          = $mobile;
//		$data['template_code']   = 'SMS_12840367';
//		$data['vars']['code']    = $code;
//		$data['vars']['product'] = v( 'site.info.name' );
//		if ( Sms::send( $data ) ) {
//			return true;
//		} else {
//			$this->error = '验证码发送失败，请稍候再试<br/>' . Sms::getError();
//
//			return false;
//		}
//	}

	//返回错误内容
	public function getError() {
		return $this->error;
	}
}