<?php
namespace Common\Model;
use Think\Model;
class CommonModel extends Model{

    protected $tmp_data;
    protected $old_data;

	protected function beforeAutoValidation($data, $options){
        // 


		return true;
	}

    protected function afterAutoValidation($data, $options) {
        if(defined('IS_DEMO')  && IS_DEMO == true){
            $this->error = "当前系统为demo状态，无法进行增删改";
            return false;
        }
        return true;
    }

}