<?php
namespace module\uc;
class tag {
	/**
	 * @param array $attr 属性
	 * @param string $content 内容
	 *
	 * @return mixed
	 */
	public function news( $attr, $content ) {
		//代码...
	}

	//快捷导航
	public function quick_menu( $attr ) {
		$php
			= <<<str
        <?php
            \$res = Db::table('web_page')->where(siteid,SITEID)->where('type',1)->first();
            if(\$res){
                \$params = json_decode(\$res['params'],true);
                if(v('module.name')=='uc' && \$params['has_ucenter']==true){
                    //会员中心主页
                    echo '<div style="height:60px;"></div>'.\$res['html'];
                }
            }
        ?>
str;

		return $php;
	}
}