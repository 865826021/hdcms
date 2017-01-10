<?php namespace system\service\Template;
use houdunwang\framework\build\Facade;

//外观构造类
class TemplateFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Template';
	}
}