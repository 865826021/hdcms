<?php namespace module;

/**
 * 模块处理消息
 * Class hdProcessor
 * @package system\core
 * @author 向军
 */
abstract class hdProcessor {
    //模块数据
    protected $module
        = [
            //模块配置项
            'config' => [ ]
        ];

    /**
     * hdProcessor constructor.
     *
     * @param $module 模块名称
     */
    public function __construct( $module ) {
    }

    //回复方法
    abstract function handle( $rid );

    public function __call( $name, $arguments ) {
        $instance = Weixin::instance( 'message' );
        if ( method_exists( $instance, $name ) ) {
            call_user_func_array( [ $instance, $name ], $arguments );
        }
    }
}