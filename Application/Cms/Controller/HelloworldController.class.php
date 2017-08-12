<?php 
namespace Cms\Controller;
/**
 * 第一次使用产品时的提示，C('CMS_PROJECT_NAME')不存在时，给出的提示
 */
class HelloworldController extends CmsBaseController {

    protected function _initialize() {
        parent::_initialize();
    }

    public function index($name){

        if($name){
            echo "欢迎使用<b>pinkePHP单页面快速开发框架</b>,你还未安装前台主题".$name.",请到后台进行安装";
        }else{
            echo "欢迎使用<b>pinkePHP单页面快速开发框架</b>,cms前台主题是".C('CMS_PROJECT_NAME')."，但该主题文件未安装，请到后台插件管理中安装本主题";
        }
    }

}