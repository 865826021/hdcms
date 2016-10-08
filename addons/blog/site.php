<?php namespace addons\blog;
/**
 * 博客模块业务定义
 *
 * @author 向军
 * @url http://open.hdcms.com
 */
use module\hdSite;
class Site extends hdSite
{
	
    //桌面入口导航
    public function doWebZmrk() {
        //这个操作被定义用来呈现 桌面入口导航
    }
    //桌面个人中心导航
    public function doWebZmkrzx() {
        //这个操作被定义用来呈现 桌面个人中心导航
    }                

    //微站首页导航1
    public function doWebWzsydh1() {
        //这个操作被定义用来呈现 微站首页导航图标
    }                

    //微站首页导航2
    public function doWebWzsydh2() {
        //这个操作被定义用来呈现 微站首页导航图标
    }                    

    //微站个人中心导航1
    public function doWebWzgrzx1() {
        //这个操作被定义用来呈现 微站个人中心导航
    }                    

    //微站个人中心导航2
    public function doWebWzgrzx2() {
        //这个操作被定义用来呈现 微站个人中心导航
    }
    //功能封面1
    public function doWebGnfm1() {
    	echo 33;
        //这个操作被定义用来呈现 功能封面
    }
    //功能封面2
    public function doWebGnfm2() {
        //这个操作被定义用来呈现 功能封面
    }                

    //规则列表1
    public function doSiteGzlb1() {
        //这个操作被定义用来呈现 规则列表
    }                

    //规则列表2
    public function doSiteGzlb2() {
        //这个操作被定义用来呈现 规则列表
    }                

    //业务功能1
    public function doSiteYwgn1() {
        //这个操作被定义用来呈现 业务功能导航菜单
    }                

    //业务功能2
    public function doSiteYwgn2() {
        //这个操作被定义用来呈现 业务功能导航菜单
    }
}