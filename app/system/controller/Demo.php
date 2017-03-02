<?php namespace app\system\controller;

use houdunwang\oss\Oss;

/**
 * 测试控制器
 * Class Demo
 * @package app\system\controller
 */
class Demo {
	public function test() {
		$d = Oss::uploadFile('aaa1222.jpg', 'resource/images/bg.jpg');
		p($d);
		return view();
	}

	public function upload(){
//		c('oss.accessKeyId','VUFGPITAyRwwi296');
//		c('oss.accessKeySecret','DQDn3RSYzZ8OgZrUUfcRrnPYJgZ43r');
//		c('oss.endpoint','oss-cn-hangzhou.aliyuncs.com');
//		c('oss.bucket','houdunren');
		$d = Oss::uploadFile('hdcms.zip', 'hdcms.zip');
		dd($d);
		exit;
		$accessKeyId = "VUFGPITAyRwwi296";
		$accessKeySecret = "DQDn3RSYzZ8OgZrUUfcRrnPYJgZ43r";
		$endpoint = "oss-cn-hangzhou.aliyuncs.com";
		$bucket= "houdunren";
		$object = "1.mp4";
		$content = "Hi, OSS.";
		$filePath = '/www/1.mp4';
		try {
			$ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
			$ossClient->uploadFile($bucket, $object, $filePath);
		} catch (OssException $e) {
			print $e->getMessage();
		}
	}
}