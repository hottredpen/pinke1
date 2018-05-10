<?php 
namespace Admin\Admin;

class AdminLogAdmin extends AdminBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function adminlog(){
        $p         = I('p',1,'intval');
        $page_size = 10;
        $map       = $this->getMap();
        $order     = $this->getOrder();

        $data_list    = D('Admin/AdminLog','Datamanager')->getData($p,$page_size,$map,$order);
        $data_num     = D('Admin/AdminLog','Datamanager')->getNum($map);

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('操作记录列表')
                ->setSearch(array('model'=>'模型'),'',U('admin/admin/adminlog'))
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