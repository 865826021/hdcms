<?php
namespace module\uc;
class tag {
	//快捷导航
	public function quick_menu( $attr ) {
		$php
			= <<<str
        <?php
            \$res = Db::table('web_page')->where(siteid,SITEID)->where('type',1)->first();
            if(\$res){
                \$params = json_decode(\$res['params'],true);
                if(empty(\$params['modules'])){
					echo '<div style="height:60px;"></div>'.\$res['html'];
                }else{
	                foreach(\$params['modules'] as \$v){
	                    if(\$v['mid']==v('module.mid')){
	                        echo '<div style="height:60px;"></div>'.\$res['html'];
	                        break;
	                    }
	                }
                }
            }
        ?>
str;

		return $php;
	}
}