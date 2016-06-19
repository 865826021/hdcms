<?php namespace module\cover;

/**
 * 测试模块模块消息处理器
 *
 * @author 后盾网
 * @url http://open.hdcms.com
 */
use module\hdProcessor;

class processor extends hdProcessor {
    //规则编号
    public function handle( $rid ) {
        $res = Db::table( 'reply_cover' )->where( 'rid', '=', $rid )->where( 'siteid', v( 'site.siteid' ) )->first();
        if ( $res ) {
            $data[] = [
                'title'       => $res['title'],
                'discription' => $res['description'],
                'picurl'      => __ROOT__ . '/' . $res['thumb'],
                'url'         => __ROOT__ . '/' . $res['url'],
            ];
            $this->news( $data );

            return TRUE;
        }
    }
}