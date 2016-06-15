<?php if(!defined('HDPHP_PATH'))exit;
return array (
  0 => 
  array (
    'groupname' => '系统设置',
    'menu' => 
    array (
      0 => 
      array (
        'menu_id' => '6',
        'title' => '菜单管理',
        'orderby' => '0',
        'url' => 'core/menu/index',
        'params' => '',
        'pid' => '1',
        'groupname' => '系统设置',
        'isshow' => '1',
        'remark' => '',
        'system' => '0',
      ),
      1 => 
      array (
        'menu_id' => '20',
        'title' => '网站设置',
        'orderby' => '0',
        'url' => 'core/config/edit',
        'params' => '&type=water',
        'pid' => '1',
        'groupname' => '系统设置',
        'isshow' => '1',
        'remark' => '',
        'system' => '0',
      ),
    ),
  ),
  1 => 
  array (
    'groupname' => '管理员设置',
    'menu' => 
    array (
      0 => 
      array (
        'menu_id' => '11',
        'title' => '角色管理',
        'orderby' => '0',
        'url' => 'core/role/index',
        'params' => '',
        'pid' => '1',
        'groupname' => '管理员设置',
        'isshow' => '1',
        'remark' => ' ',
        'system' => '0',
      ),
      1 => 
      array (
        'menu_id' => '12',
        'title' => '管理员管理',
        'orderby' => '0',
        'url' => 'core/manager/index',
        'params' => '',
        'pid' => '1',
        'groupname' => '管理员设置',
        'isshow' => '1',
        'remark' => ' ',
        'system' => '0',
      ),
    ),
  ),
);
?>