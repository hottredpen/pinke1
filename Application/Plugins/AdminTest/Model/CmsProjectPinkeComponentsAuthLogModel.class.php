<?php
namespace Plugins\CmsProjectPinke\Model;
use Think\Model;
class CmsProjectPinkeComponentsAuthLogModel extends Model{

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL      = 13;//管理员删除

    private $tmp_data;
    private $old_data;
    private $scene_id;
    
    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('update_time','time',self::ADMIN_ADD,'function'),
        array('auth_key','set_auth_key',self::ADMIN_ADD,'callback'),
        
        //管理员修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        // array('full_name','set_full_name',self::ADMIN_SAVE,'callback'),
        
    );

    protected $_validate = array(
        
        // 添加
        array('component_id', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('component_id', 'is_component_id_pass', '请选择具体组件', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('buyer_id', 'get_buyer_id', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),

        // 修改
        array('id', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),



        // 删除
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_DEL),

    );
    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('CmsProjectPinkeComponentsAuthLog',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

    protected function _after_update($data, $options) {
        $id = $data['id'];
        admin_log('CmsProjectPinkeComponentsAuthLog',self::ADMIN_SAVE,$id,admin_session_admin_id(),"",$this->old_data,$data);
    }
    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('CmsProjectPinkeComponentsAuthLog',self::ADMIN_DEL,$id,admin_session_admin_id(),"",$this->old_data,$data);
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


    protected function is_component_id_pass($component_id){
        $component_data = M('cms_project_pinke_components')->where(array('id'=>$component_id))->find();
        if($component_data){
            $this->tmp_data['component_id']   = $component_id;
            $this->tmp_data['component_data'] = $component_data;
            return true;
        }
        return false;
    }

    protected function get_buyer_id($buyer_id){
        $this->tmp_data['buyer_id'] = (int)$buyer_id;
        return true;
    }

    // 第一次生成，后面没法修改
    protected function set_auth_key(){
        // component_id   buyer_id  md5 
        $component_id   = $this->tmp_data['component_data']['id'];
        $component_name = $this->tmp_data['component_data']['name'];
        $buyer_id       = $this->tmp_data['buyer_id'];

        $b1b2_arr = $this->_make_block1_block2($buyer_id,$component_id);
        $b2b3_arr = $this->_make_block1_block2($buyer_id,$buyer_id,$b1b2_arr['part_2']);
        
        $all_part = $b1b2_arr['part_1']."-".$b1b2_arr['part_2']."-".$b2b3_arr['part_2'];
        $md5_val  = md5($component_name.$all_part);
        $auth_key = $all_part."-".$md5_val;

        return $auth_key;
    }
    // 添加一个有效期
    private function _make_block1_block2($buyer_id,$val_1,$part_1=''){
        if($part_1 == ""){
            $part_1 = substr(md5($val_1),0,20);
            $part_2 = substr(md5($buyer_id.$val_1),0,20);
        }else{
            $part_2 = substr(md5($buyer_id.$val_1),0,20);
        }
        $part_1_arr = str_split($part_1);
        $part_2_arr = str_split($part_2);

        $val_1_arr  = str_split(sprintf('%020s',decbin($val_1)));
        for ($i=0; $i < 20; $i++) { 
            if($val_1_arr[$i] == 1){
              if($part_2_arr[$i] == $part_1_arr[$i]){
                continue;
              }else{
                $part_2_arr[$i] = $part_1_arr[$i];
              }
            }else{
              if($part_2_arr[$i] == $part_1_arr[$i]){
                $part_2_arr[$i] = chr(ord($part_2_arr[$i] + ($val_1%10 + 1)));
              }else{
                continue;
              }
            }
        }
        $part_1 = implode("", $part_1_arr);
        $part_2 = implode("", $part_2_arr);

        return array('part_1'=>$part_1,'part_2'=>$part_2);
    }


}