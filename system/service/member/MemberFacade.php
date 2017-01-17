<?php namespace system\service\member;
use houdunwang\framework\build\Facade;

//外观构造类
class MemberFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Member';
	}
}