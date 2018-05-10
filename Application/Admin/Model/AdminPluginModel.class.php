<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class AdminPluginModel extends CommonModel{

    const ADMIN_ADD  = 11;
    const ADMIN_SAVE = 12;
    const ADMIN_DEL  = 13;

    protected $tmp_data;
    protected $old_data;
    protected $scene_id;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(

        // 安装
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('config','set_config',self::ADMIN_ADD,'callback'),

    );

    protected $_validate = array(

        array('name', 'is_name_pass', '已存在相同插件名无法安装', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        
        
    );

    protected function _after_insert($data, $options) {
        $id = $this->getLastInsID();
        admin_log('AdminPlugin',self::ADMIN_ADD,$id,admin_session_admin_id());
    }

    protected function _after_update($data, $options) {
        $id = $data['id'];
        admin_log('AdminPlugin',self::ADMIN_SAVE,$id,admin_session_admin_id());
    }

    protected function _after_delete($data, $options) {
        $id = $data['id'];
        admin_log('AdminPlugin',self::ADMIN_DEL,$id,admin_session_admin_id());
    }


    protected function is_name_pass($name){
        $has = $this->where(array('name'=>$name))->find();
        if(!$has){
            $this->tmp_data['name'] = $name;
            return true;
        }
        return false;
    }

    protected function set_config(){
        $config    = include APP_PATH . 'Plugins/'.$this->tmp_data['name'].'/config.php';
        $form_data = common_trans_plugin_config_data($config);
        if($form_data){
            foreach ($form_data['item_list'] as $key => $value) {
                $new_data[$value['name']] = $value['value'];
            }
        }
        return serialize($new_data);
    }

}