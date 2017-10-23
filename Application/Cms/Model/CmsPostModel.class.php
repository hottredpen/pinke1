<?php
namespace Cms\Model;
use Common\Model\CommonModel;
class CmsPostModel extends CommonModel{

    const INFO_ADD      = 11; // 添加
    const INFO_SAVE     = 12; // 修改
    const INFO_DELECT   = 13; // 删除

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('addtime','time',self::INFO_ADD,'function'),
        array('updatetime','time',self::INFO_ADD,'function'),
        array('ip','get_client_ip',self::INFO_ADD,'function'),
        array('status','0',self::INFO_ADD),
        array('qq','',self::INFO_ADD),

        //管理员修改
        array('updatetime','time',self::INFO_SAVE,'function'),



    );

    protected $_validate = array(

        // 添加
        array('name', 'is_notempty_pass', '名称不能为空', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('message', 'is_notempty_pass', '留言内容不能为空', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('email', 'is_notempty_pass', '请填写邮箱，方便我们与您取得联系', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('email', 'is_email_format_pass', '邮箱格式错误', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('phone', 'is_phone_format_pass', '手机格式错误', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('name', 'is_notpostjust_pass', '您的留言我们已经收到，请勿重复留言', self::MUST_VALIDATE,'callback',self::INFO_ADD),


    );

    protected function is_notpostjust_pass(){
        $ip   = get_client_ip();
        $time = time() - 30;
        $has  = $this->where(array('ip'=>$ip,'addtime'=>array('gt',$time)))->find();
        if($has){
            return false;
        }
        return true;
    }


}