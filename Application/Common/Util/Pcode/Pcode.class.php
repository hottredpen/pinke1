<?php

namespace Common\Util\Pcode;
/**
 * 短信操作类
 * @file Pcode.class.php
 */
class Pcode {

	static $_ip_max     = 5;
	static $_mobile_max = 5;
	static $_serverType = "DXWSMS";//"C123";//UNICOM；//DXWSMS
	/* 发送验证码 */
	static function sendCodeMsg($mobile,$code=""){

		$arr = array();
		if($code==""){
			$code = self::_makeCode(6);
		}
		$msg = '验证码为'.$code.'(XXX客服绝不会索取此验证码，切勿告知他人），请在页面中输入以完成验证，有问题请致电123-456-789';
        $_SESSION['mobilecode'] = md5($code.$mobile);// 写入SESSION
		$res = self::sendMsg($mobile, $msg);// 发送短信
		if($res['error'] == 0){
			$res['code'] = 1;
		}
		return $res;
	}

	/**
	 * 发送短信
	 * @param  [string] $mobile 电话号码
	 * @param  [string] $msg    短信内容
	 */
	static function sendMsg($mobile, $msg){
		// 查询发送次数
		// $ipCount     = self::getIPSendCount();
		// $mobileCount = self::getMobileSendCount();

		$result = array();

		if($ipCount > self::$_ip_max){
			$result["error"] = 1;
			$result["msg"] = "发送失败，您今天发送的短信过于频繁！~请明天再试";
			return $result;
		}
		if($mobileCount>self::$_mobile_max){
			$result["error"] = 1;
			$result["msg"] = "发送失败，您今天发送的短信过于频繁！~请明天再试";
			return $result;
		}

		$obj = self::createService(self::$_serverType);
		$res = $obj->sendSMS($mobile, $msg);
		// 保存发送次数
		// self::operateDB($res,$mobile);
		return $res;
	}

	/**
	 * 对数据库进操作。
	 * @param  [type] $result [description]
	 * @return [type]         [description]
	 */
	static function operateDB($result, $mobile){
		if($result){
			$telModel        = M("TelSms");
			$data            = array();
			$data["tel"]     = $mobile;
			$data["addtime"] = date("Y-m-d",time());
			$telModel->add($data);

			$ipModel         = M("IpSms");
			$data            = array();
			$data["ip"]      = self::getIP();
			$data["addtime"] = date("Y-m-d",time());
			$ipModel->add($data);
		}
	}

	/**
	 * 检查IP发送次数是否达到上线
	 * @return [type] [description]
	 */
	static function getIPSendCount(){
		$ipModel         = M("IpSms");
		$whereData = array();
		$whereData["ip"]=self::getIP();
		$whereData["addtime"] = date("Y-m-d",time());
		$result = $ipModel->where($whereData)->select();
		return count($result);
	}

	/**
	 * 检查手机号发送短信是否达到上线
	 * @return [type] [description]
	 */
	static function getMobileSendCount($mobile){
		$telModel = M("TelSms");
		$whereData = array();
		$whereData["tel"]=$mobile;
		$whereData["addtime"] = date("Y-m-d",time());
		$result = $telModel->where($whereData)->select();
		return count($result);
	}

	static function getIP(){
		$ip=false; 
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
			$ip=$_SERVER['HTTP_CLIENT_IP']; 
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
			if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
			for ($i=0; $i < count($ips); $i++){
				if(!eregi ('^(10│172.16│192.168).', $ips[$i])){
					$ip=$ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}

	/**
	 * 创建短信对象
	 * @return obj 短信
	 */
	static function createService($type){
		$obj = null;
		if($type=="UNICOM"){
			$obj = new \Common\Util\Pcode\Items\UnicomSMS();
		}else if ($type=="C123") {
			$obj = new \Common\Util\Pcode\Items\C123SMS();
		}else if($type == "DXWSMS"){
			$obj = new \Common\Util\Pcode\Items\DXWSMS();
		}
		return $obj;
	}

	/* 生成随机字符串 */
	static function _makeCode($length = 4){
		$_strA = '0123456789';
		$_str  = $_strA;
		$str   = substr(str_shuffle($_str), 0, $length);
		return $str;
	}
}




?>