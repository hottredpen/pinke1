<?php
/**
 * sendCode   -- ok 
 * verifyCode -- ok
 * register   -- ok
 * login      -- ok
 * restPassword -- ok
 */

namespace Index\Controller;
use Firebase\JWT\JWT;
use Index\Service\SmsService;
class AccountController extends ApiBaseController {

    protected $userCenterUrl = "http://127.0.0.1:81/api/usercenter/api/";
    // protected $userCenterUrl = "http://112.13.199.14:81/api/usercenter/api/";
    static protected $key    = "609004480662a239155de1a5e4d0262f";

    protected function _initialize() {
    	parent::_initialize();
    }
    /**
     * 注册，获取手机验证码
     * @param  [type] $telephone [description]
     * @return [type]            [description]
     */
    public function sendCode($telephone="",$type=1){
        $smsService      = new SmsService();
        $code            = rand(1000,9999);

        $telephones[]    = $telephone;
        $content['code'] = $code;
        switch ($type) {
            case 1:
                S($telephone, $code, 300);
                $this->json($smsService->sendSMS(json_encode($content), $telephones, 409379));
                break;

        }
    }
    /**
     * 验证手机验证码
     * @param  [type] $code      [description]
     * @param  [type] $telephone [description]
     * @return [type]            [description]
     */
    public function verifyCode($telephone="",$code=0,$from_app_request=true){
        $result['code'] = 402;
        $result['msg']  = "验证码错误";
        if ($code > 0 && S($telephone) == $code) {
            $result['code'] = 200;
            $result['msg']  = "验证成功";
        }
        if($from_app_request){
            $this->json($result); // 来自app的验证请求
        }else{
            return $result;       // 内部方法调用
        }
    }
    public function isRegister(){
        $telephone = I('telephone','','trim');

        $url = $this->userCenterUrl."Register.php";
        $data['telephone'] = $telephone;
        $data['password']  = $password;

        $response = $this->request_post($url, array(
            "token" => $this->createToken($data),
            "action"=> "verify",
        ));
        if($response == "true"){
            $this->json(array('code'=>200,'msg'=>'号码未注册')); // 验证码错误
        }else{
            $this->json(array('code'=>201,'msg'=>'号码已注册')); // 验证码错误
        }
    }
    /**
     * 注册
     * @return [type] [description]
     */
    public function register(){
        $telephone = I('telephone','','trim');
        $password  = I('password','','trim');
        $code      = I('code',0,'intval');

        $res_code  = $this->verifyCode($telephone,$code,false);

        if($res_code['code'] != 200){
            $this->json($res_code); // 验证码错误
        }

        $url = $this->userCenterUrl."Register.php";
        $data['telephone'] = $telephone;
        $data['password']  = $password;

        $response = $this->request_post($url, array(
            "token" => $this->createToken($data),
            "action"=> "verify",
        ));

        if (json_decode($response, true)) {
            $registerUrl = $this->userCenterUrl."Register.php";
            $res = $this->request_post($registerUrl, array(
                "token"=> $this->createToken($data),
            ));
            $registerVerify = json_decode($res, true);

            if (200 == $registerVerify['code']) {
                $userInfoObj = self::decodeToken($registerVerify['token']);
                $res         = $this->_reg_user($userInfoObj,$password);
                if($res['id'] > 0){
                    $result['code'] = 200;
                    $result['msg']  = "注册成功";
                }else{
                    $result['code'] = 400;
                    $result['msg']  = $res['info'];
                }
            }else{
                $result['code'] = $registerVerify['code'];
                $result['msg']  = $registerVerify['msg'];
            }
        } else {
            $result['code'] = 402;
            $result['msg']  = "手机号码重复";
        }
        $this->json($result);
    }
    // 本地登录
    public function local_login(){
        $username   = I('username','','trim');
        $password   = I('password','','trim');
        $UserUserHandleObject = new \User\HandleObject\UserUserHandleObject();
        $res = $UserUserHandleObject->login($username,$password);
        $this->json_return($res);
    }
    /**
     * 登录
     * @return [type] [description]
     */
    public function login(){
        $username   = I('username','','trim');
        $password   = I('password','','trim');
        $android_id = I('android_id','','trim'); // 用户在不同手机设备中第一次登陆时，发送过来的android_id
        $ios_id     = I('ios_id','','trim');     // 用户在不同手机设备中第一次登陆时，发送过来的ios_id
        $url  = $this->userCenterUrl . "/Api.php";
        $data = $this->request_post($url, array(
                'username'=> $username,
                'password'=> md5($password),
                "action"  => "login"
            )
        );
        $result = json_decode($data, true);

        if (200 === $result['code']) {
            $md5Token       = md5($result['token']);
            $res = $this->setLogin(self::decodeToken($result['token']), $md5Token,$password,$android_id,$ios_id);
            if($res['error'] > 0){
                $this->kdb_json($res); // @todo kdb_error
            }else{
                $result['user'] = $res['user'];
            }
            if($android_id != '' || $ios_id != ''){
                $result['token'] = $md5Token;
            }else{
                $result['token'] = $result['token'];
            }
            $this->kdb_json($result);
        }else{
            $this->kdb_json($result);
        }
    }
    
    public function userinfo(){
        $user_id   = common_session_user_id();
        $user_data = D('User/User','Datamanager')->getInfoForApp($user_id);

        if($user_data){
            $result['code'] = 200;
            $result['msg']  = "获取成功";
            $result['data'] = $user_data;
        }else{
            $result['code'] = 400;
            $result['msg']  = "未登录状态";
        }

        $this->json($result);
    }

    public function loginOut(){
        unset($_SESSION['_common_user_info_']);
        S(I('token'),null);
        
        $result['code']  = 200;
        $result['msg']   = "成功";
        $this->json($result);
    }
    public function restPasswordForForget(){
        $telephone  = I('telephone','','trim');
        $code       = I('code','','trim');
        $password   = I('password','','trim');

        $res_code   = $this->verifyCode($telephone,$code,false);

        if($res_code['code'] != 200){
            $this->json($res_code); // 验证码错误
        }
        $token = array(
            "iat"       => time(),  
            "exp"       => time() + 999999,
            "phone"     => $telephone,
            "password"  => md5($password),
        );
        $token = JWT::encode($token, self::$key); 
        $url   = $this->userCenterUrl . "RestPassword.php";
        $response  = $this->request_post($url, array(
                'token'  => $token,
                'action' => 'restByPhone'
        ));
        $result = json_decode($response, true);
        return $this->json($result);

    }
    /**
     * 重置密码
     * @return [type] [description]
     */
    public function restPassword(){
        $password  = I('password','','trim');

        $user_id  = common_session_user_id();
        if($user_id == 0){
            $this->json(array('code'=>400,'msg'=>'请登录'));
        }

        $origin_id = M('user')->where(array('id'=>$user_id))->getField('origin_id');

        $token = array(
            "iat"       => time(),  
            "exp"       => time() + 999999,
            "id"        => $origin_id,
            "password"  => md5($password),
        );
        $token = JWT::encode($token, self::$key); 
        $url   = $this->userCenterUrl . "RestPassword.php";
        $response  = $this->request_post($url, array(
                'token'=> $token
        ));

        $result = json_decode($response, true);
        return $this->json($result);
    }
    private function _reg_user($userInfoObj,$password){
        C('TRANS_START_METHOD',__METHOD__);
        C('TRANS_END_METHOD',__METHOD__);

        $userModel  = D('User/User');
        // 开单宝用户第一次注册时，会初始化一个公司和一个仓库
        common_plus_start_trans(__METHOD__,$userModel);
        // 先注册
        $all_ok      = "true";

        $post['origin_id'] = $userInfoObj->id;
        $post['username']  = "erp_".$userInfoObj->id;
        $post['password']  = $password;
        $post['telephone'] = $userInfoObj->phone;
        if (!$userModel->field('origin_id,username,password,company_id,telephone,email')->create($post,1101)){
            return array("error"=>1,"info"=>$userModel->getError());
        }
        $res         = $userModel->add();
        $user_data   = $post;
        $user_data['avatar'] = "";
        $res_company = D('Company/Company','Service')->reg_common_companay($res,$userInfoObj->username);
        $res_store   = D('Store/Store','Service')->reg_store($res);
        $res_finance = D('Finance/FinanceUser','Service')->initFinanceUser($res);
        if(!$res){
            $all_ok     .= "false1";
            $error_info = "错误";
        }
        if($res_company['error'] > 0 ){
            $all_ok     .= "false2";
            $error_info = $res_company['info'];
        }
        if($res_store['error'] > 0 ){
            $all_ok     .= "false3";
            $error_info = $res_store['info'];
        }
        if($res_finance['error'] > 0 ){
            $all_ok     .= "false4";
            $error_info = $res_finance['info'];
        }
        if($all_ok == "true"){
            common_plus_commit_trans(__METHOD__,$userModel);
            return array('error'=>0,'info'=>'注册成功','id'=>$res);
        }else{
            common_plus_rollback_trans(__METHOD__,$userModel);
            return array('error'=>1,'info'=>'注册失败'.$res_company['info']."-".$res_store['info']."-".$res);
        }
    }

    private function setLogin($userInfoObj, $md5Token,$password,$android_id,$ios_id) {

        $userModel  = D('User/User');
        $user_data  = D('User/User','Datamanager')->getInfoByOriginIdForApp($userInfoObj->id);

        if($user_data){
            $post['id']         = $user_data['id'];
            $post['token']      = $md5Token;
            $post['android_id'] = $android_id;
            $post['ios_id']     = $ios_id;
            if (!$userModel->field('id,token,android_id,ios_id')->create($post,1102)){
                return array("error"=>1,"info"=>$userModel->getError());
            }
            $res = $userModel->where(array('id'=>$post['id']))->save();
            if($res){
                $all_ok = "true";
            }else{
                $all_ok = "false";
            }
        }else{
            // 已经在erp中有帐号，但本地没帐号
            $res_user  = $this->_reg_user($userInfoObj,$password);
            if($res_user['id'] > 0){
                $all_ok    = "true";
                $user_data = D('User/User','Datamanager')->getInfoForApp($res_user['id']);
            }else{
                return array("error"=>400,"info"=>'本地注册失败'.$res_user['info']);
            }
        }
        if($all_ok == "true"){
            if($android_id != '' || $post['ios_id'] != ''){
                S($md5Token,$user_data['id']);
            }
            // S('user_id_'.$user_data['id'].'_last_token',$md5Token);
            return array('error'=>0,'info'=>'ok','user'=>$user_data);
        }else{
            S($md5Token,null);
            return array('error'=>1,'info'=>$error_info);
        }
    }

    private static function decodeToken($token){
        return JWT::decode($token, self::$key, array('HS256'));
    }

    private function createToken($data) {
        $token = array(
            "iat"      => time(),  
            "exp"      => time() + 999999,
            "username" => $data['telephone'] ? $data['telephone'] : "",
            "phone"    => $data['telephone'] ? $data['telephone'] :  "",
            "password" => md5($data['password']) ? md5($data['password']) : "",
        );
        return JWT::encode($token, self::$key); 
    }
}