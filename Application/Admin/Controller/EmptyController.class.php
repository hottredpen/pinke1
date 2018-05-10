<?php
/**
 * 其他模块或插件的admin通过此模块整合过来
 * 例如：admin/AdminTest/index
 * 
 */
namespace Admin\Controller;
use Common\Controller\CommonBaseController;

class EmptyController extends CommonBaseController{

    protected function _initialize() {
        parent::_initialize();
    }

    public function _empty($action_name,$args){
        if(C('PK_PLUGIN_NAME')){
            $file = APP_PATH . "Plugins" . '/' . C('PK_PLUGIN_NAME') . '/Controller/' .C('PK_PLUGIN_NAME'). 'Controller.class.php';
            if(!file_exists($file)){
                $this->pk_error('不存在插件的后台衍射文件'.$file);
            }
            require_once($file);
            $controller = A('Plugins://'.C('PK_PLUGIN_NAME').'/'.C('PK_PLUGIN_NAME'));
            $action     = ACTION_NAME;
        }else{
            $file = APP_PATH . C('PK_MODULE_NAME') . '/' . 'Controller' . '/' .C('PK_MODULE_NAME'). 'Controller.class.php';
            if(!file_exists($file)){
                $this->pk_error('不存在'.C('PK_MODULE_NAME').'模块的后台衍射文件'.$file);
            }
            require_once($file);
            $controller = A('Admin/'.C('PK_MODULE_NAME') );
            $action     = ACTION_NAME;
        }

        try{


            if (method_exists($controller, $action_name)) {
                $method =   new \ReflectionMethod($controller, $action_name);
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
                // $method =   new \ReflectionMethod($controller,"_empty");
                $controller->_empty($action_name,$args);
            }
        }catch (\ReflectionException $e){

            $this->error('empty模块错误');

        }

    }

}