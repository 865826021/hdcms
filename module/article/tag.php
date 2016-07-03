<?php
namespace module\article;
class tag {
	//获取文章
	public function lists( $attr, $content ) {
		$row       = isset( $attr['row'] ) ? intval( $attr['row'] ) : 10;
		$cid       = isset( $attr['cid'] ) ? ($attr['cid'][0]=='$'?$attr['cid']:"'{$attr['cid']}'") : "''";
		$iscommend = isset( $attr['iscommend'] ) ? 1 : 0;
		$ishot     = isset( $attr['ishot'] ) ? 1 : 0;
		$titlelen  = isset( $attr['titlelen'] ) ? intval( $attr['titlelen'] ) : 20;
		$order     = isset( $attr['order'] ) ? $attr['order'] : 'new';
		$php
		           = <<<str
		<?php \$db = Db::table('web_article')->where('siteid',SITEID)->limit($row);
		//栏目检索
		\$cid = array_filter(explode(',',$cid));
		if(!empty(\$cid)){
			\$db->whereIn('category_cid',\$cid);
		}
		//推荐文章
		if($iscommend){
			\$db->where('iscommend',1);
		}
		//头条文章
		if($ishot){
			\$db->where('ishot',1);
		}
		//排序
		\$order = "$order";
		switch(\$order){
			case 'new':
				\$db->orderBy('aid','DESC');
				break;
		}
		\$_result = \$db->get();
		foreach(\$_result as \$field){
			\$field['url'] = __ROOT__.'/?a=entry/content&m=article&aid='.\$field['aid'].'&cid='.\$field['category_cid'].'&t=web&siteid='.SITEID;
			\$field['title'] = mb_substr(\$field['title'],0,$titlelen,'utf8');
		?>
			$content
		<?php }?>
str;

		return $php;
	}

	//幻灯图
	public function slide( $attr ) {
		$color    = isset( $attr['color'] ) ? $attr['color'] : '#FFFFFF';
		$width    = isset( $attr['width'] ) ? $attr['width'] : 'window.innerWidth';
		$height   = isset( $attr['height'] ) ? $attr['height'] : 200;
		$autoplay = isset( $attr['autoplay'] ) ? $attr['autoplay'] : 3000;
		$php
		          = <<<str
        <?php \$slideData = Db::table('web_slide')->where('web_id',Session::get('webid'))->where('siteid',SITEID)->orderBy('id','DESC')->get();?>
        <?php if(!empty(\$slideData)){?>
<div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach(\$slideData as \$_s){?>
            <div class="swiper-slide">
                <img src="<?php echo \$_s['thumb'];?>">
                <div class="title"><?php echo \$_s['title'];?></div>
            </div>
            <?php }?>
        </div>
        <!-- 如果需要分页器 -->
        <div class="swiper-pagination"></div>
    </div>
    <style>
        .swiper-container {
            width      : 100%;
            height     : {$height}px;
            background : #aaa;
            overflow:hidden;
        }
        .swiper-container-horizontal > .swiper-pagination-bullets {
            bottom : 17px;
        }
        .swiper-container .swiper-slide img {
            width : 100%;
        }
        .swiper-container .swiper-slide div.title {
            position   : absolute;
            bottom     : 0px;
            text-align : center;
            width      : 100%;
            font-size  : 1em;
            color      : {$color};
        }
    </style>
    <script>
        $(function () {
            require(['swiper'], function ($) {
                var mySwiper = new Swiper('.swiper-container', {
                    width: {$width},
                    height: {$height},
                    autoplay: {$autoplay},
                    direction: 'horizontal',
                    loop: true,
                    //如果需要分页器
                    pagination: '.swiper-pagination',
                })
            })
        })
    </script>
    <?php }?>
str;

		return $php;
	}

	//微站导航菜单
	public function mobile_nav( $attr, $content ) {
		$position = isset( $attr['position'] ) ? $attr['position'] : 1;
		$php
		          = <<<str
        <?php
	        \$sql  ="SELECT * FROM ".tablename('web_nav').' WHERE siteid=? AND web_id=? AND position=? AND status=1';
	        \$nav = Db::query(\$sql,[SITEID,q('get.webid',0,'intval'),$position]);
	        foreach((array)\$nav as \$field){
	            \$css = json_decode(\$field['css'],true);
	            if(\$field['icontype']==1){
	                //字体图标
	                \$field['icon']='<i class="'.\$css['icon'].'" style="color:'.\$css['color'].';font-size:'.\$css['size'].'px;"></i>';
	            }else{
	                //图片图标
	                \$field['icon']='<i class="icon" style="background:url(\''.\$css['image'].'\') no-repeat;background-size:cover;"></i>';
	            }
	        ?>
	        $content
        <?php }?>
str;

		return $php;
	}


	//头部导航条(手机)
	public function mobile_header() {
		if ( $cid = q( 'get.cid', 0, 'intval' ) ) {
			$php
				= <<<str
        <div class="mobile_header">
        <a class="back_url" href="javascript:history.back();">
            <i class="fa fa-chevron-left"></i>
        </a>
        <div class="mobile_header_content" style="overflow:hidden"><?php echo isset(\$hdcms['cat_name']) ? \$hdcms['cat_name'] : \$hdcms['title'] ?></div>
        <div class="cat_menu_list">
            <i class="fa fa-bars ng-scope"></i>
        </div>
        <div class="child_menu_list clearfix">
            <dl>
                <?php
                 \$categoryData = Db::table('web_category')->where('siteid',SITEID)->where('status',1)->get();
                 \$categoryData = Data::channelLevel(\$categoryData,0,'','cid','pid');
                 foreach(\$categoryData as \$d){
                        \$d['url']=empty(\$d['linkurl'])?__ROOT__."/index.php?a=entry/category&m=article&t=web&siteid={\$d['siteid']}&cid={\$d['cid']}":\$d['linkurl'];
                        echo "<dt><a href='{\$d['url']}'>{\$d['title']}</a></dt>";
                        if(!empty(\$d['_data'])){
                            echo '<dd>';
                            foreach(\$d['_data'] as \$_m){
                                \$_m['url']=empty(\$_m['linkurl'])?__ROOT__."/in11dex.php?a=entry/category&m=article&t=web&siteid={\$_m['siteid']}&cid={\$_m['cid']}":\$_m['linkurl'];
                                echo "<a href='{\$_m['url']}'>{\$_m['title']}</a>";
                            }
                            echo '</dd>';
                        }
                 }
                ?>
            </dl>
        </div>
    </div>
    <div class="head-bg-gray"></div>
str;

			return $php;
		}

	}

	//栏目列表
	public function category( $attr, $content ) {
		$cid = isset( $attr['cid'] ) ? $attr['cid'] : '';
		$php
		     = <<<str
<?php
\$cid = array_filter(explode(',','$cid'));
\$db =  Db::table('web_category')->where('siteid',SITEID);
if(\$cid){
	\$db->whereIn('cid',\$cid);
}
\$_category =\$db->get();
foreach(\$_category as \$field){
    //栏目链接
    \$field['url']=empty(\$field['cat_linkurl'])?__ROOT__."/index.php?a=entry/category&t=web&m=article&siteid={\$field['siteid']}&cid={\$field['cid']}":\$field['cat_linkurl'];
    \$css = json_decode(\$field['css']);
    if(!empty(\$field['icon'])){
                //有图标 2
                \$field['icon']='<i class="icon" style="background:url(\''.\$css['icon'].'\') no-repeat;background-size:cover;"></i>';
            }else{
                \$css = unserialize(\$field['css']);
                \$field['icon']='<i class="'.\$css['icon'].'" style="color:'.\$css['color'].';font-size:'.\$css['size'].'px;"></i>';
            }
?>
$content
<?php }?>
str;

		return $php;
	}

	//栏目列表数据
	public function pagelist( $attr, $content ) {
		$row       = isset( $attr['row'] ) ? $attr['row'] : 10;
		$ishot     = isset( $attr['ishot'] ) ? 1 : 0;
		$iscommend = isset( $attr['iscommend'] ) ? 1 : 0;
		$php
		           = <<<str
        <?php
        \$db = Db::table('web_article')->where('category_cid',q('get.cid',0,'intval'))->where('siteid',SITEID);
        //头条
        if($ishot){
            \$db->where('ishot',1);
        }
        if($iscommend){
            \$db->where('iscommend',1);
        }
        \$count = \$db->count();
        unset(\$_GET['s']);
        \$page_show = Page::pageNum(8)->row($row)->make(\$count);
        \$db = Db::table('web_article')->where('category_cid',q('get.cid',0,'intval'))->where('siteid',SITEID);
        //头条
        if($ishot){
            \$db->where('ishot','=',1);
        }
        if($iscommend){
            \$db->where('iscommend','=',1);
        }
        \$_data=\$db->limit(Page::limit())->get();
        //栏目数据
        \$_category = Db::table('web_category')->where('cid',q('get.cid',0,'intval'))->where('siteid',SITEID)->first();
        //栏目链接
        \$_category['url']=empty(\$_category['cat_linkurl'])?__ROOT__."/index.php?a=/entry/category&m=article&t=web&siteid={\$_category['siteid']}&cid={\$_category['cid']}":\$_category['cat_linkurl'];
            //栏目图标
            \$css = unserialize(\$_category['css']);
            if(\$_category['icontype']==1){
                //字体图标
                \$_category['icon']='<i class="'.\$css['icon'].'" style="color:'.\$css['color'].';font-size:'.\$css['size'].'px;"></i>';
            }else{
				//图片图标
                \$_category['icon']='<i class="icon" style="background:url(\''.\$css['image'].'\') no-repeat;background-size:cover;"></i>';
            }
        foreach(\$_data as \$field){
            \$field['category']=\$_category;
            //文章缩略图
            if(!empty(\$field['thumb'])){
                \$field['thumb']=__ROOT__."/{\$field['thumb']}";
            }
            //文章链接
            \$field['url']=empty(\$field['linkurl'])?__ROOT__."/index.php?a=/entry/content&m=article&t=web&siteid={\$_category['siteid']}&cid={\$_category['cid']}&aid={\$field['aid']}":\$field['linkurl'];
        ?>
        $content
        <?php }?>
str;

		return $php;

	}

	//页码
	public function pagenum() {
		$php
			= <<<str
        <?php echo isset(\$page_show)?\$page_show:'';?>
str;

		return $php;
	}
}