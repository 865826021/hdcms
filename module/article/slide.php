<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\article;

use module\hdSite;

/**
 * 站点幻灯图
 * Class slide
 * @package module\article
 */
class slide extends hdSite {
    //官网编号
    protected $webid;

    public function __construct() {
        parent::__construct();
        $this->webid = q( 'get.webid' );
        if ( ! api( 'web' )->has( $this->webid ) ) {
            message( '你访问的官网不存在', 'back', 'error' );
        }
    }

    //列表
    public function doSiteLists() {
        if ( IS_POST ) {
            $db = Db::table( 'web_slide' );
            foreach ( $_POST['slide'] as $id => $order ) {
                $data['displayorder'] = $order;
                $db->table( 'web_slide' )->where( 'id', '=', $id )->update( $data );
            }
            message( '保存成功', 'refresh', 'success' );
        }
        $data = Db::table( 'web_slide' )->where( 'web_id', '=', $this->webid )->get();
        View::with( 'data', $data );
        View::make( $this->template . '/slide/lists.php' );
    }

    //添加&修改
    public function doSitePost() {
        if ( IS_POST ) {
            $_POST['siteid'] = v( 'site.siteid' );
            $_POST['web_id'] = $this->webid;
            if ( $id = q( 'get.id' ) ) {
                $_POST['id'] = $id;
            }
            Db::table( 'web_slide' )->replace( $_POST );
            message( '幻灯片保存成功', site_url( 'lists', [ 'webid' => $this->webid ] ), 'success' );
        }
        $field = Db::table( 'web_slide' )->where( 'id', '=', q( 'get.id' ) )->first();
        View::with( 'field', $field );
        //官网列表
        $web = Db::table( 'web' )->where( 'id', '=', $this->webid )->first();
        View::with( [ 'web' => $web ] );
        View::make( $this->template . '/slide/post.php' );
    }

    //删除
    public function doSiteRemove() {
        Db::table( 'web_slide' )->where( 'id', '=', q( 'get.id' ) )->delete();
        message( '删除幻灯片成功', 'back', 'success' );
    }
}