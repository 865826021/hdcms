<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace system\controller;

/**
 * 模块管理
 * Class Module
 * @package core\controller
 * @author 向军
 */
class Module {
	public function __construct() {
		api( "user" )->isLogin();
	}

	//已经安装模块
	public function installed() {
		$modules = Db::table( 'modules' )->get();
		foreach ( $modules as $k => $m ) {
			if ( $m['is_system'] ) {
				//系统模块
				$modules[ $k ]['type']  = 1;
				$modules[ $k ]['cover'] = "web/module/{$m['name']}/cover.jpg";
			} else {
				//本地模块
				$modules[ $k ]['type']  = 2;
				$modules[ $k ]['cover'] = is_file( "addons/{$m['name']}/cover.jpg" ) ? "addons/{$m['name']}/cover.jpg" : "resource/images/nopic-small.jpg";
			}
		}
		View::with( 'modules', $modules )->make();
	}

	//安装新模块列表
	public function prepared() {
		$modules  = Db::table( 'modules' )->lists( 'name' );
		$dirs     = Dir::tree( 'addons' );
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
					$locality[ $x['name'] ] = $x;
				}
			}
		}
		View::with( 'locality', $locality )->make();
	}

	//设置新模块
	public function design() {
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
			] );
			//验证失败
			if ( Validate::fail() ) {
				message( Validate::getError(), 'back', 'error' );
			};
			//图片类型检测,只允许 jpg格式的图片
			if ( $_FILES['thumbnail']['error'] != 0 || $_FILES['thumbnail']['type'] !== 'image/jpeg' ) {
				message( '模块缩略图格式错误,图片文件过大或类型不是jpg格式', 'back', 'error' );
			}
			if ( $_FILES['cover_plan']['error'] != 0 || $_FILES['cover_plan']['type'] !== 'image/jpeg' ) {
				message( '模块封面格式错误,图片文件过大或类型不是jpg格式', 'back', 'error' );
			}
			p( $_POST );
			exit;
			//模块名转小写
			$_POST['name'] = strtolower( $_POST['name'] );
			//检查插件是否存在
			if ( is_dir( 'addons/' . $_POST['name'] ) || Db::table( 'modules' )->where( 'name', $_POST['name'] )->first() ) {
				message( '模块已经存在,请更改模块标识', 'back', 'error' );
			}
			if ( ! mkdir( 'addons/' . $_POST['name'], 0755, TRUE ) ) {
				message( '模块目录创建失败,请修改addons目录的权限', 'back', 'error' );
			}
			mkdir( 'addons/' . $_POST['name'] . '/template', 0755, TRUE );
			$this->siteScript();
			$this->moduleScript();
			$this->createMessageScript();
			$this->createManifestFile();
			//模块缩略图
			$file = 'addons/' . $_POST['name'] . '/thumb.jpg';
			move_uploaded_file( $_FILES['thumbnail']['tmp_name'], $file );
			//模块封面图
			$file = 'addons/' . $_POST['name'] . '/cover.jpg';
			move_uploaded_file( $_FILES['cover_plan']['tmp_name'], $file );
			message( '模块创建成功', 'prepared', 'success' );
		} else {
			View::make();
		}
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
				$site
					.= <<<str

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
				$site
					.= <<<str

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
				$site
					.= <<<str
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
				$site
					.= <<<str
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
				$site
					.= <<<str

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
				$site
					.= <<<str
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
				$site
					.= <<<str
                \n
    //{$_POST['bindings']['business']['title'][$k]}
    public function doSite{$do}() {
        //这个操作被定义用来呈现 业务功能导航菜单
    }
str;
			}
		}

		if ( ! empty( $site ) ) {
			$site
				= <<<str
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
				$tplScript
					= <<<str
<extend file="web/resource/view/site"/>
<block name="content">
这里定义页面内容
</block>
str;
				file_put_contents( 'addons/' . $_POST['name'] . '/template/setting.html', $tplScript );
				//php脚本
				$moduleScript
					.= <<<str

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
        View::make(\$this->template.'/setting.html');
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
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 \$rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsSubmit(\$rid) {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 \$rid 为对应的规则编号
    }

    public function ruleDeleted(\$rid) {
        //删除规则时调用，这里 \$rid 为对应的规则编号
    }
str;
			}
			$moduleScript
				= <<<str
<?php namespace addons\\{$_POST['name']};
/**
 * {$_POST['title']}模块定义
 *
 * @author {$_POST['author']}
 * @url {$_POST['url']}
 */
use module\HdModule;
class Module extends HdModule
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
			$php
				= <<<str
<?php namespace addons\\{$_POST['name']};
/**
 * {$_POST['title']}模块消息订阅器
 *
 * @author {$_POST['author']}
 * @url {$_POST['url']}
 */
use module\\{$_POST['name']}Subscribe;
class Subscribe extends hdSubscribe
{
	public function handle()
	{
		p(\$this->message);
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看HDCMS文档来编写你的代码
	}
}
str;
			file_put_contents( 'addons/' . $_POST['name'] . '/subscribe.php', $php );
		}
		//直接处理的消息
		if ( ! empty( $_POST['processors'] ) ) {
			$php
				= <<<str
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
		p(\$this->message);
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
							'directly' => isset( $d['directly'][ $k ] ) ? $d['directly'][ $k ] : TRUE,
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
					'setting' => isset( $_POST['setting'] ) ? TRUE : FALSE
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
				'rule'        => [ '@attributes' => [ 'embed' => isset( $_POST['rule'] ) ? $_POST['rule'] : FALSE ] ]
			],
			'platform'    => $platformXml,
			'bindings'    => $bindings,
			'permission'  => [ '@cdata' => $_POST['permission'] ],
			'install'     => [ '@cdata' => $_POST['install'] ],
			'uninstall'   => [ '@cdata' => $_POST['uninstall'] ],
			'upgrade'     => [ '@cdata' => $_POST['upgrade'] ],
		];
		$manifest = Xml::toXml( 'manifest', $xml_data );
		file_put_contents( 'addons/' . $_POST['name'] . '/manifest.xml', $manifest );
	}

	//安装模块
	public function install() {
		//模块安装检测
		if ( $m = Db::table( 'modules' )->where( 'name', $_GET['module'] )->first() ) {
			message( $m['title'] . '模块已经安装, 你可以卸载后重新安装', 'back', 'error' );
		}
		if ( IS_POST ) {
			//获取模块xml数据
			$manifest = Xml::toArray( file_get_contents( 'addons/' . $_POST['module'] . '/manifest.xml' ) );

			$platform = $manifest['manifest']['platform'];
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
			$moduleData = [
				'name'        => $manifest['manifest']['application']['name']['@cdata'],
				'version'     => $manifest['manifest']['application']['version']['@cdata'],
				'industry'    => $manifest['manifest']['application']['industry']['@cdata'],
				'title'       => $manifest['manifest']['application']['title']['@cdata'],
				'url'         => $manifest['manifest']['application']['url']['@cdata'],
				'resume'      => $manifest['manifest']['application']['resume']['@cdata'],
				'detail'      => $manifest['manifest']['application']['detail']['@cdata'],
				'author'      => $manifest['manifest']['application']['author']['@cdata'],
				'rule'        => $manifest['manifest']['application']['rule']['@attributes']['embed'] ? 1 : 0,
				'is_system'   => 0,
				'subscribes'  => serialize( $subscribes ),
				'processors'  => serialize( $processors ),
				'setting'     => $manifest['manifest']['application']['@attributes']['setting'] ? 1 : 0,
				'permissions' => serialize( preg_split( '/\n/', $manifest['manifest']['permission']['@cdata'] ) )
			];
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
			message( "模块安装成功", u( 'installed' ) );
		}


		$manifest = Xml::toArray( file_get_contents( 'addons/' . $_GET['module'] . '/manifest.xml' ) );
		$package  = Db::table( 'package' )->get();
		View::with( 'module', $manifest['manifest'] )->with( 'package', $package )->make();
	}

	//卸载模块
	public function uninstall() {
		$module = Db::table( 'modules' )->where( 'name', $_GET['module'] )->first();
		if ( $module['rule'] && ! isset( $_GET['confirm'] ) ) {
			confirm( '卸载模块时同时删除规则数据吗, 删除规则数据将同时删除相关规则的统计分析数据？', u( 'uninstall', [
				'confirm' => 1,
				'module'  => $_GET['module']
			] ), u( 'uninstall', [ 'confirm' => 0, 'module' => $_GET['module'] ] ) );
		}
		//删除封面关键词数据
		if ( q( 'get.confirm' ) == 1 ) {
			//删除模块封面数据
			if ( $coverRids = Db::table( 'reply_cover' )->where( 'module', $_GET['module'] )->lists( 'rid' ) ) {
				Db::table( 'rule' )->whereIn( 'rid', $coverRids )->delete();
				Db::table( 'rule_keyword' )->whereIn( 'rid', $coverRids )->delete();
				Db::table( 'reply_cover' )->where( 'module', $_GET['module'] )->delete();
			}
			//删除模块回复规则列表
			Db::table( 'rule' )->where( 'module', '=', $_GET['module'] )->delete();
			Db::table( 'rule_keyword' )->where( 'module', '=', $_GET['module'] )->delete();
		}
		//删除模块数据
		Db::table( 'modules' )->where( 'name', $_GET['module'] )->delete();
		//删除模块动作数据
		Db::table( 'modules_bindings' )->where( 'module', $_GET['module'] )->delete();
		//更新套餐数据
		$package = Db::table( 'package' )->get();
		foreach ( $package as $p ) {
			$p['modules'] = unserialize( $p['modules'] );
			if ( $k = array_search( $_GET['module'], $p['modules'] ) ) {
				unset( $p['modules'][ $k ] );
			}
			$p['modules'] = serialize( $p['modules'] );
			Db::table( 'package' )->where( 'id', $p['id'] )->update( $p );
		}
		//更新站点缓存
		$siteids = Db::table( 'site' )->lists( 'siteid' );
		foreach ( $siteids as $siteid ) {
			System::updateSiteCache( $siteid );
		}
		message( '模块卸载成功', u( 'installed' ) );
	}

}