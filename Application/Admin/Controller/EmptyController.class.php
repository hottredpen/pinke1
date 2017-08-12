<?php
// +----------------------------------------------------------------------
// | 品客PHP框架 [ pinkePHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 浙江蓝酷网络科技有限公司 [ http://www.lankuwangluo.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.pinkephp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\CommonBaseController;

class EmptyController extends CommonBaseController{

    public function _empty($name,$args){
        header("Content-Type: text/html; charset=utf-8");
        // 插件是否存在$name的插件名
        $has_plugin = M('admin_plugin')->where(array('name'=>CONTROLLER_NAME))->find();
        if($has_plugin){
            $plugin_name = CONTROLLER_NAME;
            define("PK_PLUGIN_NAME", $plugin_name);
            $file = APP_PATH . "Plugins" . '/' . $plugin_name . '/Controller/' .$plugin_name. 'Controller.class.php';
            if(!file_exists($file)){
                $this->cpk_error('不存在插件的后台衍射文件'.$file);
            }
            require_once($file);
            $controller = A( 'Plugins://'.$plugin_name.'/'.$plugin_name);
            $action     = ACTION_NAME;
        }else{
            $file = APP_PATH . CONTROLLER_NAME . '/' . 'Controller' . '/' .CONTROLLER_NAME. 'Controller.class.php';
            if(!file_exists($file)){
                $this->cpk_error('不存在'.CONTROLLER_NAME.'模块。如果是插件或后台模块管理，则不存在后台衍射文件'.$file);
            }
            // 加入 衍射入口，同时将方法伪装成Admin下的操作
            require_once($file);
            $controller = A( 'Admin/'.CONTROLLER_NAME);
            $action     = ACTION_NAME;
        }

        try{
            if (method_exists($controller, $name)) {
                $method =   new \ReflectionMethod($controller, $name);
                // URL参数绑定检测
                if($method->getNumberOfParameters()>0 && C('URL_PARAMS_BIND')){
                    switch($_SERVER['REQUEST_METHOD']) {
                        case 'POST':
                            $vars    =  array_merge($_GET,$_POST);
                            break;
                        case 'PUT':
                            parse_str(file_get_contents('php://input'), $vars);
                            break;
                        default:
                            $vars  =  $_GET;
                    }
                    $params =  $method->getParameters();

                    $paramsBindType     =   C('URL_PARAMS_BIND_TYPE');
                    foreach ($params as $param){
                        $name = $param->getName();
                        if( 1 == $paramsBindType && !empty($vars) ){
                            $args[] =   array_shift($vars);
                        }elseif( 0 == $paramsBindType && isset($vars[$name])){
                            $args[] =   $vars[$name];
                        }elseif($param->isDefaultValueAvailable()){
                            $args[] =   $param->getDefaultValue();
                        }else{
                            // E(L('_PARAM_ERROR_'));
                        }
                    }
                    // 开启绑定参数过滤机制
                    if(C('URL_PARAMS_SAFE')){
                        array_walk_recursive($args,'filter_exp');
                        $filters     =   C('URL_PARAMS_FILTER')?:C('DEFAULT_FILTER');
                        if($filters) {
                            $filters    =   explode(',',$filters);
                            foreach($filters as $filter){
                                $args   =   array_map_recursive($filter,$args); // 参数过滤
                            }
                        }
                    }
                    $method->invokeArgs($controller,$args);
                }else{
                    $method->invoke($controller);
                }            
            }else{
                C('CPK_FROM_MODULE_ADMIN',1); // 来自模块的admin
                $method =   new \ReflectionMethod($controller,"_empty");
                $controller->_empty($name,$args);
            }
        }catch (\ReflectionException $e){

            $this->error('empty模块错误');

        }

    }

}