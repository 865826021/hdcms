<?php namespace addons\test\controller;

/**
 * 文章管理
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 模块作者
 * @url http://open.hdcms.com
 */
use module\HdController;

class Article extends HdController {

    //列表 
    public function lists() {
        echo '执行了控制器动作';
    }
    //添加 
    public function add() {
        echo '执行了控制器动作';
    }

}