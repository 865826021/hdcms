<?php namespace app\component\controller;

/**
 * 模块与模板
 * Class Module
 * @package app\component\controller
 */
class Module {
	//模块列表
	public function moduleBrowser() {
		\User::loginAuth();
		View::with( 'modules', v( 'site.modules' ) );
		View::with( 'useModules', explode( ',', q( 'get.mid', '', [ ] ) ) );

		return view();
	}

	//模块列表
	public function moduleList() {
		\User::loginAuth();
		$modules = Db::table( 'modules' )->get();

		return view()->with( 'modules', $modules );
	}

	/**
	 * 模块与模板列表
	 * 添加站点时选择扩展模块时使用
	 * @return mixed
	 */
	public function ajaxModulesTemplate() {
		\User::loginAuth();
		$modules   = Db::table( 'modules' )->where( 'is_system', 0 )->get();
		$templates = Db::table( 'template' )->where( 'is_system', 0 )->get();

		return view()->with( [
			'modules'   => $modules,
			'templates' => $templates
		] );
	}

	//选择站点模板模板
	public function siteTemplateBrowser() {
		\User::loginAuth();
		$data = \Template::getSiteAllTemplate();

		return view()->with( 'data', $data );
	}
}