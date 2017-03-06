<?php
return [
	//服务提供者
	'providers' => [
		'houdunwang\error\ErrorProvider',
		'houdunwang\cli\CliProvider',
		'houdunwang\middleware\MiddlewareProvider',
		'houdunwang\view\ViewProvider',
		'houdunwang\route\RouteProvider',

		'houdunwang\session\SessionProvider',
		'houdunwang\config\ConfigProvider',
		'houdunwang\loader\LoaderProvider',
		'houdunwang\backup\BackupProvider',
		'houdunwang\cache\CacheProvider',
		'houdunwang\validate\ValidateProvider',
		'houdunwang\log\LogProvider',
		'houdunwang\alipay\AliPayProvider',
		'houdunwang\collection\CollectionProvider',
		'houdunwang\db\DbProvider',
		'houdunwang\database\SchemaProvider',
		'houdunwang\file\FileProvider',
		'houdunwang\response\ResponseProvider',
		'houdunwang\cookie\CookieProvider',
		'houdunwang\xml\XmlProvider',
		'houdunwang\qq\QqProvider',
		'houdunwang\wechat\WeChatProvider',
		'houdunwang\curl\CurlProvider',
		'houdunwang\dir\DirProvider',
		'houdunwang\mail\MailProvider',
		'houdunwang\html\HtmlProvider',
		'houdunwang\crypt\CryptProvider',
		'houdunwang\lang\LangProvider',
		'houdunwang\rbac\RbacProvider',
		'houdunwang\request\RequestProvider',
		'houdunwang\controller\ControllerProvider',
		'houdunwang\code\CodeProvider',
		'houdunwang\image\ImageProvider',
		'houdunwang\tool\ToolProvider',
		'houdunwang\arr\ArrProvider',
		'houdunwang\str\StrProvider',
		'houdunwang\zip\ZipProvider',
		'houdunwang\page\PageProvider',
		'houdunwang\cart\CartProvider',
	],

	//服务外观
	'facades'   => [
		'App'        => 'houdunwang\framework\AppFacade',
		'Backup'     => 'houdunwang\backup\BackupFacade',
		'Cache'      => 'houdunwang\cache\CacheFacade',
		'Loader'     => 'houdunwang\loader\LoaderFacade',
		'Error'      => 'houdunwang\error\ErrorFacade',
		'Validate'   => 'houdunwang\validate\ValidateFacade',
		'Log'        => 'houdunwang\log\LogFacade',
		'View'       => 'houdunwang\view\ViewFacade',
		'Route'      => 'houdunwang\route\RouteFacade',
		'Config'     => 'houdunwang\config\ConfigFacade',
		'Cli'        => 'houdunwang\cli\CliFacade',
		'AliPay'     => 'houdunwang\alipay\AliPayFacade',
		'Collection' => 'houdunwang\collection\CollectionFacade',
		'Db'         => 'houdunwang\db\DbFacade',
		'Schema'     => 'houdunwang\database\SchemaFacade',
		'File'       => 'houdunwang\file\FileFacade',
		'Response'   => 'houdunwang\response\ResponseFacade',
		'Cookie'     => 'houdunwang\cookie\CookieFacade',
		'Xml'        => 'houdunwang\xml\XmlFacade',
		'Qq'         => 'houdunwang\qq\QqFacade',
		'Middleware' => 'houdunwang\middleware\MiddlewareFacade',
		'WeChat'     => 'houdunwang\wechat\WeChatFacade',
		'Curl'       => 'houdunwang\curl\CurlFacade',
		'Dir'        => 'houdunwang\dir\DirFacade',
		'Mail'       => 'houdunwang\mail\MailFacade',
		'Html'       => 'houdunwang\html\HtmlFacade',
		'Crypt'      => 'houdunwang\crypt\CryptFacade',
		'Session'    => 'houdunwang\session\SessionFacade',
		'Lang'       => 'houdunwang\lang\LangFacade',
		'Rbac'       => 'houdunwang\rbac\RbacFacade',
		'Request'    => 'houdunwang\request\RequestFacade',
		'Controller' => 'houdunwang\controller\ControllerFacade',
		'Code'       => 'houdunwang\code\CodeFacade',
		'Image'      => 'houdunwang\image\ImageFacade',
		'Tool'       => 'houdunwang\tool\ToolFacade',
		'Arr'        => 'houdunwang\arr\ArrFacade',
		'Str'        => 'houdunwang\str\StrFacade',
		'Zip'        => 'houdunwang\zip\ZipFacade',
		'Page'       => 'houdunwang\page\PageFacade',
		'Cart'       => 'houdunwang\cart\CartFacade',
	]
];