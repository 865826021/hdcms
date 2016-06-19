<?php namespace module\special;

use module\hdSite;

/**
 * 微信关注时或默认回复内容设置
 *
 * @author 后盾网
 * @url http://open.hdcms.com
 */
class site extends hdSite
{
    //系统消息回复设置
    public function doSitePost()
    {
        if (IS_POST)
        {
            $data['welcome']         = $_POST['welcome'];
            $data['default_message'] = $_POST['default'];
            Db::table('site_setting')->where('siteid', v('site.siteid'))->update($data);
            (new \system\model\Site())->updateSiteCache(v('site.siteid'));
            message('系统回复消息设置成功', '', 'success');
        }
        View::make($this->template.'/post.html');
    }
}