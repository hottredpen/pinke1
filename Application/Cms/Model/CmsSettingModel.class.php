<?php
namespace Cms\Model;
use Common\Model\CommonModel;
class CmsSettingModel extends CommonModel{

    const CHECK_DATA     = 11; // 数据批量检测
    const SAVE_INFO      = 12; // 单个修改

    public $settingData;

    //字段衍射
    protected $_map = array(

                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 数据批量检测
        array('updatetime','time',self::CHECK_DATA,'function'),

        // 单个修改
        array('updatetime','time',self::SAVE_INFO,'function'),
    );

    protected $_validate = array(
        //数据批量检测
        array('setting', 'get_valid_name', 'callback_true', self::MUST_VALIDATE,'callback',self::CHECK_DATA),

    );

    public function getSettingData(){
        return $this->settingData;
    }

    protected function get_valid_name($setting){
        $setting = $_POST; // 从此直接从$_POST取
        foreach ($setting as $key => $value) {
            $hasname = $this->where(array("name"=>$key))->find();
            if($hasname){
                $this->settingData[$key]['name'] = $key;
                $this->settingData[$key]['data'] = $value;
            }
        }
        return true;
    }

}