<?php
namespace User\Service;

class UserService{
    // todo 验证token的实效性和ip
    public function loginCheckToken($token){
        $has = M('user')->where(array('token'=>$token))->find();
        if($has){
            S($token,$has['id']);
            return array('error'=>0,'info'=>'登录成功');
        }else{
            return array('error'=>1,'info'=>'登录失败');
        }
    }

}