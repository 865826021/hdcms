<?php namespace module\article\system;

use module\article\model\WebCategory;

/**
 * 模块模板视图自定义标签处理
 * @author 向军
 * @url http://www.hdcms.com
 */
class Tag {
	//文章列表
	public function lists( $attr, $content ) {
		$row       = isset( $attr['row'] ) ? intval( $attr['row'] ) : 10;
		$mid       = isset( $attr['mid'] ) ? intval( $attr['mid'] ) : 0;
		$cid       = isset( $attr['cid'] ) ? $attr['cid'] : "";
		$iscommend = isset( $attr['iscommend'] ) ? $attr['iscommend'] : 0;
		$ishot     = isset( $attr['ishot'] ) ? $attr['ishot'] : 0;
		$titlelen  = isset( $attr['titlelen'] ) ? intval( $attr['titlelen'] ) : 20;
		$order     = isset( $attr['order'] ) ? $attr['order'] : 'new';
		$php
		           = <<<str
		<?php
		\$model = new module\article\model\WebContent($mid);
		\$db = \$model->where('siteid',SITEID)->limit($row);
		//栏目检索
		\$cid = array_filter(explode(',','$cid'));
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
			case 'old':
				\$db->orderBy('aid','ASC');
				break;
		}
		\$_result = \$db->get();
		\$_result =\$_result?\$_result->toArray():[]; 
		foreach(\$_result as \$field){
			\$field['category']=module\article\model\WebCategory::getByCid(\$field['cid']);
			\$field['url'] = Link::get(\$field['_category']['html_content'],\$field);
			\$field['title'] = mb_substr(\$field['title'],0,$titlelen,'utf8');
		?>
			$content
		<?php }?>
str;

		return $php;
	}

	//相关文章
	public function relation( $attr, $content ) {
		$row      = isset( $attr['row'] ) ? intval( $attr['row'] ) : 10;
		$titlelen = isset( $attr['titlelen'] ) ? intval( $attr['titlelen'] ) : 20;
		$php
		          = <<<str
		<?php \$db = Db::table('web_article')->where('siteid',SITEID)->limit($row);
		//栏目检索
		\$db->where('category_cid',\$_GET['cid']);
		//排序
		\$db->orderBy('rand()');
		\$_result = \$db->get()?:[];
		foreach(\$_result as \$field){
			\$field['url'] = url('entry/content',['aid'=>\$field['aid'],'cid'=>\$field['category_cid']],'article');
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
        <?php \$slideData = Db::table('web_slide')->where('siteid',SITEID)->orderBy('id','DESC')->get()?:[];?>
        <?php if(!empty(\$slideData)){?>
<div class="hdcms_swiper_container">
        <div class="swiper-wrapper">
            <?php foreach(\$slideData as \$_s){?>
            <div class="swiper-slide">
                <img src="<?php echo \$_s['thumb'];?>">
                <div class="title"><?php echo \$_s['title'];?></div>
            </div>
            <?php }?>
        </div>
        <!-- 如果需要分页器 -->
        <div class="hdcms_swiper_pagination"></div>
    </div>
    <style>
        .hdcms_swiper_container {
            width      : 100%;
            height     : {$height}px;
            background : #aaa;
            overflow:hidden;
        }
        .hdcms_swiper_container .swiper-container-horizontal > .swiper-pagination-bullets {
            bottom : 17px;
        }
        .hdcms_swiper_container .swiper-slide img {
            width : 100%;
        }
        .hdcms_swiper_container .swiper-slide div.title {
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
                var mySwiper = new Swiper('.hdcms_swiper_container', {
                    width: {$width},
                    height: {$height},
                    autoplay: {$autoplay},
                    direction: 'horizontal',
                    loop: true,
                    //如果需要分页器
                    pagination: '.hdcms_swiper_pagination',
                })
            })
        })
    </script>
    <?php }?>
str;

		return $php;
	}

	//微站首页导航菜单
	public function navigate( $attr, $content ) {
		$position = isset( $attr['position'] ) ? $attr['position'] : 1;
		$php
		          = <<<str
        <?php
	        \$sql  ="SELECT * FROM ".tablename('navigate').' WHERE siteid=? AND position=? AND status=1';
	        \$nav = Db::query(\$sql,[SITEID,$position]);
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
	public function category_menu() {
		if ( $cid = Request::get( 'cid' ) ) {
			$__ROOT__ = __ROOT__;
			$php
			          = <<<str
		<link rel="stylesheet" href="{$__ROOT__}/module/article/system/tag/css/category_menu.css">
		<script src="{{__ROOT__}}/module/article/system/tag/js/category_menu.js"></script>
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
                 \$categoryData = Db::table('web_category')->where('siteid',SITEID)->where('status',1)->get()?:[];
                 \$categoryData = \Arr::channelLevel(\$categoryData,0,'','cid','pid');
                 foreach(\$categoryData as \$d){
                        \$d['url']=Link::get(\$d['html_category'],\$d);
                        \$d['url']=str_replace('{page}',Request::get('page',1), \$d['url']);
                        echo "<dt><a href='{\$d['url']}'>{\$d['catname']}</a></dt>";
                        if(!empty(\$d['_data'])){
                            echo '<dd>';
                            foreach(\$d['_data'] as \$_m){
                                \$_m['url']=Link::get(\$_m['html_category'],\$_m);
                                \$_m['url']=str_replace('{page}',Request::get('page',1), \$_m['url']);
                                echo "<a href='{\$_m['url']}'>{\$_m['catname']}</a>";
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
\$_category =\$db->get()?:[];
foreach(\$_category as \$field){
    //栏目链接
    \$field['url']=Link::get(\$field['html_category'],\$field);
    \$field['url']=str_replace('{page}',Request::get('page',1),\$field['url']);
    \$field['active']=isset(\$_GET['cid']) && \$_GET['cid']==\$field['cid']?true:false;
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
		$php       = <<<str
        <?php
        \$db = new module\article\model\WebContent();
        p(Request::get('cid'));
        \$db->where('cid',Request::get('cid'))->where('siteid',SITEID);
        //头条
        if($ishot){
            \$db->where('ishot',1);
        }
        if($iscommend){
            \$db->where('iscommend',1);
        }
        //栏目数据
        \$_category = Db::table('web_category')->where('cid',Request::get('cid'))->where('siteid',SITEID)->first();
        //栏目链接
        \$_category['url']=Link::get(\$d['html_category'],\$_category);
        \$_category['url']=str_replace('{page}',Request::get('page',1), \$d['url']);
        //分页地址设置
        houdunwang\page\Page::url(Link::get(\$d['html_category'],\$_category));
        \$_data = \$db->paginate($row);
        
        foreach(\$_data as \$field){
            \$field['category']=\$_category;
            //文章缩略图
            if(!empty(\$field['thumb'])){
                \$field['thumb']=__ROOT__."/{\$field['thumb']}";
            }
            //文章链接
            \$field['url'] = Link::get(\$_category['html_content'],\$field);
        ?>
        $content
        <?php }?>
str;

		return $php;

	}

	//页码
	public function pagination() {
		$php = <<<str
        <?php echo \$db->links();?>
str;
		return $php;
	}

	//底部快捷导航
	public function quickmenu( $attr ) {
		$php = <<<str
	<link rel="stylesheet" href="resource/css/quickmenu.css">
	<script src="resource/js/quickmenu.js"></script>
    <style>
        .quickmenu {
            position: fixed;
        }
        .quickmenu .normal dl dd {
            display: none;
        }
    </style>
        <?php
            \$res = Db::table('page')->where(siteid,SITEID)->where('type','quickmenu')->first();
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