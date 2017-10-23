<?php
namespace Cms\TagLib;
use Think\Template\TagLib;


class Cms extends TagLib {

    protected $tags = array(

        'block_info'           => array('attr' => 'name,enname', 'close' => 1),
        'block_list'           => array('attr' => 'name,enname,p,page_size,order', 'close' => 1),
        'page_seo'             => array('attr' => 'name,title,keywords,description', 'close' => 1),
        'category_list'        => array('attr' => 'name,enname', 'close' => 1),
        'article_info'         => array('attr' => 'name,enname,id', 'close' => 1),
        'article_onepage_info' => array('attr' => 'name,enname', 'close' => 1),
        'article_list'         => array('attr' => 'name,enname,p,page_size,order,key,search_code', 'close' => 1),
        'article_list_pager'   => array('attr' => 'name,enname,page_size', 'close' => 1),
        
    );


    /**
     * 文章列表
     */
    public function _article_list($tag, $content) {
        $name        = $tag['name'];
        $enname      = $tag['enname'] ? : "";
        $search_code = $tag['search_code'] ? : "";
        $p           = $tag['p'] ? : 1;
        $page_size   = $tag['page_size'] ? : 10;
        $key         = $tag['key']? : "k";

        switch ($search_code) {
            case 'recommend':
                $searchmap = "array('d.recommend_side'=>1)";
                break;
            default:
                $searchmap = "array()";
                break;
        }
        $parse  = '<?php ';
        $parse .= '$__ARTICLE_LIST__ = D("Cms/CmsDocument","Datamanager")->getDocumentData_enname('.$enname.','.$p.','.$page_size.','.$searchmap.');';
        $parse .= ' ?>';

        $parse .= '<php>if(count($__ARTICLE_LIST__) == 0){ echo "<div class=\"col-md-12  text-center\">暂无数据</div>";} </php>';
        $parse .= '<php> $'.$key.' = 0; </php>';
        $parse .= '<php> foreach( $__ARTICLE_LIST__  as $'.$name.'){ </php>';
        $parse .= '<php> $'.$key.' ++; </php>';
        $parse .= $content;
        $parse .= '<php>}</php>';

        return $parse;
    }
    public function _article_list_pager($tag,$content){
        $name        = $tag['name'];
        $enname      = $tag['enname'] ? : "";
        $search_code = $tag['search_code'] ? : "";
        $p           = $tag['p'] ? : 1;
        $page_size   = $tag['page_size'] ? : 10;
        $key         = $tag['key']? : "k";

        switch ($search_code) {
            case 'recommend':
                $searchmap = "array('d.recommend_side'=>1)";
                break;
            default:
                $searchmap = "array()";
                break;
        }
        $parse  = '<?php ';
        $parse .= '$__ARTICLE_LIST_NUM__ = D("Cms/CmsDocument","Datamanager")->getDocumentNum_enname('.$enname.','.$searchmap.');';
        $parse .= ' ?>';
        $parse .= '<php>
                        $pager_obj = new \Common\Util\Page($__ARTICLE_LIST_NUM__, '.$page_size.');
                        echo $pager_obj->show();// 分页显示输出
                   </php>';
        $parse .= $content;
        return $parse;
    }
    /**
     * 文章详情页
     */
    public function _article_info($tag, $content) {
        $name   = $tag['name'];
        $enname = $tag['enname'] ? : "";
        $id     = $tag['id'] ?  : 0;
        $parse  = '<?php ';
        $parse .= '$'.$name.' = D("Cms/CmsDocument","Datamanager")->getDocumentInfoData_enname_id('.$enname.','.$id.');';
        $parse .= ' ?>';
        $parse .= $content;
        return $parse;
    }

    /**
     * 单块内容
     */
    public function _article_onepage_info($tag, $content){
        $name      = $tag['name'];
        $enname    = $tag['enname'] ? : "";

        $parse  = '<?php ';
        $parse .= '$'.$name.' = D("Cms/CmsDocument","Datamanager")->getDocumentInfoDataOnePage_enname('.$enname.');';
        $parse .= ' ?>';
        $parse .= $content;

        return $parse;
    }
    /**
     * 获取enname下面的子分类
     * 如果enname=main则获取pid=0的分类
     */
    public function _category_list($tag, $content){
        $name      = $tag['name'];
        $enname    = $tag['enname'] ? : "";
        $key       = $tag['key']? : "k";

        $parse  = '<php> $__CATEGORY_LIST__ = D("Cms/CmsCategory","Datamanager")->getSubDataByEnname("'.$enname.'");</php>';
        $parse .= '<php> $'.$key.' = 0; </php>';
        $parse .= '<php> foreach( $__CATEGORY_LIST__  as $'.$name.'){ </php>';
        $parse .= '<php> $'.$key.' ++; </php>';
        $parse .= $content;
        $parse .= '<php>}</php>';
        return $parse;
    }

    /**
     * 如果是首页或者列表也不带有文章内部seo的，直接使用
     *  <cms:page_seo name="page_seo"></cms:page_seo>
     * 如果是详情页或者想seo配置中需要参与变量的，使用
     *  <cms:page_seo name="page_seo" 
            title       = "$info['title']" 
            keywords    = "$info['keywords']" 
            description = "$info['description']" 
        >
        </cms:page_seo>
     */
    public function _page_seo($tag, $content){
        $name        = $tag['name'];
        $title       = $tag['title'];
        $keywords    = $tag['keywords'];
        $description = $tag['description'];

        if($title != ''){
            $parse .= ' <php>
                            $page_seo = seo_init(array(
                                    "info_title"       => '.$title.',
                                    "info_keywords"    => '.$keywords.',
                                    "info_description" => '.$description.'
                            ),1);
                        </php>';
        }else{
            $parse  = ' <php>
                            $page_seo = seo_init();
                        </php>';
        }
        $parse .= '<title><php>echo $page_seo["title"]; </php></title>';
        $parse .= $content;
        return $parse;
    }

    /**
     * 相对独立的页面碎片内容(类似于单页内容)
     * 例子
     * <cms:block_info name="index_about_info" enname="index_about">
     *      ...
     * </cms:block_info>
     */
    public function _block_info($tag, $content){
        $name    = $tag['name'];
        $enname  = $tag['enname'] ? : "";

        $parse   = '<?php ';
        $parse  .= '$'.$name.' = D("Cms/CmsBlock","Datamanager")->getOneInfoByCategoryName('.$enname.');';
        $parse  .= ' ?>';
        $parse  .= $content;
        return $parse;
    }

    /**
     * 具有列表性质的页面碎片内容（类似于列表页内容）
     * 例子
     * <cms:block_list name="vo" enname="index_service" p="1" page_size="10">
     *     ...
     * </cms:block_list>
     */
    public function _block_list($tag, $content){
        $name        = $tag['name'];
        $enname      = $tag['enname'] ? : "";
        $search_code = $tag['search_code'] ? : "";
        $p           = $tag['p'] ? : 1;
        $page_size   = $tag['page_size'] ? : 10;
        $key         = $tag['key']? : "k";

        switch ($search_code) {
            // 目前无此需求
            default:
                $searchmap = "array()";
                break;
        }

        $parse   = '<?php ';
        $parse  .= '$__BLACK_LIST__ = D("Cms/CmsBlock","Datamanager")->getDataListByCategoryName('.$enname.','.$p.','.$page_size.','.$searchmap.');';
        $parse  .= ' ?>';

        $parse .= '<php> $'.$key.' = 0; </php>';
        $parse .= '<php> foreach( $__BLACK_LIST__  as $'.$name.'){ </php>';
        $parse .= '<php> $'.$key.' ++; </php>';
        $parse .= $content;
        $parse .= '<php>}</php>';
        return $parse;
    }

}
