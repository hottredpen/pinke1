<?php
namespace User\Controller;
use Common\Controller\CommonBaseController;

class IndexController extends CommonBaseController {
	public function _initialize() {
        parent::_initialize();
    }

    public function index(){
  		$this->layoutdisplay(); 
    }



}
