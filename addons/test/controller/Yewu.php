<?php namespace addons\test\controller;

/**
 * 业务功能
 * 模板目录为模块根目录下的template文件夹
 * 建议模板以控制器名为前缀,这样在模板文件多的时候容易识别
 * @author 模块作者
 * @url http://open.hdcms.com
 */
use module\HdController;

class Yewu extends HdController {

    //动作方法1 
    public function action1() {
    	auth();
        echo '执行了控制器动作';
    }
    //动作方法2 
    public function action2() {
	    auth();
        echo '执行了控制器动作';
    }

}