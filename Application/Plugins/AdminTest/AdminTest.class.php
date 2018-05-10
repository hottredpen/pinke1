<?php
namespace Plugins\AdminTest;
use Common\Controller\PluginBaseController;

class AdminTest extends PluginBaseController{
    /**
     * @var array 插件信息
     */
    public $info = array(
        // 插件名[必填]
        'name'        => 'AdminTest',
        // 插件标题[必填]
        'title'       => 'admin自动化测试',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'admintest.hottredpen.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-globe',
        // 插件描述[选填]
        'description' => 'admin自动化测试,完成admin的一些测试',
        // 插件作者[必填]
        'author'      => '直观层',
        // 作者主页[选填]
        'author_url'  => 'http://www.jk-kj.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0',
        // 是否有后台管理功能
        'admin'       => '1',
    );

    public $database_prefix = 'xrs_';

    /**
     * @var array 插件钩子
     */
    public $hooks = array(
        // 钩子名称 => 钩子说明
        'page_tips'          => '系统－页面钩子',

    );
    public function page_tips(){

    }
    /**
     * 安装方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     */
    public function install(){
        $res = true;
        if($res){
            return true;
        }else{
            $this->error = "安装过程失败";
            return false;
        }
    }

    /**
     * 卸载方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     */
    public function uninstall(){
        return true;
    }
}
