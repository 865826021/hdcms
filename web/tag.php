<?php namespace web;

use Hdphp\View\TagBase;

class tag extends TagBase {
	/**
	 * 标签声明
	 * @var array
	 */
	public $tags
		= [
			'line' => [ 'block' => FALSE, 'level' => 4 ],
			'tag'  => [ 'block' => TRUE, 'level' => 4 ],
		];

	public function _line( $attr ) {
		static $instance = [ ];
		$info   = explode( '.', $attr['action'] );
		$action = $info[1];
		if ( $module = Db::table( 'modules' )->where( 'name', $info[0] )->first() ) {
			$class = ( $module['is_system'] == 1 ? 'module' : 'addons' ) . '\\' . $info[0] . '\tag';
			if ( ! isset( $instance[ $class ] ) ) {
				$instance[ $class ] = new $class;
			}

			return $instance[ $class ]->$action( $attr);
		}
	}

	public function _tag( $attr, $content ) {
		static $instance = [ ];
		$info   = explode( '.', $attr['action'] );
		$action = $info[1];
		if ( $module = Db::table( 'modules' )->where( 'name', $info[0] )->first() ) {
			$class = ( $module['is_system'] == 1 ? 'module' : 'addons' ) . '\\' . $info[0] . '\tag';
			if ( ! isset( $instance[ $class ] ) ) {
				$instance[ $class ] = new $class;
			}

			return $instance[ $class ]->$action( $attr, $content );
		}
	}

	//栏目列表数据
	public function _pagelist( $attr, $content ) {
		$row       = isset( $attr['row'] ) ? $attr['row'] : 10;
		$ishot     = isset( $attr['ishot'] ) ? 1 : 0;
		$iscommend = isset( $attr['iscommend'] ) ? 1 : 0;
		$php
		           = <<<str
        <?php
        \$db = Db::table('web_article')->where('category_cid','=',q('get.cid',0,'intval'))->where('siteid','=',q('get.siteid',0,'intval'));
        //头条
        if($ishot){
            \$db->where('ishot','=',1);
        }
        if($iscommend){
            \$db->where('iscommend','=',1);
        }
        \$count = \$db->count();
        \$page_show = Page::pageNum(8)->row($row)->make(\$count);
        //字段列表
        \$field_name='c.siteid,c.cid,c.title catname,c.description cat_description,c.css,c.linkurl cat_linkurl,c.icon cat_icon,a.aid,a.category_cid,a.iscommend
        ,a.ishot,a.title,a.description,a.source,a.author,a.orderby,a.linkurl,a.createtime,a.click,a.thumb';
        \$db = Db::table('web_article a')->join('web_category c','a.category_cid','=','c.cid')->field(\$field_name)
        ->where('category_cid','=',q('get.cid',0,'intval'))->where('a.siteid','=',q('get.siteid',0,'intval'));
        //头条
        if($ishot){
            \$db->where('ishot','=',1);
        }
        if($iscommend){
            \$db->where('iscommend','=',1);
        }
        \$cat_data=\$db->limit(Page::limit())->get();
        foreach(\$cat_data as \$field){
            //文章缩略图
            if(!empty(\$field['thumb'])){
                //有图标 2
                \$field['thumb']=__ROOT__."/{\$field['thumb']}";
            }
            //文章链接
            \$field['url']=empty(\$field['linkurl'])?__ROOT__."/index.php?s=article/entry/content&siteid={\$field['siteid']}&cid={\$field['cid']}&aid={\$field['aid']}":\$field['linkurl'];
            //栏目链接
            \$field['cat_url']=empty(\$field['cat_linkurl'])?__ROOT__."/index.php?s=article/entry/category&siteid={\$field['siteid']}&cid={\$field['cid']}":\$field['cat_linkurl'];
            //栏目图标
            if(!empty(\$field['cat_icon'])){
                //有图标 2
                \$field['cat_icon']='<i class="icon" style="background:url(\''.\$field['cat_icon'].'\') no-repeat;background-size:cover;"></i>';
            }else{
                \$css = unserialize(\$field['css']);
                \$field['cat_icon']='<i class="'.\$css['icon'].'" style="color:'.\$css['color'].';font-size:'.\$css['size'].'px;"></i>';
            }
        ?>
        $content
        <?php }?>
str;

		return $php;

	}

	//页码
	public function _pagenum( $attr, $content ) {
		$php
			= <<<str
        <?php echo \$page_show;?>
str;

		return $php;
	}
}