<?php namespace system\service\build;
/**
 * 积分管理服务
 * Class Credit
 * @package system\service\build
 */
class Credit {
	/**
	 * 会员积分中文名称
	 *
	 * @param string $creditType 积分类型
	 *
	 * @return string
	 */
	public function getCreditTitle( $creditType ) {
		return v( 'setting.creditnames.' . $creditType . '.title' );
	}

	/**
	 * 更改会员积分或余额
	 *
	 * @param array $data
	 * array(
	 *  'uid'=>会员编号,
	 *  'credittype'=>积分类型,如credit1
	 *  'num'=>数量,负数为减少
	 *  'module'=>变动积分的模块
	 *  'remark'=>说明
	 * );
	 *
	 * @return bool
	 */
	public function changeCredit( array $data ) {
		if ( empty( $data['uid'] ) || empty( $data['credittype'] ) || empty( $data['num'] ) || empty( $data['remark'] ) ) {
			$this->error = '参数错误';

			return FALSE;
		}
		$data['module'] = isset( $data['module'] ) ? $data['module'] : '';
		//检测兑换数量
		$userTickNum = $this->where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->pluck( $data['credittype'] );
		if ( $userTickNum < $data['num'] ) {
			$this->error = $this->getCreditTitle( $data['credittype'] ) . ' 数量不够';

			return FALSE;
		}
		//执行
		$action      = $data['num'] > 0 ? 'increment' : 'decrement';
		$data['num'] = $data['num'] > 0 ? $data['num'] : abs( $data['num'] );
		if ( ! $this->where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->$action( $data['credittype'], $data['num'] ) ) {
			$this->error = '修改会员 ' . $this->getCreditTitle( $data['credittype'] ) . " 失败";

			return FALSE;
		}
		//记录变量日志
		$RecordModel = new CreditsRecord();
		if ( ! $RecordModel->add( $data ) ) {
			$this->error = $RecordModel->getError();

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * 根据积分字段获取中文描述
	 *
	 * @param $name
	 * <code>
	 *  api('credit')->getTitle('credit1');
	 * </code>
	 *
	 * @return string 积分中文描述
	 */
	public function getTitle( $name ) {
		return v( 'setting.creditnames.' . $name . '.title' );
	}
}