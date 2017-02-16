<?php namespace app\system\controller;

use system\model\Package;
use system\model\Template as TemplateModel;

/**
 * 文章模板管理
 * Class Template
 * @package web\system\controller
 * @author 向军
 */
class Template {
	public function __construct() {
		\User::superUserAuth();
	}

	//设置新模板
	public function design() {
		if ( IS_POST ) {
			$data = json_decode( Request::post('data'), JSON_UNESCAPED_UNICODE );
			//字段基本检测
			Validate::make( [
				[ 'title', 'required', '模板名称不能为空' ],
				[ 'industry', 'required', '请选择行业类型' ],
				[ 'name', 'regexp:/^[a-z]\w+$/i', '模板标识必须以英文字母开始, 后跟英文,字母,数字或下划线' ],
				[ 'version', 'regexp:/^[\d\.]+$/i', '请设置版本号, 版本号只能为数字或小数点' ],
				[ 'resume', 'required', '模板简述不能为空' ],
				[ 'author', 'required', '作者不能为空' ],
				[ 'url', 'required', '请输入发布url' ],
				[ 'thumb', 'required', '模板缩略图不能为空' ],
				[ 'position', 'regexp:/^\d+$/', '微站导航菜单数量必须为数字' ],
			], $data );
			//模板标识转小写
			$data['name'] = strtolower( $data['name'] );
			//模板缩略图
			if ( ! is_file( $data['thumb'] ) ) {
				message( '缩略图文件不存在', 'back', 'error' );
			}
			//检查插件是否存在
			if ( is_dir( 'theme/' . $data['name'] ) || Db::table( 'template' )->where( 'name', $data['name'] )->first() ) {
				message( '模板已经存在,请更改模板标识', 'back', 'error' );
			}
			foreach ( [ 'web/css', 'mobile/css' ] as $dir ) {
				if ( ! \Dir::create( "theme/{$data['name']}/{$dir}" ) ) {
					message( '模板目录创建失败,请修改目录权限', 'back', 'error' );
				}
			}
			//缩略图处理
			$info = pathinfo( $data['thumb'] );
			copy( $data['thumb'], 'theme/' . $data['name'] . '/thumb.' . $info['extension'] );
			$data['thumb'] = 'thumb.' . $info['extension'];
			file_put_contents( "theme/{$data['name']}/package.json", json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
			message( '模板创建成功', 'prepared', 'success' );
		}

		return view();
	}

	//已经安装模板
	public function installed() {
		$template = Db::table( 'template' )->get();
		foreach ( $template as $k => $m ) {
			$template[ $k ]['thumb']    = is_file( "theme/{$m['name']}/{$m['thumb']}" ) ? "theme/{$m['name']}/{$m['thumb']}" : "resource/images/nopic_small.jpg";
			$template[ $k ]['locality'] = ! is_file( "theme/{$m['name']}/cloud.hd" ) ? 1 : 0;
		}

		return view()->with( 'template', $template );
	}

	//生成压缩包
	public function createZip() {
		$name = Request::get( 'name' );
		//更改当前目录
		chdir( 'theme' );
		//设置压缩文件名
		\Zip::PclZip( $name . ".zip" );
		//压缩目录
		\Zip::create( "{$name}" );
		\File::download( $name . ".zip", $name . '.zip' );
		@unlink( $name . ".zip" );
	}

	//安装本地模板列表
	public function prepared() {
		$templates = TemplateModel::lists( 'name' );
		//本地模板
		$locality = [ ];
		foreach ( \Dir::tree( 'theme' ) as $d ) {
			if ( $d['type'] == 'dir' && is_file( $d['path'] . '/package.json' ) ) {
				if ( $config = json_decode( file_get_contents( "{$d['path']}/package.json" ), true ) ) {
					//去除已经安装的模板
					if ( ! in_array( $config['name'], $templates ) ) {
						//预览图片
						$config['thumb']             = "theme/{$config['name']}/{$config['thumb']}";
						$locality[ $config['name'] ] = $config;
					}
				}
			}
		}

		return view()->with( 'locality', $locality );
	}

	//安装本地模板
	public function install() {
		//模板安装检测
		if ( $m = TemplateModel::where( 'name', Request::get( 'name' ) )->first() ) {
			message( $m['title'] . '模板已经安装', 'back', 'error' );
		}
		$configFile = 'theme/' . Request::get( 'name' ) . '/package.json';
		if ( ! is_file( $configFile ) ) {
			message( '配置文件不存在,无法安装', '', 'error' );
		}
		$config = json_decode( file_get_contents( $configFile ), true );
		if ( ! $config ) {
			message( '模板配置文件解析失败', 'back', 'error' );
		}
		if ( IS_POST ) {
			//整合添加到模板表中的数据
			$config['is_system']  = 0;
			$config['is_default'] = 0;
			$config['locality']   = 1;
			$model                = new TemplateModel();
			$model->save( $config );
			//在服务套餐中添加模板
			if ( ! empty( $_POST['package'] ) ) {
				$package = Package::whereIn( 'name', $_POST['package'] )->get();
				foreach ( $package as $p ) {
					$template      = json_decode( $p['template'], true ) ?: [ ];
					$template[]    = $config['name'];
					$p['template'] = $template;
					$p->save();
				}
			}
			message( "模板安装成功", u( 'installed' ) );
		}

		return view()->with( 'template', $config )->with( 'package', Package::get() );
	}

	//卸载模板
	public function uninstall() {
		if ( ! \Template::remove( $_GET['name'], $_GET['confirm'] ) ) {
			message( Template::getError(), '', 'error' );
		}

		message( '模板卸载成功', u( 'installed' ) );
	}
}