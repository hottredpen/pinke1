<?php
namespace Plugins\CmsProjectYuji\Controller;
use Admin\Controller\BackController;

class CmsProjectYujiController extends BackController {

    protected function _initialize() {
        parent::_initialize();
        // 如果找不到方法，重新定义到新的Admin控制器
        $this->action_list =  array(

            'index'                       => 'sadfas',
            // base
            'syc_card_from_wechat'        => 'WeixinCardBase/syc_card_from_wechat',


        );
    }
}