<?php namespace module;

/**
 * 模块业务基类
 * Class hdSite
 * @package system\core
 * @author 向军
 */
abstract class hdSite
{
    //模板目录
    protected $template;

    //配置项
    protected $config;

    //构造函数
    public function __construct()
    {
        $this->template = (v('module.is_system') ? "module/" : "addons/") . v('module.name') . '/template';
        $setting        = Db::table('module_setting')->where('siteid', '=', v('site.siteid'))->where('module', '=', v('module.anme'))->pluck('setting');
        $this->config   = unserialize($setting);
        define('__TEMPLATE__', $this->template);
    }

}