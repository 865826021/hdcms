<?php
require 'vendor/autoload.php';
$config = [
	//允许上传类型
	'type' => 'jpg,jpeg,gif,png,zip,rar,doc,txt,pem',
	//允许上传大小单位KB
	'size' => 10000,
	//上传路径
	'path' => 'attachment',
];
\system\model\Config::set( $config );
$obj = new \houdunwang\file\File();