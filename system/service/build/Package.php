<?php namespace system\service\build;

/**
 * 套餐管理服务
 * Class Package
 * @package system\service\build
 */
class Package extends \system\model\Package {
	/**
	 * 获取站点拥有的套餐数据
	 * 包括站长默认套餐+为站点独立设置的扩展套餐
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return array
	 */
	public function getSiteAllPackageData( $siteId ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteId ] ) ) {
			return $cache[ $siteId ];
		}
		$ids      = $this->getSiteAllPackageIds( $siteId );
		$packages = [ ];
		if ( ! empty( $ids ) ) {
			if ( in_array( - 1, $ids ) ) {
				//拥有所有套餐权限
				$res      = $this->get();
				$packages = $res ? $res->toArray() : [ ];
			} else {
				$res      = $this->whereIn( 'id', $ids )->get();
				$packages = $res ? $res->toArray() : [ ];
			}
		}
		foreach ( $packages as $k => $v ) {
			$packages[ $k ]['modules']  = unserialize( $v['modules'] );
			$packages[ $k ]['template'] = unserialize( $v['template'] );
		}
		//含有[所有服务]套餐时
		if ( in_array( - 1, $ids ) ) {
			array_unshift( $packages, [ 'id' => - 1, 'name' => '所有服务', 'modules' => [ ], 'template' => [ ] ] );
		}

		return $cache[ $siteId ] = $packages;
	}

	/**
	 * 站点所有套餐列表编号
	 * 包括站点默认套餐+为站点独立设置的扩展套餐
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return array 数组中包含-1套餐表示可以使用所有模块
	 */
	public function getSiteAllPackageIds( $siteId ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteId ] ) ) {
			return $cache[ $siteId ];
		}
		//获取站长拥有的套餐
		$default = $this->getSiteDefaultPackageIds( $siteId );
		//站点自定义扩展的套餐
		$ext = $this->getSiteExtPackageIds( $siteId );

		return $cache[ $siteId ] = array_merge( $default, $ext );
	}

	/**
	 * 根据站长用户组获取站点套餐
	 * 不含为站点自定义的扩展套餐
	 * 套餐由用户组套餐+站点扩展套餐构成
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return mixed
	 */
	public function getSiteDefaultPackageIds( $siteId ) {
		static $cache = [ ];
		if ( isset( $cache[ $siteId ] ) ) {
			return $cache[ $siteId ];
		}
		//获取站长拥有的套餐
		$sql = "SELECT ug.package FROM " . tablename( 'user' ) . " u " . "JOIN " . tablename( 'site_user' ) . " su ON u.uid=su.uid ";
		$sql .= "JOIN " . tablename( 'user_group' ) . " ug ON u.groupid=ug.id " . "WHERE su.siteid={$siteId} AND su.role='owner'";
		if ( $res = Db::query( $sql ) ) {
			$cache[ $siteId ] = unserialize( $res[0]['package'] ) ?: [ ];
		} else {
			//没有站长时即为系统管理员添加的站点,默认有所有权限
			$cache[ $siteId ] = [ - 1 ];
		}

		return $cache[ $siteId ];
	}

	/**
	 * 获取为站点自定义扩展套餐编号
	 * 套餐由用户组套餐+站点扩展套餐构成
	 *
	 * @param int $siteId 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtPackageIds( $siteId ) {
		return Db::table( 'site_package' )->where( 'siteid', $siteId )->lists( 'package_id' ) ?: [ ];
	}

	/**
	 * 获取系统所有自定义套餐,不含 基础套餐与所有套餐
	 * @return array
	 */
	public function getSystemAllPackageData() {
		static $cache = [ ];
		if ( ! empty( $cache ) ) {
			return $cache;
		}
		$packages = $this->get()->toArray() ?: [ ];
		foreach ( $packages as $k => $v ) {
			$packages[ $k ]['modules'] = $packages[ $k ]['template'] = [ ];
			if ( $modules = unserialize( $v['modules'] ) ) {
				$packages[ $k ]['modules'] = Db::table( 'modules' )->whereIn( 'name', $modules )->get()->toArray();
			}

			if ( $template = unserialize( $v['template'] ) ) {
				$packages[ $k ]['template'] = Db::table( 'template' )->whereIn( 'name', $template )->get()->toArray();
			}
		}
		array_unshift( $packages, [ 'id' => - 1, 'name' => '所有服务', 'modules' => [ ], 'template' => [ ] ] );
		array_unshift( $packages, [ 'id' => 0, 'name' => '基础服务', 'modules' => [ ], 'template' => [ ] ] );

		return $cache = $packages;
	}

	/**
	 * 根据会员组编号获取该会员组的所有套餐信息
	 *
	 * @param int $groupId 会员组编号
	 *
	 * @return array
	 */
	public function getUserGroupPackageLists( $groupId ) {
		$group = Db::table( 'user_group' )->where( 'id', $groupId )->find();
		//用户套餐
		$packageIds = unserialize( $group['package'] );
		$packages   = $group['package'] ? ( $this->whereIn( 'id', $packageIds )->get() ?: [ ] ) : [ ];
		foreach ( (array) $packages as $k => $p ) {
			$packages[ $k ]['modules']  = $p['modules'] ? Db::table( 'modules' )->whereIn( 'name', unserialize( $p['modules'] ) )->get() : [ ];
			$packages[ $k ]['template'] = $p['template'] ? Db::table( 'template' )->whereIn( 'name', unserialize( $p['template'] ) )->get() : [ ];
		}
		if ( in_array( - 1, $packageIds ) ) {
			array_unshift( $packages, [ 'id' => - 1, 'name' => '所有服务', 'modules' => '', 'template' => '' ] );
		}

		return $packages;
	}
}