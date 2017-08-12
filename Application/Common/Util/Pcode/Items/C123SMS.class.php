<?php
/**
 * c125短信人才
 */
namespace Common\Util\Pcode\Items;
class C123SMS {

	private $url;
	private $ac;
	private $authkey;
	private $cgid;
	private $csid;
	private $t           = "";
	private $description ="";

	public function __construct(){
		$configData = M("msg_config")->where("identifier=C123SMS")->find();
		
		$this->url     = $configData["posturl"];
		$this->ac      = $configData["spcode"];
		$this->authkey = $configData["authkey"];
		$this->cgid    = $configData["cgid"];
		$this->csid    = $configData["csid"];
	}

	public function sendSMS($mobile, $msg){
		$resArr=array();
		$data = array
			(
			'action'=>'sendOnce', //发送类型 sendOnce短信发送，sendBatch一对一发送，sendParam	动态参数短信接口
			'ac'      => $this->ac,		 //用户账号
			'authkey' => $this->authkey,  //认证密钥
			'cgid'    => $this->cgid,        //通道组编号
			'm'       => $mobile,		      //号码,多个号码用逗号隔开
			'c'       => $msg,		 //如果页面是gbk编码，则转成utf-8编码，如果是页面是utf-8编码，则不需要转码
			'csid'    => $this->csid,        //签名编号 ，可以为空，为空时使用系统默认的签名编号
			't'       => $this->t               //定时发送，为空时表示立即发送
			);
			//echo ("URL：".$this->url);
		$xml = $this->postSMS($this->url,$data); //POST方式提交
	    $re  = simplexml_load_string(utf8_encode($xml));
		if(trim($re['result'])==1) {
		    foreach ($re->Item as $item) {
				$stat['msgid']  = trim((string)$item['msgid']);
				$stat['total']  = trim((string)$item['total']);
				$stat['price']  = trim((string)$item['price']);
				$stat['remain'] = trim((string)$item['remain']);
				$stat_arr[]=$stat;
	        }
			if(is_array($stat_arr)) {
				$resArr["error"] = 0; 
				$resArr["msg"] = "发送成功"; 
				return $resArr;
				//echo "发送成功,返回值为".$re['result'];
		    }
			
	    } else { //发送失败的返回值
	    	
		    switch(intval($re['result'])){
				case  0: 
					$this->save_usual_info($mobile, $msg, "帐户格式不正确(正确的格式为:员工编号@企业编号)"); 
					break; 
				case  -1: 
					$this->save_usual_info($mobile, $msg, "服务器拒绝(速度过快、限时或绑定IP不对等)");
					break;
				case  -2: 
					$this->save_usual_info($mobile, $msg, " 密钥不正确");
					break;
				case  -3: 
					$this->save_usual_info($mobile, $msg, "密钥已锁定");
					break;
				case  -4: 
					$this->save_usual_info($mobile, $msg, "内容和号码不能为空，手机号码数过多，发送时间错误等");
					break;
				case  -5: 
					$this->save_usual_info($mobile, $msg, "无此帐户");
					break;
				case  -6: 
					$this->save_usual_info($mobile, $msg, "帐户已锁定或已过期");
					break;
				case  -7: 
					$this->save_usual_info($mobile, $msg, "帐户未开启接口发送");
					break;
				case  -8: 
					$this->save_usual_info($mobile, $msg, "不可使用该通道组");
					break;
				case  -9: 
					$this->save_usual_info($mobile, $msg, "帐户余额不足");
					break;
				case  -10: 
					$this->save_usual_info($mobile, $msg, "内部错误");
					break;
				case  -11: 
					$this->save_usual_info($mobile, $msg, "扣费失败");
					break;
				default: break;
			}
			$resArr["error"] = 1;
			$resArr["msg"] = $this->description; 
			return $resArr;
			
		}
	}

	private function save_usual_info($mobile, $msg, $err){
		$this->description = $err;
		$u_data = array();
		$u_data['info'] = $mobile.':'.$msg.'------'.$err;
		$u_data['addtime'] = date('Y-m-d H:i:s', time());
		$u_res = M('unusual')->add($u_data);
		return true;
	}

	private function postSMS($url, $data='')
	{
		$row = parse_url($url);
		$host = $row['host'];
		$port = $row['port'] ? $row['port']:80;
		$file = $row['path'];
		while (list($k,$v) = each($data)) 
		{
			$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
		}
		$post = substr( $post , 0 , -1 );
		$len = strlen($post);
		$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
		if (!$fp) {
			return "$errstr ($errno)\n";
		} else {
			$receive = '';
			$out = "POST $file HTTP/1.0\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;		
			fwrite($fp, $out);
			while (!feof($fp)) {
				$receive .= fgets($fp, 128);
			}
			fclose($fp);
			$receive = explode("\r\n\r\n",$receive);
			unset($receive[0]);
			//dump($receive);
			return implode("",$receive);
		}
	}

}


?>