<?php
/**
 * 短信网人才
 */
namespace Common\Util\Pcode\Items;
class DXWSMS {

	public $url        = 'http://web.duanxinwang.cc/asmx/smsservice.aspx?';
	public $name       = ''; //用户账号
	public $pwd        = ''; //认证密钥
	private $content	= ''; //通道组编号
	private $stime		= '';
	private $sign       = 'pinkephp';
	private $type       = 'pt';
	private $extno      = '';
	private $errorMsg 	= array("1"=>"含有敏感词汇",
								"2"=>"余额不足",
								"3"=>"没有号码",
								"4"=>"包含sql语句",
								"10"=>"账号不存在",
								"11"=>"账号注销",
								"12"=>"账号停用",
								"13"=>"IP鉴权失败",
								"14"=>"格式错误",
								"-1"=>"系统异常"
								);

	public function __construct(){
		//$configData = M("msg_config")->where("identifier=DXWSMS")->find();
	}

	public function sendSMS($mobile, $msg){
		$flag = 0;
		$params = '';
		$argv = array( 
			'name'    => $this->name,
			'pwd'     => $this->pwd,
			'content' => $msg,
			'mobile'  => $mobile,
			'stime'   => $this->stime,
			'sign'    => $this->sign,
			'type'    => $this->type,
			'extno'   => ''
        );
		
		foreach ($argv as $key=>$value) { 
		    if ($flag!=0) { 
		        $params .= "&"; 
		        $flag = 1; 
		    }
		    $params.= $key."="; $params.= urlencode($value);// urlencode($value); 
		    $flag = 1; 
		}
		$url = "http://web.duanxinwang.cc/asmx/smsservice.aspx?".$params;
		$con = substr( file_get_contents($url), 0, 1);
		$resArr = array();
		if($con == '0'){
			$resArr["error"] = 0; 
			$resArr["msg"] = "发送成功"; 
		    return $resArr;
		}else{
			$resArr["error"] = 1; 
			$resArr["msg"] = "发送失败"; 
		    return $this->getErrorMsg($con);
		}
		
	}

	public function getErrorMsg($code){
		if(array_key_exists($code, $this->errorMsg)){
			return $this->errorMsg[$code];
		}else{
			return "发送失败";
		}
	}
}






	
	



