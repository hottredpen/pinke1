<?php
// +----------------------------------------------------------------------
// | 品客PHP框架 [ pinkePHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 浙江蓝酷网络科技有限公司 [ http://www.lankuwangluo.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.pinkephp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace Admin\Controller;

class ModuleController extends BackController {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $this->cpk_error('模块管理正在开发中！');
    }
}