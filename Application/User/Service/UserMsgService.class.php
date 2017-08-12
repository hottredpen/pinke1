<?php
namespace User\Service;
use Common\Util\Pcode\Pcode;
use Common\Util\PHPMailer\PHPMailer;
/**
 * 被动型的数据操作 不包含业务逻辑及过滤，只需执行
 * 因为在本次点击执行时，用D方法生成的检验有一个错误，后面的都无法进行，所以只能用M()->add()
 */
class UserMsgService{


    public function sendLocalmsg($user_id,$title,$content){
        $res = $this->_save_localmsg_log($user_id,$title,$content);
        if($res){
            return array('error'=>0,'info'=>'发送成功');
        }else{
            return array('error'=>1,'info'=>'发送失败');
        }
    }



	public function sendSMS($user_id,$phone,$title,$content){

        if(!is_phone_format_pass($phone)){
            return array('error'=>1,'info'=>'手机号码格式错误');
        }

        $res = Pcode::sendMsg($phone,$content);
        if(isset($res['error']) && $res['error'] == 1){
            return array('error'=>1,'info'=>$res['msg']);
        } else {
            // 发送成功设置发送时间
            $_SESSION['mobilecodetime'] = time();
            $this->_save_sms_log($user_id,$phone,$title,$content);
            return array('error'=>0,'info'=>$res['msg']);
        }
	}


    public function sendEmail($user_id,$email,$title,$content){
        if(!is_email_format_pass($email)){
            return array('error'=>1,'info'=>'邮箱格式错误');
        }
        $res = $this->_sendEmail($email,$title,$content);
        if($res){
            $this->_save_email_log($user_id,$email,$title,$content);
            return array('error'=>0,'info'=>'发送成功');
        }else{
            $this->_save_email_log($user_id,$email,$title,$content);
            return array('error'=>1,'info'=>'发送失败');
        }
    }


    private function _sendEmail($email,$title,$content){

        $config = array(
            'mail_address'   => C('common_mail_address'),
            'mail_loginname' => C('common_mail_loginname'),
            'mail_smtp'      => C('common_mail_smtp'),
            'mail_password'  => C('common_mail_password'),
            'mail_port'      => C('common_mail_port'),
            'mail_name'      => C('common_mail_name'),
        );

        $mail = new PHPMailer();
        // 设置PHPMailer使用SMTP服务器发送Email
        $mail->IsSMTP();
        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet='UTF-8';
        // 添加收件人地址，可以多次使用来添加多个收件人
        $mail->AddAddress($email);
        // 设置邮件正文
        $mail->Body=$content;
        // 设置邮件头的From字段。
        $mail->From=$config['mail_address'];
        // 设置发件人名字
        $mail->FromName=$config['mail_name'];
        // 设置邮件标题
        $mail->Subject=$title;
        // 服务器发送shell
        $mail->Sendmail = "/usr/sbin/sendmail";
        // 设置是否是html
        $mail->IsHTML(true);
        // 设置SMTP服务器。
        $mail->Host=$config['mail_smtp'];
        // 设置为“需要验证”
        $mail->SMTPAuth=true;
        // 设置用户名和密码。
        $mail->Username=$config['mail_loginname'];
        $mail->Password=$config['mail_password'];
        $res = $mail->Send();
        return $res;
    }

    private function _save_localmsg_log($user_id,$title,$content){

        $add_data['admin_id'] = admin_session_admin_id();
        $add_data['fuid']     = 0;
        $add_data['tuid']     = $user_id;
        $add_data['msg_type'] = 1;
        $add_data['title']    = $title;
        $add_data['content']  = common_filter_editor_content($content);
        $add_data['addtime']  = time();

        $res = M('user_msg_localmsg_log')->add($add_data);

        return $res;

    }

    private function _save_email_log($user_id,$email,$title,$content){
        
        $add_data['admin_id'] = admin_session_admin_id();
        $add_data['fuid']     = 0;
        $add_data['tuid']     = $user_id;
        $add_data['msg_type'] = 1;
        $add_data['email']    = $email;
        $add_data['title']    = $title;
        $add_data['content']  = common_filter_editor_content($content);
        $add_data['addtime']  = time();

        $res = M('user_msg_email_log')->add($add_data);

        return $res;

    }

    private function _save_sms_log($user_id,$phone,$title,$content){
        
        $add_data['admin_id'] = admin_session_admin_id();
        $add_data['fuid']     = 0;
        $add_data['tuid']     = $user_id;
        $add_data['msg_type'] = 1;
        $add_data['phone']    = $phone;
        $add_data['title']    = $title;
        $add_data['content']  = common_filter_textarea($content);
        $add_data['addtime']  = time();

        $res = M('user_msg_sms_log')->add($add_data);

        return $res;

    }


}