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

class AdminLogController extends BackController{

    public function _initialize() {
        parent::_initialize();
    }

    public function index(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminLog','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminLog','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('管理员列表')
                ->setSearch(array('username'=>'管理员账号'),'',U('admin/AdminLog/index'))
                ->addOrder('create_time')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('model', '模型')
                ->addTableColumn('scene_id', '场景id')
                ->addTableColumn('record_id', '操作对象id')
                ->addTableColumn('admin_id', '管理员id')
                ->addTableColumn('info', 'info')
                ->addTableColumn('ip', 'ip')
                ->addTableColumn('create_time', '操作时间','function','common_format_time')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

}