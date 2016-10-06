<?php namespace system\service\build;
/**
 * 模板管理服务
 * Class Template
 * @package system\service\build
 */
class Template {
	/**
	 * 获取站点扩展模板数据
	 *
	 * @param $siteid 网站编号
	 *
	 * @return array
	 */
	public function getSiteExtTemplates( $siteid ) {
		$template = model( 'SiteTemplate' )->where( 'siteid', $siteid )->lists( 'template' );

		return $template ? model( 'template' )->whereIn( 'name', $template )->get() : [ ];
	}

	/**
	 * 获取站点扩展模板
	 *
	 * @param int $siteid 站点编号
	 *
	 * @return array
	 */
	public function getSiteExtTemplateName( $siteid ) {
		return $this->where( 'siteid', $siteid )->lists( 'template' );
	}

	/**
	 * 获取站点所有模板
	 *
	 * @param int $siteid 站点编号
	 * @param string $type 模板类型
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getSiteAllTemplate( $siteid = NULL, $type = NULL ) {
		$siteid = $siteid ?: Session::get( 'siteid' );
		if ( empty( $siteid ) ) {
			throw new \Exception( '$siteid 参数错误' );
		}
		static $cache = [ ];
		if ( isset( $cache[ $siteid ] ) ) {
			return $cache[ $siteid ];
		}
		//获取站点可使用的所有套餐
		$package   = ( new Package() )->getSiteAllPackageData( $siteid );
		$templates = [ ];
		if ( ! empty( $package ) && $package[0]['id'] == - 1 ) {
			//拥有[所有服务]套餐
			$templates = $this->get();
		} else {
			$templateNames = [ ];
			foreach ( $package as $p ) {
				$templateNames = array_merge( $templateNames, $p['template'] );
			}
			$templateNames = array_merge( $templateNames, ( new SiteTemplate() )->getSiteExtTemplateName( $siteid ) );
			if ( ! empty( $templateNames ) ) {
				if ( $type ) {
					$this->where( 'type', $type );
				}
				$templates = $this->whereIn( 'name', $templateNames )->get();
			}
		}

		return $cache[ $siteid ] = $templates;
	}

	/**
	 * 获取模板位置数据
	 *
	 * @param $tid 模板编号
	 *
	 * @return array
	 * array(
	 *  1=>'位置1',
	 *  2=>'位置2',
	 * )
	 */
	public function getPositionData( $tid ) {
		$position = $this->where( 'tid', $tid )->pluck( 'position' );
		$data     = [ ];
		if ( $position ) {
			for ( $i = 1;$i <= $position;$i ++ ) {
				$data[ $i ] = '位置' . $i;
			}
		}

		return $data;
	}

	/**
	 * 获取模板类型
	 * @return array
	 */
	public function getTitleLists() {
		return [
			'often'       => '常用模板',
			'rummery'     => '酒店',
			'car'         => '汽车',
			'tourism'     => '旅游',
			'drink'       => '餐饮',
			'realty'      => '房地产',
			'medical'     => '医疗保健',
			'education'   => '教育',
			'cosmetology' => '健身美容',
			'shoot'       => '婚纱摄影',
			'other'       => '其他'
		];
	}

	/**
	 * 获取站点的模板数据
	 *
	 * @param int $webid 站点编号
	 *
	 * @return array 模板数据
	 */
	public function getTemplateData( $webid ) {
		$template_tid = $this->where( 'siteid', SITEID )->where( 'id', $webid )->pluck( 'template_tid' );

		return Db::table( 'template' )->where( 'tid', $template_tid )->first();
	}
}