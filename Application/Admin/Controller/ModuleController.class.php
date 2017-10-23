<?php

namespace Admin\Controller;

class ModuleController extends BackController {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $this->cpk_error('模块管理正在开发中！');
    }
}