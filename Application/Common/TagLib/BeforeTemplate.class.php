<?php
namespace Common\TagLib;
use Think\Template\TagLib;


class BeforeTemplate extends TagLib {

    protected $tags = array(

        'builder'    => array('attr' => 'name,enname', 'close' => 1), // name和enname不能为空，随便填写
        'page_init'  => array('attr' => 'name,title,loadjs', 'close' => 1),
        
    );


    /**
     * include文件，无法使用变量，所以此处的变量都是无意义的
     */
    public function _builder($tag, $content) {
        $name          = $tag['name'];
        $enname        = $tag['enname'] ? : "";

        // dump($buildermodule);
        $buildermodule = ucfirst(C('PK_BUILDER_MODULE'));

        if($buildermodule != ""){

            if(strstr("Plugins://",$buildermodule)){
                $module_name = str_replace("Plugins://", "", $buildermodule);
            }else{
                if($buildermodule == "Common"){
                    $module_name = ""; // 兼容以前的
                }else{
                    $module_name = $buildermodule;
                }
            }

            $component_alias = D($buildermodule."/Form",'Builder')->getComponentAliasData();
            // dump($component_alias);
            foreach ($component_alias as $key => $value) {
                $component_alias[$key] = str_replace("@", "/", $value);
                $component_alias[$key] = str_replace("~", "/", $component_alias[$key]);
            }
        }else{
            $component_alias = array();
        }


        $parse = "";
        $dirs = common_local_components_local_file();
        foreach ($dirs as $key => $component_name) {
            if(isset($component_alias[$component_name])){
                $component_dir = $component_alias[$component_name];
            }else{
                $component_dir = $component_name;
            }
            $parse .= "<include file='./static/components/form_builder/".$component_dir."/".$component_name.".html' />";
            // dump($parse);
        }
        return $parse;
    }

    public function _page_init($tag, $content){
        $name        = $tag['name'];
        $title       = $tag['title'];
        $loadjs      = $tag['loadjs'];
        $loadvue     = $tag['loadvue'];


        $parse .= '<title>'.$title.'</title>';
        $parse .= '<div class="jt_page_change"';
        $parse .= '    data-top-tab-name = "{:C(\'ADMIN_TOP_MENU_NAME\')}"';
        $parse .= '    data-cur-url    = "<php>echo strtolower(U());</php>"';
        $parse .= '    data-module     = "<php>echo strtolower(MODULE_NAME);</php>"';
        $parse .= '    data-controller = "<php>echo strtolower(CONTROLLER_NAME);</php>"';
        $parse .= '    data-action     = "<php>echo strtolower(ACTION_NAME);</php>"';
        $parse .= '    data-load-js    = "'.$loadjs.'"';
        $parse .= '    data-load-vue   = "'.$loadvue.'"';
        $parse .= '    data-is-init    = "false"';
        $parse .= '></div>';
        $parse .= $content;
        return $parse;
    }

}
