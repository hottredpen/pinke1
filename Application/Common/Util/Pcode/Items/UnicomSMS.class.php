<?php
namespace Common\Util\Pcode\Items;
/**
 * HTTP接口发送短信，参数说明见文档，需要安装CURL扩展
 */
class UnicomSMS {
	private $_apiUrl;
	public $SpCode;
	public $LoginName;
	public $Password;
	public $MessageContent;
	public $UserNumber;
	public $SerialNumber;
	public $ScheduleTime;
	public $ExtendAccessNum;
	public $f ="";
	public $errorMsg;
	
    public function __construct(){
    	$data = array();
    	$data["identifier"] = "UNICOM";
    	$configData = M("msg_config")->where($data)->find();
		$this->_apiUrl   = $configData["posturl"];
		$this->SpCode    = $configData["spcode"];
		$this->LoginName = $configData["loginname"];
		$this->Password  = $configData["password"];
    }
	/**
	 * 发送短信
	 * @return boolean
	 */
	public function sendSMS($mobile, $msg){
		$params = array();
		$params["SpCode"] = $this->SpCode;
		$params["LoginName"] = $this->LoginName;
		$params["Password"] = $this->Password;
		$params["MessageContent"] = iconv("UTF-8", "GB2312//IGNORE", $msg);
		$params["UserNumber"] = $mobile;
		$params["SerialNumber"] = "12345678901234567890";
		$params["ScheduleTime"] = "";
		$params["ExtendAccessNum"] = "";
		$params["f"] = $this->f;
		$data = http_build_query($params);
		$res = iconv('GB2312', 'UTF-8//IGNORE', $this->_httpClient($data));
		//$res = $this->_httpClient($data);
		$resArr = array();
        parse_str($res, $resArr);
		if (!empty($resArr) && $resArr["result"] == 0) {
			$resArr["error"] = 0; 
			$resArr["msg"] = "发送成功"; 
			return $resArr;
		}else {
			//dump($res);
			if (empty($this->errorMsg)){
				$this->errorMsg = isset($resArr["description"]) ? $resArr["description"] : '未知错误';
			} 
			$resArr["error"] = 1; 
			$resArr["msg"] 	 = $this->errorMsg; 
			return $resArr;
		}
	}
	
	
	/**
	 * POST方式访问接口
	 * @param string $data
	 * @return mixed
	 */
	private function _httpClient($data) {
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$this->_apiUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
		} catch (Exception $e) {
			$this->errorMsg = $e->getMessage();
			return false;
		}
	}
	private function _makeCode($length = 6){
		$_strA = '0123456789';
		$_strB = 'abcdefghijklmnpqrst';

		// $_str = $_strA.$_strB;
		$_str = time();
		
		$str = substr(str_shuffle($_str), 0, $length);

		return $str;
	}
}
