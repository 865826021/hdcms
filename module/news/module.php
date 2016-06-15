<?php namespace module\news;

/**
 * 测试模块模块定义
 *
 * @author 后盾网
 * @url http://open.hdcms.com
 */
use module\HdModule;

class Module extends HdModule
{

    public function settingsDisplay($settings)
    {

    }

    public function fieldsDisplay($rid = 0)
    {
        //要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
        $contents = array();
        if ($rid)
        {
            //顶级菜单
            $contents = Db::table('reply_news')->where('rid', $rid)->where('pid', 0)->orderBy('rank', 'desc')->orderBy('id', 'ASC')->get();
            //子级菜单
            foreach ($contents as $k => $t)
            {
                $news                     = Db::table('reply_news')->where('rid', $rid)->where('pid', $t['id'])->orderBy('id', 'ASC')->get();
                $contents[$k]['son_news'] = $news ?: array();
            }
        }
        View::with('contents', json_encode($contents ?: array()));
        return View::fetch($this->template . '/fieldsDisplay.html');
    }

    public function fieldsValidate($rid = 0)
    {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        if (empty($_POST['content']))
        {
            return '内容不能为空';
        }
        return '';
    }

    public function fieldsSubmit($rid)
    {
        Db::table('reply_news')->where('rid', $rid)->delete();
        $content = json_decode($_POST['content'], true);
        foreach ($content as $c)
        {
            //添加一级图文
            $c['rid']         = $rid;
            $c['pid']         = 0;
            $c['title']       = isset($c['title']) ? $c['title'] : '';
            $c['author']      = isset($c['author']) ? $c['author'] : '';
            $c['description'] = isset($c['description']) ? $c['description'] : '';
            $c['thumb']       = isset($c['thumb']) ? $c['thumb'] : '';
            $c['content']     = isset($c['content']) ? $c['content'] : '';
            $c['url']         = isset($c['url']) ? $c['url'] : '';
            $c['rank']        = isset($c['rank']) ? $c['rank'] : 0;
            $c['createtime']  = time();
            $pid              = Db::table('reply_news')->replaceGetId($c);
            //添加二级图文
            foreach ($c['son_news'] as $d)
            {
                $d['rid']         = $rid;
                $d['pid']         = $pid;
                $d['title']       = isset($d['title']) ? $d['title'] : '';
                $d['author']      = isset($d['author']) ? $d['author'] : '';
                $d['description'] = isset($d['description']) ? $d['description'] : '';
                $d['thumb']       = isset($d['thumb']) ? $d['thumb'] : '';
                $d['content']     = isset($d['content']) ? $d['content'] : '';
                $d['url']         = isset($d['url']) ? $d['url'] : '';
                $d['rank']        = isset($d['rank']) ? $d['rank'] : 0;
                $d['createtime']  = time();
                Db::table('reply_news')->insertGetId($d);
            }
        }
    }

    public function ruleDeleted($rid)
    {
        //删除规则时调用，这里 $rid 为对应的规则编号
        Db::table('reply_news')->where('rid', $rid)->delete();
    }
}