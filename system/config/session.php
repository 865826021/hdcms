<?php
return [
	//引擎:file,mysql,memcache,redis
<<<<<<< HEAD
	'driver'   => 'mysql',
=======
	'driver'   => 'file',
>>>>>>> d191ef7fd2db8f578f2e53e41b319d65713d9c79
	//session_name
	'name'     => 'hdcmsid',
	//域名
	'domain'   => '',
	//过期时间
	'expire'   => 0,
	#File
	'file'     => [
		'path' => 'storage/session',
	],
	#Mysql
	'mysql'    => [
		'table' => 'core_session',
	],
	#Memcache
	'memcache' => [
		'host' => 'localhost',
		'port' => 11211,
	],
	#Redis
	'redis'    => [
		'host'     => 'localhost',
		'port'     => 11211,
		'password' => '',
		'database' => 0,
	]
];