<?php namespace app\system\controller;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use system\model\Modules;

/**
 * 模块管理
 * Class Module
 * @package app\system\controller
 * @author 向军 <2300071698@qq.com>
 * @site www.houdunwang.com
 */
class Module {
	protected $module;

	public function __construct() {
		\User::superUserAuth();
	}

	//打开本地开发的模块
	public function createZip() {
		$name = q( 'get.name' );
		Zip::PclZip( "app.zip" );//设置压缩文件名
		Zip::create( "addons/{$name}" );//压缩目录
		\Tool::download( "app.zip", $name . '.zip' );
	}

	//获取云商店的模块
	public function getCloudModules() {
		$data = Db::table( 'cloud' )->find( 1 );
		$post = [
			'type'      => 'addons',
			'uid'       => $data['uid'],
			'AppSecret' => $data['AppSecret']
		];
		$res  = \Curl::post( c( 'api.cloud' ) . '?a=site/GetUserApps&t=web&siteid=1&m=store&type=addons', $post );
		$res  = json_decode( $res, 'true' );
		$apps = [ ];
		foreach ( $res['apps'] as $k => $v ) {
			$v          = json_decode( $v['xml'], true );
			$apps[ $k ] = $v;
		}
		//缓存
		d( 'cloudModules', $apps );
		echo json_encode( $apps );
	}

	//已经安装模块
	public function installed() {
		$modules = Modules::where( 'is_system', 0 )->get() ?: [ ];
		foreach ( $modules as $k => $m ) {
			//本地模块
			$modules[ $k ]['cover'] = is_file( "addons/{$m['name']}/{$m['cover']}" ) ?
				"addons/{$m['name']}/{$m['cover']}" : "resource/images/nopic_small.jpg";
		}

		return view()->with( [ 'modules' => $modules ] );
	}

	//安装新模块列表
	public function prepared() {
		$modules = Db::table( 'modules' )->lists( 'name' );
		$dirs    = Dir::tree( 'addons' );
		//本地模块
		$locality = [ ];
		foreach ( $dirs as $d ) {
			if ( $d['type'] == 'dir' && is_file( $d['path'] . '/manifest.xml' ) ) {
				$xml = Xml::toArray( file_get_contents( $d['path'] . '/manifest.xml' ) );
				//去除已经安装的模块
				if ( ! in_array( $xml['manifest']['application']['name']['@cdata'], $modules ) ) {
					//预览图片
					$x['cover']             = is_file( $d['path'] . '/cover.jpg' ) ? $d['path'] . '/cover.jpg' : 'resource/images/nopic_small.jpg';
					$x['name']              = $xml['manifest']['application']['name']['@cdata'];
					$x['title']             = $xml['manifest']['application']['title']['@cdata'];
					$x['version']           = $xml['manifest']['application']['version']['@cdata'];
					$x['detail']            = $xml['manifest']['application']['detail']['@cdata'];
					$x['author']            = $xml['manifest']['application']['author']['@cdata'];
					$x['locality']          = ! is_file( 'addons/' . $x['name'] . '/cloud.hd' ) ? 1 : 0;
					$locality[ $x['name'] ] = $x;
				}
			}
		}

		return view()->with( 'locality', $locality );
	}

	//设置新模块
	public function design() {
		$xml = [
			'manifest_code'      => '2.0',
			'title'              => '',
			'name'               => '',
			'version'            => '',
			'industry'           => '',
			'resume'             => '',
			'detail'             => '',
			'author'             => '',
			'url'                => '',
			'setting'            => '',
			'web'                => [
				'entry'  => [
					'title' => '',
					'do'    => ''
				],
				'member' => [
					[
						'title' => '',
						'do'    => ''
					]
				]
			],
			'mobile'             => [
				'home'   => [
					[
						'title' => '',
						'do'    => ''
					]
				],
				'member' => [
					[
						'title' => '',
						'do'    => ''
					]
				]
			],
			'subscribes'         => [
				'text'        => true,
				'image'       => true,
				'voice'       => true,
				'video'       => true,
				'shortvideo'  => true,
				'location'    => true,
				'link'        => true,
				'subscribe'   => true,
				'unsubscribe' => true,
				'scan'        => true,
				'track'       => true,
				'click'       => true,
				'view'        => true
			],
			'processors'         => [
				'text'        => true,
				'image'       => true,
				'voice'       => true,
				'video'       => true,
				'shortvideo'  => true,
				'location'    => true,
				'link'        => true,
				'subscribe'   => true,
				'unsubscribe' => true,
				'scan'        => true,
				'track'       => true,
				'click'       => true,
				'view'        => true
			],
			'cover'              => [
				[
					'title' => '',
					'do'    => ''
				]
			],
			'rule'               => [
				[
					'title' => '',
					'do'    => ''
				]
			],
			'business'           => [
				[
					'title'      => '',
					'controller' => '',
					'action'     => [
						[
							'title' => '',
							'do'    => ''
						]
					]
				]
			],
			'compatible_version' => [
				'version2' => true
			],
			'thumb'              => '',
			'install'            => [ '@cdata' => '' ],
			'upgrade'            => [ '@cdata' => '' ]
		];
//		header( 'Content-Type: application/xml' );
		file_put_contents('package.json',json_encode($xml,true));
//		$xml =  \Xml::toXml( 'manifest', $xml);
//		file_put_contents('package.xml',$xml);
//		echo $xml;exit;
//		echo \Xml::toSimpleXml( $xml );
//		exit;


		if ( IS_POST ) {
			//字段基本检测
			Validate::make( [
				[ 'title', 'required', '模块名称不能为空' ],
				[ 'industry', 'required', '请选择行业类型' ],
				[ 'name', 'regexp:/^[a-z]\w+$/i', '模块标识必须以英文字母开始, 后跟英文,字母,数字或下划线' ],
				[ 'version', 'regexp:/^[\d\.]+$/i', '请设置版本号, 版本号只能为数字或小数点' ],
				[ 'resume', 'required', '模块简述不能为空' ],
				[ 'detail', 'required', '请输入详细介绍' ],
				[ 'author', 'required', '作者不能为空' ],
				[ 'url', 'required', '请输入发布url' ],
				[ 'versionCode', 'required', '请选择兼容版本' ],
				[ 'thumb', 'required', '模块缩略图不能为空' ],
				[ 'cover', 'required', '模块封面图不能为空' ],
			] );
			//模块标识转小写
			$_POST['name'] = strtolower( $_POST['name'] );
			//检查插件是否存在
			if ( is_dir( 'module/' . q( 'post.name' ) ) || is_dir( 'addons/' . q( 'post.name' ) )
			     || Modules::where( 'name', q( 'post.name' ) )->first()
			) {
				message( '模块已经存在,请更改模块标识', 'back', 'error' );
			}

			//创建目录
			if ( ! mkdir( 'addons/' . q( 'post.name' ), 0755, true ) ) {
				message( '模块目录创建失败,请修改addons目录的权限', 'back', 'error' );
			}
			mkdir( 'addons/' . q( 'post.name' ) . '/view' );
			mkdir( 'addons/' . q( 'post.name' ) . '/controller' );
			mkdir( 'addons/' . q( 'post.name' ) . '/model' );
			mkdir( 'addons/' . q( 'post.name' ) . '/service' );
			mkdir( 'addons/' . q( 'post.name' ) . '/api' );

			//模块缩略图
			$info = pathinfo( q( 'post.thumb' ) );
			copy( q( 'post.thumb' ), 'addons/' . q( 'post.name' ) . '/thumb.' . $info['extension'] );

			$_POST['thumb'] = 'thumb.' . strtolower( $info['extension'] );
			//封面图片
			$info = pathinfo( $_POST['cover'] );
			copy( $_POST['cover'], 'addons/' . $_POST['name'] . '/cover.' . $info['extension'] );
			$_POST['cover'] = 'cover.' . strtolower( $info['extension'] );

			$this->siteScript();
			$this->moduleScript();
			$this->createMessageScript();
			$this->createManifestFile();
			message( '模块创建成功', 'prepared', 'success' );
		}

		return view();
	}

	//site.php脚本
	private function siteScript() {
		//创建site.php 业务文件
		$site = '';
		//桌面入口导航
		if ( isset( $_POST['bindings']['web'] ) ) {
			foreach ( $_POST['bindings']['web']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['web']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str

    //{$_POST['bindings']['web']['title'][$k]}
    public function doWeb{$do}() {
        //这个操作被定义用来呈现 桌面入口导航
    }
str;
			}
		}

		//桌面个人中心导航
		if ( isset( $_POST['bindings']['member'] ) ) {
			foreach ( $_POST['bindings']['member']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['member']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str

    //{$_POST['bindings']['member']['title'][$k]}
    public function doWeb{$do}() {
        //这个操作被定义用来呈现 桌面个人中心导航
    }
str;
			}
		}

		//微站首页导航图标
		if ( isset( $_POST['bindings']['home'] ) ) {
			foreach ( $_POST['bindings']['home']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['home']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str
                \n
    //{$_POST['bindings']['home']['title'][$k]}
    public function doWeb{$do}() {
        //这个操作被定义用来呈现 微站首页导航图标
    }
str;
			}
		}

		//微站个人中心导航
		if ( isset( $_POST['bindings']['profile'] ) ) {
			foreach ( $_POST['bindings']['profile']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['profile']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str
                    \n
    //{$_POST['bindings']['profile']['title'][$k]}
    public function doWeb{$do}() {
        //这个操作被定义用来呈现 微站个人中心导航
    }
str;
			}
		}

		//功能封面
		if ( isset( $_POST['bindings']['cover'] ) ) {
			foreach ( $_POST['bindings']['cover']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['cover']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str

    //{$_POST['bindings']['cover']['title'][$k]}
    public function doWeb{$do}() {
        //这个操作被定义用来呈现 功能封面
    }
str;
			}
		}

		//规则列表
		if ( isset( $_POST['bindings']['rule'] ) ) {
			foreach ( $_POST['bindings']['rule']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['rule']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str
                \n
    //{$_POST['bindings']['rule']['title'][$k]}
    public function doSite{$do}() {
        //这个操作被定义用来呈现 规则列表
    }
str;
			}
		}

		//业务功能导航菜单
		if ( isset( $_POST['bindings']['business'] ) ) {
			foreach ( $_POST['bindings']['business']['do'] as $k => $do ) {
				if ( empty( $do ) || empty( $_POST['bindings']['business']['title'] ) ) {
					continue;
				}
				$do = ucfirst( $do );
				$site .= <<<str
                \n
    //{$_POST['bindings']['business']['title'][$k]}
    public function doSite{$do}() {
        //这个操作被定义用来呈现 业务功能导航菜单
    }
str;
			}
		}

		if ( ! empty( $site ) ) {
			$site = <<<str
<?php namespace addons\\{$_POST['name']};
/**
 * {$_POST['title']}模块业务定义
 *
 * @author {$_POST['author']}
 * @url {$_POST['url']}
 */
use module\hdSite;
class Site extends hdSite
{
	$site
}
str;
		}
		if ( ! empty( $site ) ) {
			file_put_contents( 'addons/' . $_POST['name'] . '/site.php', $site );
		}
	}

	//创建module脚本
	private function moduleScript() {
		//模块定义module.php
		if ( isset( $_POST['rule'] ) || isset( $_POST['setting'] ) ) {
			$moduleScript = '';
			if ( isset( $_POST['setting'] ) ) {
				//创建模板文件
				$tplScript = <<<str
<extend file="resource/view/site"/>
<block name="content">
	<div class="panel panel-default">
    	  <div class="panel-heading">
    			<h4 class="panel-title">模块配置</h4>
    	  </div>
    	  <div class="panel-body">
              <form action="" method="post" class="form-horizontal" role="form">
                  <div class="form-group">
                      <label class="col-sm-2 control-label">标题</label>
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="title" value="{{\$field['title']}}">
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-sm-10 col-sm-offset-2">
                          <button type="submit" class="btn btn-primary">保存</button>
                      </div>
                  </div>
              </form>
    	  </div>
    </div>
</block>
str;
				file_put_contents( 'addons/' . $_POST['name'] . '/template/setting.html', $tplScript );
				//php脚本
				$moduleScript .= <<<str
    public function settingsDisplay(\$settings) {
        //点击模块设置时将调用此方法呈现模块设置页面，\$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
        //在此呈现页面中自行处理post请求并保存设置参数（通过使用\$this->saveSettings()来实现）
        if(IS_POST) {
            //字段验证, 并获得正确的数据\$data
            \$this->saveSettings(\$_POST);
            message('更新配置项成功','refresh','success');
        }
        //这里来展示设置项表单
        View::with('field', \$settings);
        return view(\$this->template.'/setting.html');
    }
str;
			}
			if ( isset( $_POST['rule'] ) ) {
				$moduleScript
					.= <<<str

    public function fieldsDisplay(\$rid = 0) {
        //要嵌入规则编辑页的自定义内容，这里 \$rid 为对应的规则编号，新增时为 0
    }

    public function fieldsValidate(\$rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回true表示验证无误，返回其他字符串将呈现为错误提示。这里 \$rid 为对应的规则编号，新增时为 0
        return true;
    }

    public function fieldsSubmit(\$rid) {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 \$rid 为对应的规则编号
    }

    public function ruleDeleted(\$rid) {
        //删除规则时调用，这里 \$rid 为对应的规则编号
    }
str;
			}
			$moduleScript = <<<str
<?php namespace addons\\{$_POST['name']};
/**
 * {$_POST['title']}模块定义
 *
 * @author {$_POST['author']}
 * @url {$_POST['url']}
 */
use module\hdModule;
class Module extends hdModule
{
	$moduleScript
}
str;

			file_put_contents( 'addons/' . $_POST['name'] . '/module.php', $moduleScript );
		}
	}

	//创建消息脚本
	private function createMessageScript() {
		//定阅消息
		if ( ! empty( $_POST['subscribes'] ) ) {
			$php = <<<str
<?php namespace addons\\{$_POST['name']};
/**
 * {$_POST['title']}模块消息订阅器
 *
 * @author {$_POST['author']}
 * @url {$_POST['url']}
 */
use module\hdSubscribe;
class Subscribe extends hdSubscribe
{
	public function handle()
	{
		p(\$this->message());
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看HDCMS文档来编写你的代码
	}
}
str;
			file_put_contents( 'addons/' . $_POST['name'] . '/subscribe.php', $php );
		}
		//直接处理的消息
		if ( ! empty( $_POST['processors'] ) ) {
			$php = <<<str
<?php namespace addons\\{$_POST['name']};
/**
 * {$_POST['title']}模块消息处理器
 *
 * @author {$_POST['author']}
 * @url {$_POST['url']}
 */
use module\hdProcessor;
class Processor extends hdProcessor
{
	public function handle(\$rid)
	{
		p(\$this->message());
		//这里定义此模块进行消息处理的, 消息到达以后的具体处理过程, 请查看HDCMS文档来编写你的代码
	}
}
str;
			file_put_contents( 'addons/' . $_POST['name'] . '/processor.php', $php );
		}
	}

	//创建manifest.xml文件
	private function createManifestFile() {
		//------------------------- 创建xml文件 start
		//模块动作
		$bindings = [ ];
		foreach ( [ 'cover', 'rule', 'business', 'home', 'profile', 'member', 'web' ] as $type ) {
			$d = $_POST['bindings'][ $type ];
			foreach ( $d['title'] as $k => $v ) {
				if ( ! empty( $d['title'][ $k ] ) && preg_match( '/^\w+$/i', $d['do'][ $k ] ) ) {
					$bindings[ $type ]['entry'][] = [
						'@attributes' => [
							'title'    => $d['title'][ $k ],
							'do'       => $d['do'][ $k ],
							'data'     => $d['data'][ $k ],
							'directly' => isset( $d['directly'][ $k ] ) ? $d['directly'][ $k ] : true,
						]
					];
				}
			}
		}
		//消息处理
		$platformXml = [ 'subscribes' => [ ], 'processors' => [ ] ];
		if ( isset( $_POST['subscribes'] ) ) {
			foreach ( $_POST['subscribes'] as $m ) {
				$platformXml['subscribes']['message'][] = [ '@attributes' => [ 'type' => $m ] ];
			}
		}
		if ( isset( $_POST['processors'] ) ) {
			foreach ( $_POST['processors'] as $m ) {
				$platformXml['processors']['message'][] = [ '@attributes' => [ 'type' => $m ] ];
			}
		}
		//创建xml文件
		$xml_data = [
			'@attributes' => [
				'versionCode' => implode( ',', $_POST['versionCode'] ),
			],
			'application' => [
				'@attributes' => [
					'setting' => isset( $_POST['setting'] ) ? true : false
				],
				'name'        => [ '@cdata' => $_POST['name'] ],
				'title'       => [ '@cdata' => $_POST['title'] ],
				'url'         => [ '@cdata' => $_POST['url'] ],
				'version'     => [ '@cdata' => $_POST['version'] ],
				'industry'    => [ '@cdata' => $_POST['industry'] ],
				'resume'      => [ '@cdata' => $_POST['resume'] ],
				'detail'      => [ '@cdata' => $_POST['detail'] ],
				'author'      => [ '@cdata' => $_POST['author'] ],
				'industry'    => [ '@cdata' => $_POST['industry'] ],
				'thumb'       => [ '@cdata' => $_POST['thumb'] ],
				'cover'       => [ '@cdata' => $_POST['cover'] ],
				'rule'        => [ '@attributes' => [ 'embed' => isset( $_POST['rule'] ) ? $_POST['rule'] : false ] ]
			],
			'platform'    => $platformXml,
			'bindings'    => $bindings,
			'permission'  => [ '@cdata' => $_POST['permission'] ],
			'install'     => [ '@cdata' => $_POST['install'] ],
			'uninstall'   => [ '@cdata' => $_POST['uninstall'] ],
			'upgrade'     => [ '@cdata' => $_POST['upgrade'] ],
		];
		$manifest = \Xml::toXml( 'manifest', $xml_data );
		file_put_contents( 'addons/' . $_POST['name'] . '/manifest.xml', $manifest );
	}

	//安装模块
	public function install() {
		//模块安装检测
		if ( $m = $this->module->where( 'name', $_GET['module'] )->first() || is_dir( 'module/' . $_GET['module'] ) ) {
			message( $m['title'] . '模块已经安装或已经存在系统模块, 你可以卸载后重新安装', 'back', 'error' );
		}
		if ( IS_POST ) {
			//获取模块xml数据
			$xmlFile  = 'addons/' . $_POST['module'] . '/manifest.xml';
			$manifest = Xml::toArray( file_get_contents( $xmlFile ) );
			$platform = $manifest['manifest']['platform'];
			//添加数据
			$installSql = trim( $manifest['manifest']['install']['@cdata'] );
			if ( ! empty( $installSql ) ) {
				if ( preg_match( '/.php$/', $installSql ) ) {
					$file = 'addons/' . $_POST['module'] . '/' . $installSql;
					if ( ! is_file( $file ) ) {
						message( '安装文件:' . $file . " 不存在", 'back', 'error' );
					}
					require $file;
				} else {
					\Schema::sql( $installSql );
				}
			}
			//不需要对用户关键词进行响应的消息类型
			$subscribes = [ ];
			if ( ! empty( $platform['subscribes'] ) ) {
				//只选择一个类型
				if ( isset( $platform['subscribes']['message']['@value'] ) ) {
					$subscribes[] = $platform['subscribes']['message']['@attributes']['type'];
				} else {
					foreach ( $platform['subscribes']['message'] as $m ) {
						$subscribes[] = $m['@attributes']['type'];
					}
				}
			}
			//需要模块处理的微信关键词消息类型
			$processors = [ ];
			if ( ! empty( $platform['processors'] ) ) {
				//只选择一个类型
				if ( isset( $platform['processors']['message']['@value'] ) ) {
					$subscribes[] = $platform['processors']['message']['@attributes']['type'];
				} else {
					foreach ( $platform['processors']['message'] as $m ) {
						$processors[] = $m['@attributes']['type'];
					}
				}
			}
			//整合添加到模块表中的数据
			$moduleData             = [
				'name'        => $manifest['manifest']['application']['name']['@cdata'],
				'version'     => $manifest['manifest']['application']['version']['@cdata'],
				'industry'    => $manifest['manifest']['application']['industry']['@cdata'],
				'title'       => $manifest['manifest']['application']['title']['@cdata'],
				'url'         => $manifest['manifest']['application']['url']['@cdata'],
				'resume'      => $manifest['manifest']['application']['resume']['@cdata'],
				'detail'      => $manifest['manifest']['application']['detail']['@cdata'],
				'author'      => $manifest['manifest']['application']['author']['@cdata'],
				'rule'        => $manifest['manifest']['application']['rule']['@attributes']['embed'] == 'true' ? 1 : 0,
				'thumb'       => $manifest['manifest']['application']['thumb']['@cdata'],
				'cover'       => $manifest['manifest']['application']['cover']['@cdata'],
				'is_system'   => 0,
				'subscribes'  => serialize( $subscribes ),
				'processors'  => serialize( $processors ),
				'setting'     => $manifest['manifest']['application']['@attributes']['setting'] == 'true' ? 1 : 0,
				'permissions' => serialize( preg_split( '/\n/', $manifest['manifest']['permission']['@cdata'] ) ),
			];
			$moduleData['locality'] = ! is_file( 'addons/' . $_POST['module'] . '/cloud.hd' ) ? 1 : 0;
			Db::table( 'modules' )->insertGetId( $moduleData );
			//添加模块动作表数据
			if ( ! empty( $manifest['manifest']['bindings'] ) ) {
				foreach ( $manifest['manifest']['bindings'] as $entry => $bind ) {
					foreach ( $bind as $b ) {
						if ( isset( $b['@value'] ) ) {
							//只有一个元素
							$data = [
								'module'   => $manifest['manifest']['application']['name']['@cdata'],
								'entry'    => $entry,
								'title'    => $b['@attributes']['title'],
								'do'       => $b['@attributes']['do'],
								'data'     => $b['@attributes']['data'],
								'directly' => $b['@attributes']['directly'] ? 1 : 0,
							];
							Db::table( 'modules_bindings' )->insert( $data );
						} else {
							foreach ( $b as $m ) {
								$data = [
									'module'   => $manifest['manifest']['application']['name']['@cdata'],
									'entry'    => $entry,
									'title'    => $m['@attributes']['title'],
									'do'       => $m['@attributes']['do'],
									'data'     => $m['@attributes']['data'],
									'directly' => $m['@attributes']['directly'] ? 1 : 0,
								];
								Db::table( 'modules_bindings' )->insert( $data );
							}
						}

					}
				}
			}

			//在服务套餐中添加模块
			if ( ! empty( $_POST['package'] ) ) {
				$package = Db::table( 'package' )->whereIn( 'name', $_POST['package'] )->get();
				foreach ( $package as $p ) {
					$p['modules'] = unserialize( $p['modules'] );
					if ( empty( $p['modules'] ) ) {
						$p['modules'] = [ ];
					}
					$p['modules'][] = $_POST['module'];
					$p['modules']   = serialize( array_unique( $p['modules'] ) );
					Db::table( 'package' )->where( 'name', $p['name'] )->update( $p );
				}
			}
			service( 'site' )->updateAllCache();
			message( "模块安装成功", u( 'installed' ) );
		}
		$xmlFile = 'addons/' . $_GET['module'] . '/manifest.xml';
		if ( is_file( $xmlFile ) ) {
			//本地应用
			$manifest = Xml::toArray( file_get_contents( $xmlFile ) );
		} else {
			//下载模块目录
			go( u( 'download', [ 'module' => $_GET['module'] ] ) );
		}
		$package = Db::table( 'package' )->get();

		return view()->with( 'module', $manifest['manifest'] )->with( 'package', $package );
	}

	//下载远程模块
	public function download() {
		if ( IS_POST ) {
			$module = q( 'get.module' );
			$app    = Curl::get( c( 'api.cloud' ) . '?a=site/GetLastAppInfo&t=web&siteid=1&m=store&type=addons&module=' . $module );
			$app    = json_decode( $app, true );
			if ( $app ) {
				$package = Curl::post( c( 'api.cloud' ) . '?a=site/download&t=web&siteid=1&m=store&type=addons', [ 'file' => $app['data']['package'] ] );
				file_put_contents( 'tmp.zip', $package );
				//释放压缩包
				Zip::PclZip( 'tmp.zip' );//设置压缩文件名
				Zip::extract( "." );//解压缩
				file_put_contents( 'addons/' . $module . '/cloud.hd', json_encode( $app['data'], JSON_UNESCAPED_UNICODE ) );
				message( '模块下载成功,准备安装', '', 'success' );
			}
			message( '应用商店不存在模块', '', 'error' );
		}
		View::make();
	}

	/**
	 * 从远程应用模块缓存中获取模块
	 *
	 * @param $module
	 *
	 * @return mixed
	 */
	protected function getCacheModuleManifest( $module ) {
		$apps = d( 'cloudModules' );
		foreach ( $apps as $a ) {
			if ( $a['manifest']['application']['name']['@cdata'] == $module ) {
				return $a;
			}
		}
	}

	//卸载模块
	public function uninstall() {
		if ( ! isset( $_GET['confirm'] ) ) {
			confirm( '卸载模块时同时删除规则数据吗, 删除规则数据将同时删除相关规则的统计分析数据？', u( 'uninstall', [
				'confirm' => 1,
				'module'  => $_GET['module']
			] ), u( 'uninstall', [ 'confirm' => 0, 'module' => $_GET['module'] ] ) );
		}
		if ( ! $this->module->remove( $_GET['module'], $_GET['confirm'] ) ) {
			message( $this->module->getError(), 'back', 'error' );
		}
		message( '模块卸载成功', u( 'installed' ) );
	}

}