<?php namespace system\service\Cloud;
use houdunwang\framework\build\Facade;

//外观构造类
class CloudFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Cloud';
	}
}