<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace api;

//公众号管理接口
class wechat {
    /**
     * 获取公众号类型
     *
     * @param $level 公众号编号
     *
     * @return mixed
     */
    public function chatNameBylevel( $level ) {
        $data = [ 1 => '普通订阅号', 2 => '普通服务号', 3 => '认证订阅号', 4 => '认证服务号/认证媒体/政府订阅号' ];

        return $data[ $level ];
    }

}