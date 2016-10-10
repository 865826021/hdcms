<?php
<<<<<<< HEAD
$database = [
=======
return [
>>>>>>> d191ef7fd2db8f578f2e53e41b319d65713d9c79
	//读库列表
	'read'     => [ ],
	//写库列表
	'write'    => [ ],
	//开启读写分离
	'proxy'    => FALSE,
	//主机
	'host'     => 'localhost',
	//类型
	'driver'   => 'mysql',
	//帐号
	'user'     => 'root',
	//密码
<<<<<<< HEAD
	'password' => '',
	//数据库
	'database' => '',
	//表前缀
	'prefix'   => 'hd_'
];

return array_merge( $database, include __DIR__ . '/../../data/database.php',include __DIR__ . '/../../data/upgrade.php' );
=======
	'password' => 'admin888',
	//数据库
	'database' => 'hdphp',
	//表前缀
	'prefix'   => 'hd_'
];
>>>>>>> d191ef7fd2db8f578f2e53e41b319d65713d9c79
