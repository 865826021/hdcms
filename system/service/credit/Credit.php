<?php namespace system\service\credit;

use system\model\CreditsRecord;
use system\model\Member;
use system\model\MemberGroup;

/**
 * 积分管理
 * Class Credit
 * @package system\service\credit
 */
class Credit {
	/**
	 * 会员积分中文名称
	 *
	 * @param string $creditType 积分类型
	 *
	 * @return string
	 */
	public function title( $creditType ) {
		return v( 'site.setting.creditnames.' . $creditType . '.title' );
	}

	/**
	 * 更改会员积分或余额
	 *
	 * @param array $data
	 * array(
	 *  'uid'=>会员编号,//不设置时取当前会员
	 *  'credittype'=>积分类型,如credit1
	 *  'num'=>数量,负数为减少
	 *  'remark'=>说明
	 * );
	 *
	 * @return bool
	 */
	public function change( array $data ) {
		if ( empty( $data['credittype'] ) || empty( $data['num'] ) || empty( $data['remark'] ) ) {
			message( '积分变动参数错误', 'back', 'error' );
		}
		$data['uid']    = isset( $data['uid'] ) ? $data['uid'] : v( 'user.info.uid' );
		$data['module'] = v( 'module.name' );
		//检测用户
		$member = Member::where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->first();
		if ( empty( $member ) ) {
			message( '当前站点不存在该用户', 'back', 'error' );
		}
		if ( empty( $member[ $data['credittype'] ] ) ) {
			message( '积分类型不存在', 'back', 'error' );
		}
		//动作增加或减少
		$action = $data['num'] > 0 ? 'increment' : 'decrement';
		//用户原积分数量
		$userTickNum = $member[ $data['credittype'] ];
		//减少时不能小于用户现有积分
		if ( $action == 'decrement' && $userTickNum < $data['num'] ) {
			$error = $this->title( $data['credittype'] ) . ' 数量不够';
			message( $error, 'back', 'error' );
		}
		$num = $data['num'] > 0 ? $data['num'] : abs( $data['num'] );
		if ( ! Member::where( 'uid', $data['uid'] )->where( 'siteid', SITEID )->$action( $data['credittype'], $num ) ) {
			$error = '修改会员 ' . $this->title( $data['credittype'] ) . " 失败";
			message( $error, 'back', 'error' );
		}
		//记录变量日志
		$this->log( $data );
		/**
		 * 系统设置根据积分变动用户组时变更之
		 * 用户积分大于组积分的组
		 */
		$group = MemberGroup::where( 'credit', '<=', $member['credit1'] )->orderBy( 'credit', 'DESC' )->first();
		switch ( v( 'site.setting.grouplevel' ) ) {
			case 2:
				//根据总积分多少自动升降
				if ( $group ) {
					$member['group_id'] = $group['id'];
					$member->save();
				}
				break;
			case 3:
				//根据总积分多少只升不降
				$userGroupCredit = MemberGroup::where( 'id', $member['group_id'] )->pluck( 'credit' );
				if ( $group && $group['credit'] > $userGroupCredit ) {
					$member['group_id'] = $group['id'];
					$member->save();
				}
				break;
		}

		return true;
	}

	/**
	 * 记录积分变量日志
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	protected function log( $data ) {
		$model               = new CreditsRecord();
		$model['siteid']     = SITEID;
		$model['uid']        = $data['uid'];
		$model['num']        = $data['num'];
		$model['remark']     = $data['remark'];
		$model['credittype'] = $data['credittype'];
		$model['module']     = $data['module'];
		$model['operator']   = v( 'user.system.user_type' ) == 'admin' ? v( 'user.info.uid' ) : '';

		return $model->save();
	}
}