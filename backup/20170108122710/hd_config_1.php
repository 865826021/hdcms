<?php 
\houdunwang\db\Db::table('hd_config',true)->replace(array (
  'id' => '1',
  'site' => '{"is_open":"1","enable_code":"0","close_message":"网站维护中,请稍候访问","upload":{"size":20000,"type":"jpg,jpeg,gif,png,zip,rar,doc,txt,pem"}}',
  'register' => '{"is_open":"0","audit":"1","enable_code":"0","groupid":"1"}',
));
