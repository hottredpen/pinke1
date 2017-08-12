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
use Common\Util\Dir;

class CacheController extends BackController{
    
    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $this->theme('one')->admindisplay("clear");
    }

    public function clear() {
        $type = I('type', '', 'trim');
        $obj_dir = new Dir();
        switch ($type) {
            case 'tpl':
                is_dir(CACHE_PATH) && $obj_dir->delDir(CACHE_PATH);
                break;
            case 'data':
                is_dir(DATA_PATH) && $obj_dir->delDir(DATA_PATH);
                break;
            case 'temp':
                is_dir(TEMP_PATH) && $obj_dir->delDir(TEMP_PATH);
                break;
            case 'html':
                is_dir(HTML_PATH) && $obj_dir->del(HTML_PATH);
                break;                
            case 'logs':
                is_dir(LOG_PATH) && $obj_dir->delDir(LOG_PATH);
                break;
        }
        $this->ajaxReturn(1,L('clear_success'));
    }

    public function qclear() {
        $obj_dir = new Dir();
        is_dir(TEMP_PATH) && $obj_dir->delDir(TEMP_PATH);
        $this->ajaxReturn(1, L('clear_success'));
    }
}