<?php
namespace Cms\Model;
use Think\Model;
class CmsServicePostModel extends Model{

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
        array('ip','get_client_ip',self::INFO_ADD,'function'),

        //管理员修改
        array('updatetime','time',self::INFO_ADD,'function'),



    );

    protected $_validate = array(

        // 添加
        array('orderno', 'is_notempty_pass', '订单号不能为空', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('phone', 'is_notempty_pass', '手机号不能为空', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('phone', 'is_phone_format_pass', '错误的手机格式', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('message', 'is_notempty_pass', '请写点啥吧，意见或批评，都是我们将产品做好的动力', self::MUST_VALIDATE,'function',self::INFO_ADD),
        array('orderno', 'is_notpostjust_pass', '您的维修订单已经保存，请勿重复提交', self::MUST_VALIDATE,'callback',self::INFO_ADD),


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