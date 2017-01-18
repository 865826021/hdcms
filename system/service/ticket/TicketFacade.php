<?php namespace system\service\ticket;
use houdunwang\framework\build\Facade;

//外观构造类
class TicketFacade extends Facade{

	public static function getFacadeAccessor(){
		return 'Ticket';
	}
}