<?php namespace module\setting;
use module\hdSite;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
class site extends hdSite
{
    //积分设置
    public function doSiteCredit()
    {
        if (IS_POST)
        {
            $data = array();
            foreach ($_POST as $credit => $d)
            {
                if (preg_match_all('/^credit/', $credit))
                {
                    $d['status']   = intval($d['status']);
                    $data[$credit] = $d;
                }
            }
            $res['siteid']      = v('site.siteid');
            $res['creditnames'] = serialize($data);
            Db::table('site_setting')->where('siteid', v('site.siteid'))->update($res);
            api('site')->updateSiteCache(v('site.siteid'));
            message('积分设置成功', '', 'success');
        }
        View::make($this->template . '/credit.html');
    }

    //积分策略
    public function doSiteTactics()
    {
        if (IS_POST)
        {
            $credit['activity']      = $_POST['activity'];
            $credit['currency']      = $_POST['currency'];
            $data['creditbehaviors'] = serialize($credit);
            Db::table('site_setting')->where('siteid', v('site.siteid'))->update($data);
            api('site')->updateSiteCache(v('site.siteid'));
            message('积分策略更新成功', '', 'success');
        }
        View::make($this->template . '/tactics.html');
    }

    //注册设置
    public function doSiteRegister()
    {
        if (IS_POST)
        {
            $data['register'] = serialize($_POST['register']);
            Db::table('site_setting')->where('siteid', v('site.siteid'))->update($data);
            api('site')->updateSiteCache(v('site.siteid'));
            message('修改会员注册设置成功', '', 'success');
        }
        View::make($this->template . '/register.html');
    }

    //邮件通知设置
    public function doSiteMail()
    {
        if (IS_POST)
        {
            $data['smtp'] = serialize($_POST['smtp']);
            Db::table('site_setting')->where('siteid', v('site.siteid'))->update($data);
            api('site')->updateSiteCache(v('site.siteid'));
            message('修改会员注册设置成功', '', 'success');
        }
        View::make($this->template . '/mail.html');
    }

    //支付设置
    public function doSitePay()
    {
        if (IS_POST)
        {
            $data['pay'] = serialize($_POST['pay']);
            Db::table('site_setting')->where('siteid', v('site.siteid'))->update($data);
            api('site')->updateSiteCache(v('site.siteid'));
            message('修改会员支付参数成功', '', 'success');
        }
        $wechat = Db::table('site_wechat')->where('siteid', v('site.siteid'))->first();
        View::with(array( 'wechat' => $wechat ));
        View::make($this->template . '/pay.html');
    }
}