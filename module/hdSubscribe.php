<?php namespace module;

/**
 * 模块订阅消息
 * Class hdSubscribe
 * @package system\core
 * @author 向军
 */
abstract class hdSubscribe
{
    //变量包含了用户信息和关键字信息
    protected $message;

    abstract function handle();
}