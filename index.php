<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
$f='create table a (uid int(10));
            alter table a add ucreatetime int(10);';
$d = preg_split( '/\r|\n/i', $f );
preg_match('/alter\s+table\s+(.+?)\s/',$d[1],$f);

print_r($f);
//开启调试模式
define( 'DEBUG', TRUE );
//引入框架
require 'hdphp/hdphp.php';
