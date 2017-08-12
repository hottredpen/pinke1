<?php
namespace Cms\Model;
use Think\Model;
class CmsCategoryModel extends Model{

    protected $pid;

    private $tmp_data;
    private $old_data;
    private $scene_id;

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL      = 13;//管理员删除

    const ADMIN_BATCH_DELECT = 23; // 管理员批量删除

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('addtime','time',self::ADMIN_ADD,'function'),
        array('deep','set_deep',self::ADMIN_ADD,'callback'),
        //管理员修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('deep','set_deep',self::ADMIN_SAVE,'callback'),
    );

    protected $_validate = array(
        // 管理员添加
        array('title', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('title', 'is_notempty_pass', '分类名称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('title', 'is_filter_pass', '分类名称不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_notempty_pass', '标示不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_filter_pass', '标示不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('name', 'is_namenothas_pass', '已有相同分类标示', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('pid', 'get_pid', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),

        // 管理员修改
        array('id', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('title', 'is_notempty_pass', '分类名称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('title', 'is_filter_pass', '分类名称不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('name', 'is_notempty_pass', '标示不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('name', 'is_filter_pass', '标示不能包含特殊符号', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('name', 'is_namenothas_pass', '已有相同分类标示', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('pid', 'get_pid', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),


        // 管理员删除
        array('id', 'is_nothassonid_pass', '先删除分类下的子分类才能删除', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),

        // 批量删除
        array('ids', 'is_ids_pass', 'ids不能为空或当前的ids无法批量删除', self::MUST_VALIDATE,'callback',self::ADMIN_BATCH_DELECT),


    );

    public function getBatchIds(){
        return $this->tmp_data['ids_arr'];
    }

    public function is_ids_pass($ids){
        $ids_arr =  array_filter(explode(",", $ids));
        if(count($ids_arr) > 0){
            $this->tmp_data['ids_arr'] = $ids_arr;
            return true;
        }
        return false;
    }

    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('CmsCategory',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

    protected function _after_update($data, $options) {
        $id = $data['id'];
        admin_log('CmsCategory',self::ADMIN_SAVE,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('CmsCategory',self::ADMIN_DEL,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
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
    protected function get_pid($pid){
        $this->pid = $pid;
        return true;
    }

    protected function set_deep(){
        // @todo 
        if((int)$this->pid > 0){
            $p_deep = $this->where(array('id'=>$this->pid))->getField('deep');
            return (int)$p_deep + 1;
        }
        return 1;
    }

    protected function is_nothassonid_pass($id){
        $has = $this->where(array("pid"=>$id))->find();
        if($has){
            return false;
        }
        return true;
    }

    protected function is_namenothas_pass($name){
        $id  = I('id',0,'intval');
        $has = $this->where(array('name'=>$name,'id'=>array('neq'=>$id)))->find();
        if($has){
            return false;
        }
        return true;
    }

}