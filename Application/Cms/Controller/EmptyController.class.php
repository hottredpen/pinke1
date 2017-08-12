<?php 
namespace Cms\Controller;
/**
 * 如果action有其他数据需要添加，请在_before_action内添加数据
 */
class EmptyController extends CmsBaseController {

    protected function _initialize() {
        parent::_initialize();
    }

    public function _empty($name){

        header("Content-Type: text/html; charset=utf-8");

        $plugin_name = "CmsProject".C('CMS_PROJECT_NAME'); // "CmsProject".CMS_PROJECT;

        // 插件是否存在$name的插件名
        $has_plugin = M('admin_plugin')->where(array('name'=>$plugin_name))->find();

        if($has_plugin){
            define("PK_PLUGIN_NAME", $plugin_name);
            $file = APP_PATH . "Plugins" . '/' . $plugin_name . '/Controller/' .$plugin_name. 'Controller.class.php';
            if(!file_exists($file)){
                exit('不存在cms的主题衍射文件'.$file);
            }
            require_once($file);
            $controller = A( 'Plugins://'.$plugin_name.'/'.CONTROLLER_NAME);
            if(!$controller){
            	$controller = A( 'Plugins://'.$plugin_name.'/Empty');
            }
            if(!$controller){
                $controller = A('Cms/helloworld');
                $controller->index($has_plugin);
                return ;
            }
            $action     = ACTION_NAME;
        }


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
                // $method =   new \ReflectionMethod($controller,"_empty");
                if(!$controller){
                    $controller = A('Cms/helloworld');
                    $controller->index($has_plugin);
                    return ;
                }else{
                    $controller->_empty($name,$args);
                }
                
            }









    }

}