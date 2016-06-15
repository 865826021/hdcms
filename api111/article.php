<?php namespace api;
class article {
	/**
	 * 添加导航
	 *
	 * @param $data
	 * array(
	 *  id=>编号,(编辑时需要)
	 *  siteid=>站点编号,
	 *  web_id=>微站编号,(必须)
	 *  module=>模块标识,
	 *  name=>菜单名称,(必须)
	 *  url=>链接,(必须)
	 *  css=>样式,
	 *  status=>开启,
	 *  category_cid=>栏目编号,
	 *  description=>描述,
	 *  position=>位置,
	 *  orderby=>排序,
	 *  icontype=>图标类型,(必须)
	 *  entry=>导航类型(必须)
	 * )
	 *
	 * @return bool
	 */
	public function addNav( $data ) {
		$data['siteid']       = empty( $data['siteid'] ) ? v( 'site.siteid' ) : $data['siteid'];
		$data['module']       = empty( $data['module'] ) ? '' : $data['module'];
		$data['css']          = isset( $data['css'] ) ? json_encode( $data['css'] ) :  '' ;
		$data['status']       = isset( $data['status'] ) ? $data['status'] : 1;
		$data['web_id']       = isset( $data['web_id'] ) ? $data['web_id'] : 0;
		$data['orderby']      = empty( $data['orderby'] ) ? 0 : min( 255, $data['orderby'] );
		$data['category_cid'] = empty( $data['category_cid'] ) ? 0 : $data['category_cid'];
		$data['description']  = empty( $data['description'] ) ? '' : $data['description'];
		$data['position']     = isset( $data['position'] ) ? $data['position'] : 1;
		if ( empty( $data['url'] ) || empty( $data['name'] ) || empty( $data['entry'] ) || empty( $data['icontype'] ) ) {
			return FALSE;
		}

		return Db::table( 'web_nav' )->replaceGetId( $data );
	}

	/**
	 * 保存文章
	 *
	 * @param $data
	 * array(
	 *  aid=>文章编号(有时编辑)
	 * )
	 *
	 * @return mixed
	 */
	public function saveArticle( $data ) {
		$data['siteid']       = empty( $data['siteid'] ) ? v( 'site.siteid' ) : $data['siteid'];
		$data['rid']          = empty( $data['rid'] ) ? 0 : $data['rid'];
		$data['iscommend']    = empty( $data['iscommend'] ) ? 0 : $data['iscommend'];
		$data['ishot']        = empty( $data['ishot'] ) ? 0 : $data['ishot'];
		$data['template_tid'] = empty( $data['template_tid'] ) ? 0 : $data['template_tid'];
		$data['description']  = empty( $data['description'] ) ? '' : $data['description'];
		$data['source']       = empty( $data['source'] ) ? '' : $data['source'];
		$data['author']       = empty( $data['author'] ) ? '' : $data['author'];
		$data['orderby']      = empty( $data['orderby'] ) ? 0 : min( 255, intval( $data['orderby'] ) );
		$data['linkurl']      = empty( $data['linkurl'] ) ? '' : $data['linkurl'];
		$data['createtime']   = time();
		$data['click']        = empty( $data['click'] ) ? 0 : intval( $data['click'] );
		$data['thumb']        = empty( $data['thumb'] ) ? '' : $data['thumb'];
		if ( empty( $data['title'] ) || empty( $data['category_cid'] ) || empty( $data['content'] ) ) {
			return FALSE;
		}

		return Db::table( 'web_article' )->replaceGetId( $data );
	}
}