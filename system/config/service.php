<?php
return [
	/*
	|--------------------------------------------------------------------------
	| 提供者
	|--------------------------------------------------------------------------
	| 服务提供者是用于生产服务实例的指令
	| 生成的服务实例会保存的系统的IOC窗口中便于全局调用
	*/
	'providers' => [
		'system\service\user\UserProvider',
		'system\service\member\MemberProvider',
		'system\service\package\PackageProvider',
		'system\service\site\SiteProvider',
		'system\service\module\ModuleProvider',
		'system\service\cloud\CloudProvider',
		'system\service\menu\MenuProvider',
		'system\service\template\TemplateProvider',
		'system\service\wx\WxProvider',
		'system\service\credit\CreditProvider',
		'system\service\ticket\TicketProvider',
		'system\service\web\WebProvider',
		'system\service\navigate\NavigateProvider'
	],

	/*
	|--------------------------------------------------------------------------
	| 外观
	|--------------------------------------------------------------------------
	| 定义了服务外观后可以不用实例化对象调用服务
	| 比如我们常用的View::with()指令
	| 就是因为定义了视图服务的View外观所以我们不用实例化对象就可以使用
	*/
	'facades'   => [
		'User'     => 'system\service\user\UserFacade',
		'Member'   => 'system\service\member\MemberFacade',
		'Package'  => 'system\service\package\PackageFacade',
		'Site'     => 'system\service\site\SiteFacade',
		'Module'   => 'system\service\module\ModuleFacade',
		'Cloud'    => 'system\service\cloud\CloudFacade',
		'Menu'     => 'system\service\menu\MenuFacade',
		'Template' => 'system\service\template\TemplateFacade',
		'Wx'       => 'system\service\wx\WxFacade',
		'Credit'   => 'system\service\credit\CreditFacade',
		'Ticket'   => 'system\service\ticket\TicketFacade',
		'Web'      => 'system\service\web\WebFacade',
		'Navigate' => 'system\service\navigate\NavigateFacade'
	]
];