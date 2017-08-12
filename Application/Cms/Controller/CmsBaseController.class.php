<?php 
namespace Cms\Controller;
use Common\Controller\CommonBaseController;
/**
 * 前台控制器
 */
class CmsBaseController extends CommonBaseController {

    protected $visitor;
    
    protected function _initialize() {
        parent::_initialize();
    }
    
}