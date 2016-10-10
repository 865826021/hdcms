<?php
return [
	//服务提供者
	'providers' => [
		'system\service\UtilProvider'
	],

	//服务外观
	'facades'   => [
		'Util' => 'system\service\UtilFacade'
	],
];