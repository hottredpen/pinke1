<?php
namespace Common\Controller;
use Common\Controller\CommonBaseController;
/**
 * 插件基类
 */
abstract class ModuleBaseController extends CommonBaseController {

    public $config_file = '';
    public $plugin_path = '';
    /**
     * 构造方法
     */
    public function __construct(){
        $this->plugin_path = C('plugin_path').$this->getName().'/';
        if (is_file($this->plugin_path.'config.php')) {
            $this->config_file = $this->plugin_path.'config.php';
        }
    }

    final public function getName(){
        $class = get_class($this);
        return substr($class, strrpos($class, '\\') + 1);
    }
    /**
     * 必须实现安装方法
     */
    abstract public function install();

    /**
     * 必须实现卸载方法
     */
    abstract public function uninstall();

}
