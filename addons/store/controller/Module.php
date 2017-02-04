<?php namespace addons\store\controller;

/**
 * 模块管理
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\HdController;

class Module extends HdController {

    //审核模块 
    public function audit() {
        echo '执行了控制器动作';
    }

    //模块列表
    public function lists() {
        echo '执行了控制器动作';
    }
}