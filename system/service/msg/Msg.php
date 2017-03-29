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
	 * 发送验证码邮件
	 *
	 * @param $email
	 *
	 * @return bool
	 */
	public function sendMailCode( $email ) {
		if ( ! preg_match( '/\w+@\w+/', $email ) ) {
			$this->error = '邮箱格式错误';

			return false;
		}
		$siteName = v( 'site.info.name' );
		$code     = Tool::rand( 4 );
		Session::set( 'mailValid', $code );
		$body   = "{$siteName}的验证码 {$code}。<br/>感谢使用 {$siteName} 服务";
		$status = Mail::send( $email, '', $siteName, $body );
		if ( $status ) {
			return true;
		} else {
			$this->error = '验证码发送失败，请稍候再试';

			return false;
		}
	}

	/**
	 * 发送手机验证码
	 *
	 * @param $mobile 手机号
	 *
	 * @return bool
	 */
	public function sendMobileCode( $mobile ) {
		if ( ! preg_match( '/^\d{11}$/', $mobile ) ) {
			$this->error = '手机号格式错误';

			return false;
		}
		$code = Tool::rand( 4 );
		Session::set( 'mobileValid', $code );
		$data['mobile']          = $mobile;
		$data['template_code']   = 'SMS_12840367';
		$data['vars']['code']    = $code;
		$data['vars']['product'] = v( 'site.info.name' );
		if ( Sms::send( $data ) ) {
			return true;
		} else {
			$this->error = '验证码发送失败，请稍候再试<br/>' . Sms::getError();

			return false;
		}
	}

	//返回错误内容
	public function getError() {
		return $this->error;
	}
}