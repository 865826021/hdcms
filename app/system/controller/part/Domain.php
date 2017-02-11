<?php namespace app\system\controller\part;
/**
 * 模块域名设置
 * Class Domain
 * @package app\system\controller\part
 */
class Domain {
	public static function make( $data ) {
		if ( $data['domain'] ) {
			self::php($data);
			self::html($data);
		}
	}

	protected static function php( $data ) {
		$tpl = <<<php
<?php namespace addons\\{$data['name']}\\system;

use module\HdDomain;

/**
 * 模块域名设置
 * Class Domain
 */
class Domain extends HdDomain {

	public function set() {
		if ( IS_POST ) {
			\$this->save();
			message( '域名保存成功', 'back', 'success' );
		}
		View::with( 'domain', \$this->get() );

		return view( \$this->template . '/domain.html' );
	}
}
php;
		file_put_contents( "addons/{$data['name']}/system/Domain.php", $tpl );

	}

	protected static function html( $data ) {
		$tpl = <<<html
<extend file="resource/view/site"/>
<block name="content">
    <form action="" method="post" class="form-horizontal">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">模块域名设置</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">域名</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="domain" value="{{\$domain}}">
                        <span class="help-block">请保证域名已经解析到服务器, 不需要添加http协议前缀</span>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary" type="submit">保存数据</button>
    </form>
</block>
html;
		file_put_contents( "addons/{$data['name']}/system/template/domain.html", $tpl );
	}
}