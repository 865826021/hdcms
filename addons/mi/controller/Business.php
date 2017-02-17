<?php namespace addons\mi\controller;

/**
 * 业务功能
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author author
 * @url http://open.hdcms.com
 */
use module\HdController;

class Business extends HdController {

    //控制器动作 
    public function action() {
        echo '执行了控制器动作';
    }

}