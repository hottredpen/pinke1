<?php
namespace Index\Controller;
use Common\Controller\CommonBaseController;
class ApiBaseController extends CommonBaseController {

    protected function _initialize() {
    	parent::_initialize();
        include_once APP_PATH.'Cms/Common/function.php'; // Cms 模块
        C('ENTER_FROM_API',1);
        // $user_id    = common_session_user_id();
        // if (!$user_id &&  strtolower(CONTROLLER_NAME) != 'account' ) {
        //      exit(json_encode(array('code'=>401, 'msg'=>'未登录')));
        // }
    }

    protected function kdb_json($res){
        $data         = $res;
        $data['msg']  = $data['msg'] ? $data['msg'] : $res['info'];
        unset($data['info']);
        return $this->json($data);
    }

    public function json_return($res){
        $explod_info          = errorinfo_explode_error_code($res['info'],$res['error']);
        $return               = $res;
        $return['msg']        = $res['msg'] ? $res['msg'] : $explod_info['info'];
        $return['code']       = $explod_info['code'];
        unset($return['info']);
        unset($return['error']);
        return $this->json($return);
    }
    

    protected function request_get($url){
        $headers = array('accept: application/json; Content-Type:application/json-patch+json');
        $strResult = $this->base_request($url,'GET','',$headers);
        return $strResult;
    }

    protected function request_post($url = '', $param = array()) {
        if (empty($url) || empty($param)) {
            return false;
        }
        $headers = array('accept: application/json; Content-Type:application/json-patch+json');
        $strResult = $this->base_request($url,'POST',$param,$headers);
        return $strResult;
    }

    protected function request_put($url='',$param=array()){
        $headers = array('accept: application/json; Content-Type:application/json-patch+json');
        $strResult = $this->base_request($url,'PUT',$param,$headers);
        return $strResult;
    }

    protected function request_delete($url='',$param=array()){
        $headers = array('accept: application/json; Content-Type:application/json-patch+json');
        $strResult = $this->base_request($url,'DELETE',$param,$headers);
        return $strResult;
    }

    protected function base_request($url,$type,$params,$headers){
        $ch = curl_init($url);
        $timeout = 5;
        if($headers!=""){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        }else {
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        switch ( strtoupper($type) ){
            case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, true);break;
            case "POST": curl_setopt($ch, CURLOPT_POST,true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PATCH": curl_setopt($ch, CULROPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);break;
            case "DELETE":curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
        }
        $file_contents = curl_exec($ch);//获得返回值
        
        curl_close($ch);
        return $file_contents;
    }
}