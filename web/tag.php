<?php namespace web;

use Hdphp\View\TagBase;

class tag extends TagBase
{
    /**
     * 标签声明
     * @var array
     */
    public $tags = array(
        //导航菜单
        'mobile_nav'        => [ 'block' => true, 'level' => 4 ],
        'mobile_quick_menu' => [ 'block' => false, 'level' => 4 ],
        'pagelist'          => [ 'block' => true, 'level' => 4 ],
        'pagenum'           => [ 'block' => false, 'level' => 4 ],
        'mobile_header'     => [ 'block' => true, 'level' => 4 ],
        'slide'             => [ 'block' => false, 'level' => 4 ],
        'category'          => [ 'block' => true, 'level' => 4 ],
    );

    //栏目列表
    public function _category($attr, $content)
    {
        $php = <<<str
<?php
\$_category = Db::table('web_category')->field('title cat_name,description cat_description,cid,pid,icon,css,siteid')->whereIn('cid',explode(',',"{$attr['cid']}"))->get();
foreach(\$_category as \$field){
    //栏目链接
    \$field['cat_url']=empty(\$field['cat_linkurl'])?__ROOT__."/index.php?s=article/entry/category&siteid={\$field['siteid']}&cid={\$field['cid']}":\$field['cat_linkurl'];
    if(!empty(\$field['icon'])){
                //有图标 2
                \$field['icon']='<i class="icon" style="background:url(\''.\$field['icon'].'\') no-repeat;background-size:cover;"></i>';
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

    //幻灯图
    public function _slide($attr, $content)
    {
        $color    = isset($attr['color']) ? $attr['color'] : '#FFFFFF';
        $width    = isset($attr['width']) ? $attr['width'] : 'window.innerWidth';
        $height   = isset($attr['height']) ? $attr['height'] : 200;
        $autoplay = isset($attr['autoplay']) ? $attr['autoplay'] : 3000;
        $php      = <<<str
        <?php \$slideData = Db::table('web_slide')->where('web_id','=',Session::get('webid'))->orderBy('id','DESC')->get();?>
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
            height     : 150px;
            background : #aaa;
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

    //头部导航条(手机)
    public function _mobile_header($attr, $content)
    {
        if ($cid = q('get.cid', 0, 'intval'))
        {
            $php = <<<str
        <div class="mobile_header">
        <a class="back_url" href="<?php echo \$_SERVER['HTTP_REFERER'];?>">
            <i class="fa fa-chevron-left"></i>
        </a>
        <div class="mobile_header_content"><?php echo isset(\$hdcms['cat_name']) ? \$hdcms['cat_name'] : \$hdcms['title'] ?></div>
        <div class="cat_menu_list">
            <i class="fa fa-bars ng-scope"></i>
        </div>
        <div class="child_menu_list clearfix">
            <dl>
                <?php
                \$categoryData = Db::table('web_category')->where('siteid','=',q('get.siteid'))->where('status','=',1)->get();
                 \$categoryData = Data::channelLevel(\$categoryData,0,'','cid','pid');
                 foreach(\$categoryData as \$d){
                        \$d['url']=empty(\$d['linkurl'])?__ROOT__."/index.php?s=article/entry/category&siteid={\$d['siteid']}&cid={\$d['cid']}":\$d['linkurl'];
                        echo "<dt><a href='{\$d['url']}'>{\$d['title']}</a></dt>";
                        if(!empty(\$d['_data'])){
                            echo '<dd>';
                            foreach(\$d['_data'] as \$_m){
                                \$_m['url']=empty(\$_m['linkurl'])?__ROOT__."/index.php?s=article/entry/category&siteid={\$_m['siteid']}&cid={\$_m['cid']}":\$_m['linkurl'];
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

    //微站导航菜单
    public function _mobile_nav($attr, $content)
    {
        $position = isset($attr['position']) ? $attr['position'] : 1;
        $php      = <<<str
        <?php
        \$sql  ="SELECT * FROM ".tablename('web_nav').' WHERE siteid=? AND web_id=? AND position=? AND status=1';
        \$nav = Db::select(\$sql,array(q('get.siteid',0,'intval'),q('get.webid',0,'intval'),$position));
        foreach(\$nav as \$field){
            if(!empty(\$field['icon'])){
                //有图标 2
                \$field['icon']='<i class="icon" style="background:url(\''.\$field['icon'].'\') no-repeat;background-size:cover;"></i>';
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

    //快捷导航
    public function _mobile_quick_menu($attr, $content)
    {
        $php = <<<str
        <?php
            \$res = Db::table('web_page')->where(siteid,q('get.i',0,'intval'))->where('web_id',api('web')->getWebId())->first();
            if(\$res){
                \$params = json_decode(\$res['params'],true);
                if(MODULE =='article' && \$params['has_article']==true){
                    //文章&分类
                    echo '<div style="height:60px;"></div>'.\$res['html'];
                }
                if(MODULE=='uc' && \$params['has_ucenter']==true){
                    //会员中心主页
                    echo '<div style="height:60px;"></div>'.\$res['html'];
                }
            }
        ?>
str;
        return $php;
    }

    //栏目列表数据
    public function _pagelist($attr, $content)
    {
        $row       = isset($attr['row']) ? $attr['row'] : 10;
        $ishot     = isset($attr['ishot']) ? 1 : 0;
        $iscommend = isset($attr['iscommend']) ? 1 : 0;
        $php       = <<<str
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
    public function _pagenum($attr, $content)
    {
        $php = <<<str
        <?php echo \$page_show;?>
str;
        return $php;
    }
}