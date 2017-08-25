<?php
namespace Common\Model;
use Think\Model;
class CommonModel extends Model{

    protected function afterAutoValidation($data, $options) {
        if(defined('IS_DEMO')  && IS_DEMO == true){
            $this->error = "当前系统为demo状态，无法进行增删改";
            return false;
        }
        return ture;
    }

}