<?php namespace addons\salary\controller;

/**
 * 薪资管理
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 后盾团队
 * @url http://open.hdcms.com
 */
use module\HdController;

class Manage extends HdController {

    //学生列表 
    public function lists() {
        echo '执行了控制器动作';
    }
    //添加学生 
    public function post() {
        echo '执行了控制器动作';
    }

}