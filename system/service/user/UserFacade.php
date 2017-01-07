<?php namespace system\service\User;
use houdunwang\framework\build\Facade;

//外观构造类
class UserFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'User';
	}
}