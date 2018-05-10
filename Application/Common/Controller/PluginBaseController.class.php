<?php
namespace Common\Controller;
use Common\Controller\CommonBaseController;
/**
 * 插件基类
 */
abstract class PluginBaseController extends CommonBaseController {
    /**
     * 必须实现安装方法
     */
    abstract public function install();

    /**
     * 必须实现卸载方法
     */
    abstract public function uninstall();

}
