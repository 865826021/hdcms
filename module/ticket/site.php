<?php namespace module\ticket;

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
    protected $names = array( 1 => '折扣券', 2 => '代金券' );

    public function __construct($module)
    {
        parent::__construct($module);
        View::with('ticket_name', $this->names[$_GET['type']]);
    }

    //折扣券列表
    public function doSitelists()
    {
        $data = Db::table('ticket')->where('siteid', v("site.siteid"))->where('type', $_GET['type'])->get();
        foreach ($data as $k => $v)
        {
            $usertotal             = Db::table('ticket_record')->where('tid', $v['tid'])->count();
            $data[$k]['usertotal'] = intval($usertotal);
        }
        View::with('data', $data);
        View::make($this->template . '/lists.html');
    }

    //添加折扣券
    public function doSitepost()
    {
        if (IS_POST)
        {
            if (!empty($_POST['tid']))
            {
                //编辑时添加主键
                $data['tid'] = $_POST['tid'];
            }
            $time = preg_split('/\s+至\s+/', $_POST['times']);
            //添加卡券表
            $data['siteid']      = v('site.siteid');
            $data['sn']          = 'HD' . strtoupper(substr(md5(time()), 0, 15));
            $data['type']        = q('type');
            $data['condition']   = is_numeric($_POST['condition']) ? $_POST['condition'] : 0;
            $data['discount']    = is_numeric($_POST['discount']) ? $_POST['discount'] : 0;
            $data['title']       = $_POST['title'];
            $data['thumb']       = $_POST['thumb'] ?: '';
            $data['credittype']  = $_POST['credittype'];
            $data['description'] = $_POST['description'];
            $data['credit']      = intval($_POST['credit']);
            $data['limit']       = intval($_POST['limit']);
            $data['amount']      = intval($_POST['amount']);
            $data['starttime']   = empty($time[0]) ? time() : strtotime(trim($time[0]));
            $data['endtime']     = empty($time[1]) ? time() : strtotime(trim($time[1]));
            $tid                 = Db::table('ticket')->replaceGetId($data);
            //模块设置
            Db::table('ticket_module')->where('siteid', v('site.siteid'))->where('tid', $tid)->delete();
            foreach ($_POST['module'] as $module)
            {
                $data['siteid'] = v('site.siteid');
                $data['tid']    = $tid;
                $data['module'] = $module;
                Db::table('ticket_module')->insert($data);
            }

            //会员组
            Db::table('ticket_groups')->where('siteid', v('site.siteid'))->where('tid', $tid)->delete();
            foreach ($_POST['groups'] as $group_id)
            {
                $data['siteid']   = v('site.siteid');
                $data['tid']      = $tid;
                $data['group_id'] = $group_id;
                Db::table('ticket_groups')->insert($data);
            }
            message('积分数据更新成功', u('post', array( 'type' => q('type'), 'tid' => $tid )));
        }
        //文字描述
        if (q('get.type') == 1)
        {
            $msg = array(
                'module'      => array( 'title' => '折扣券' ),
                'title'       => array( 'title' => '折扣券名称', 'help' => '' ),
                'condition'   => array( 'title' => '满多少钱可打折', 'help' => '请填写整数。默认订单金额大于0元就可以使用' ),
                'discount'    => array( 'title' => '折扣', 'help' => '请填写0-1的小数。' ),
                'description' => array( 'title' => '折扣券说明', 'help' => '' ),
                'amount'      => array( 'title' => '折扣券总数量', 'help' => '此设置项设置折扣券的总发行数量。' ),
                'limit'       => array( 'title' => '每人可使用数量', 'help' => '此设置项设置每个用户可领取此折扣券数量。' ),
            );
        }
        else
        {
            $msg = array(
                'module'      => array( 'title' => '代金券' ),
                'title'       => array( 'title' => '代金券名称', 'help' => '' ),
                'condition'   => array( 'title' => '使用条件', 'help' => '订单满多少钱可用。' ),
                'discount'    => array( 'title' => '代金券面额', 'help' => '代金券面额必须少于使用条件的金额。' ),
                'description' => array( 'title' => '代金券说明', 'help' => '' ),
                'amount'      => array( 'title' => '代金券总数量', 'help' => '此设置项设置代金券的总发行数量。' ),
                'limit'       => array( 'title' => '每人可使用数量', 'help' => '此设置项设置每个用户可领取此代金券数量。' ),
            );
        }
        View::with('msg', $msg);

        $tid       = q('get.tid', 0, 'intval');
        $field     = Db::table('ticket')->where('siteid', v('site.siteid'))->where('tid', $tid)->first();
        $groups    = Db::table('member_group')->get();
        $modules   = Db::table('ticket_module tg')->join('modules m', 'tg.module', '=', 'm.name')->where('siteid', v('site.siteid'))->where('tid', $tid)->get();
        $groupsIds = Db::table('ticket_groups')->where('siteid', v('site.siteid'))->where('tid', $tid)->lists('group_id');
        View::with(array(
            'field'     => $field,
            'groups'    => $groups,
            'modules'   => $modules,
            'groupsIds' => $groupsIds
        ));
        View::make($this->template . '/post.html');
    }

    //折扣券
    public function doSitediscount()
    {

        $this->post();
    }

    //代金券
    public function doSitecoupon()
    {
        $this->post();
    }

    //核销
    public function doSitecharge()
    {
        $sql  = "SELECT * FROM hd_member m JOIN hd_ticket_record tr ON m.uid=tr.uid JOIN hd_ticket t ON t.tid=tr.tid
                WHERE t.type={$_GET['type']}";
        $data = Db::select($sql);
        View::make($this->template . '/charge.html');
    }
}