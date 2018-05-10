<?php 
namespace Admin\Admin;

class AdminDatabaseAdmin extends AdminBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function adminDatabase(){
        $data_list = M()->db()->query('SHOW TABLE STATUS');

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('当前数据库表')
                ->setListCheckboxFieldName('name')
                ->ajax_url(U('Admin/ajaxAdmin'))
                ->addTopButton('custom',array('title'=>'备份','class'=>'J_batch_layer_dialog btn btn-primary','data-width'=>"700px",'data-title'=>'设置标签','data-target-from'=>'ids','data-uri'=>U( 'admin/admin/exportDatabaseConfirm')))
                ->addOrder('last_time')
                ->addTableColumn('name', '表名')
                ->addTableColumn('rows', '行数')
                ->addTableColumn('data_length', '大小','function','common_format_file_size')
                ->addTableColumn('data_free', '冗余')
                ->addTableColumn('comment', '备注')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function db_list(){
        $data_list = $this->real_db_data();

        $builder  = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('数据库备份列表')
                ->setSearch(array('filename'=>'备份名称'),'',U('admin/admin/index'))
                ->addTableColumn('filename', '备份名称')
                ->addTableColumn('size', '数据大小','function','common_format_file_size')
                ->addTableColumn('time', '备份时间','time')
                ->addTableColumn('tables_num', '表个数')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('layer',array('title'=>'还原','data-url'=>U('admin/AdminDatabase/importConfirm',array('filename'=>'__filename__')),'data-width'=>"800px",'data-height'=>'520px','data-title'=>'数据还原'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    private function real_db_data(){
        $path = C('DATA_BACKUP_PATH');
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path,  $flag);

        $list = array();
        foreach ($glob as $name => $file) {
            if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                $date = "{$name[0]}{$name[1]}{$name[2]}";
                $time = "{$name[3]}{$name[4]}{$name[5]}";
                $date_time = "{$name[0]}-{$name[1]}-{$name[2]} {$name[3]}:{$name[4]}:{$name[5]}";
                $part = $name[6];

                if(isset($list["{$date}-{$time}"])){
                    $info = $list["{$date}{$time}"];
                    $info['part'] = max($info['part'], $part);
                    $info['size'] = $info['size'] + $file->getSize();
                } else {
                    $info['part'] = $part;
                    $info['size'] = $file->getSize();
                }
                $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                $info['time']     = strtotime($date_time);
                $info['filename'] = "{$date}-{$time}";

                $info['tables_num'] = M('admin_database')->where(array('filename'=>$info['filename']))->getField('tables_num');

                $list[$info['time']] = $info;
            }
        }
        krsort($list);
        return $list;
    }
    /**
     * 备份数据
     */
    public function exportDatabaseConfirm(){
        $tables         = I('ids','','common_filter_words');
        $info['tables'] = $tables;
        $tables_data     = array_filter(explode(",", $tables));
        if(count($tables_data) == 0){
            $tables_data = array();
        }
        // 主要是为了获得token
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('备份数据库')
                ->setPostUrl(U('Admin/admin/exportDatabase'));
                if(count($tables_data) > 0){
                    $builder->addFormItem('db11', 'select','查看确认数据表','',$tables_data,array('first_value'=>-1,'first_title'=>'备份部分数据表'));
                }else{
                    $builder->addFormItem('db22', 'select','查看确认数据表','',$tables_data,array('first_value'=>-1,'first_title'=>'备份全部数据表'));
                }
        $builder->addFormItem('tables', 'hidden')
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function exportDatabase(){
        $tables         = I('tables','','common_filter_words');
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->exportDatabase($tables);
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info'],array('backurl'=>U('admin/admin/db_list')));
        }else{
            $this->pk_error($res['info']);
        }
    }

    public function importConfirm(){
        $filename         = I('filename','','trim');
        $info['filename'] = $filename;
        $builder  = D('Admin/Form','Builder');
        $builder->theme('one')->setMetaTitle('还原数据库')
                ->setPostUrl(U('Admin/admin/importDatabase'))
                ->addFormItem('import_database_auth_code', 'text', '短信验证码','todo,目前可不填')
                ->addFormItem('filename', 'hidden')
                ->setFormData($info)
                ->assign_builder()
                ->admindisplay('Common@builder:FormBuilder');
    }

    public function importDatabase(){
        $AdminAdminHandleObject = $this->visitor->AdminAdminHandleObject();
        $res = $AdminAdminHandleObject->importDatabase();
        if($res['error']==0 && $res['info'] != ""){
            $this->pk_success($res['info'],array('backurl'=>U('admin/admin/adminDatabase')));
        }else{
            $this->pk_error($res['info']);
        }
    }
}