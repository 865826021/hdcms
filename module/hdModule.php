<?php namespace module;

/**
 * 模块控制类 module.php继承
 * Class HdModule
 * @package module
 * @author 向军
 */
abstract class HdModule
{
    //模板目录
    protected $template;

    //配置项
    protected $config;

    //构造函数
    public function __construct()
    {
        $this->template = (v('module.is_system') ? "module/" : "addons/") . v('module.name') . '/template';
        $setting        = Db::table('module_setting')->where('siteid', '=', SITEID)->where('module', '=', v('module.name'))->pluck('setting');
        $this->config   = unserialize($setting);
        define('__TEMPLATE__', $this->template);
    }

    //保存模块配置
    public function saveSettings($field)
    {
        $id              = Db::table('module_setting')->where('siteid', '=', v('site.siteid'))->where('module', '=', v('module.name'))->pluck('id');
        $data['siteid']  = v("site.siteid");
        $data['module']  = v('module.name');
        $data['status']  = 1;
        $data['setting'] = serialize($field);
        if ($id)
        {
            Db::table('module_setting')->where('id', '=', $id)->update($data);
        }
        else
        {
            Db::table('module_setting')->insert($data);
        }
    }
}