<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminLogTplModel extends CommonModel{

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL   = 13;//管理员删除

    protected $tmp_data;
    protected $old_data;
    protected $scene_id;
    
    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('update_time','time',self::ADMIN_ADD,'function'),
        
        //管理员修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        
    );

    protected $_validate = array(
        // 添加
        array('title', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('title', 'is_notempty_pass', '名称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),

        // 修改
        array('id', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('title', 'is_notempty_pass', '用户组名称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),

        // 删除
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),

    );
    /**
     ***********************
     * 业务方法
     ***********************
     */
    protected function is_id_pass($id){
        $info = $this->where(array('id'=>$id))->find();
        if($info){
            $this->old_data = $info;
            return true;
        }
        return false;  
    }

}