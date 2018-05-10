<?php
namespace Admin\Model;
use Common\Model\CommonModel;
use OT\Database;
class AdminDatabaseModel extends CommonModel{

    const ADMIN_BACK     = 10; // 还原
    const ADMIN_ADD      = 11; // 备份
    const ADMIN_SAVE     = 12; //
    const ADMIN_DEL   = 13; // 管理员删除


    protected $tmp_data;
    protected $old_data;
    protected $scene_id;
    
    private $_backup_config;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 备份
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('filename','set_filename',self::ADMIN_ADD,'callback'),
        array('tables_data','set_tables_data',self::ADMIN_ADD,'callback'),
        array('tables_num','set_tables_num',self::ADMIN_ADD,'callback'),

        // 还原(此处只是为了配合D方法的正确执行)
        array('create_time','time',self::ADMIN_BACK,'function'),

    );

    protected $_validate = array(

        // array('tables', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('tables', 'get_export_database_config', '备份目录不存在或不可写，请检查后重试！', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('tables', 'is_export_database_lock_pass', '检测到有一个备份任务正在执行，请稍后再试(或删除data\backup下的lock)！', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('tables', 'get_tables_data', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('tables', 'start_export_database', '备份失败', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),

        // 还原
        // array('filename', 'is_form_token_pass', '过期的token或来自非指定方法创建的表单', self::MUST_VALIDATE,'function',self::ADMIN_BACK),
        array('filename', 'is_filename_pass', '错误的参数', self::MUST_VALIDATE,'callback',self::ADMIN_BACK),
        array('import_database_step', 'start_import_database', '还原失败', self::MUST_VALIDATE,'callback',self::ADMIN_BACK),

    );

    protected function get_export_database_config(){

        $path = C('DATA_BACKUP_PATH');
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        //读取备份配置
        $config = array(
            'path'     => realpath($path) . DIRECTORY_SEPARATOR,
            'part'     => C('DATA_BACKUP_PART_SIZE'),
            'compress' => C('DATA_BACKUP_COMPRESS'),
            'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
        );
        if(is_writeable($config['path'])){
            $this->_backup_config = $config;
            return true;
        }
        return false;
    }

    protected function set_tables_data(){
        return serialize($this->tmp_data['tables_data']);
    }

    protected function set_tables_num(){
        return $this->tmp_data['tables_num'];
    }

    protected function set_filename(){
        return $this->tmp_data['filename'];
    }

    protected function is_export_database_lock_pass(){
        $config = $this->_backup_config;
        //检查是否有正在执行的任务
        $lock = "{$config['path']}backup.lock";
        if(is_file($lock)){
            return false; // 检测到有一个备份任务正在执行，请稍后再试！
        } else {
            //创建锁文件
            file_put_contents($lock, NOW_TIME);
            $this->tmp_data['lock'] = $lock;
            return true;
        }
    }

    protected function get_tables_data($tables){
        $data_list = M()->db()->query('SHOW TABLE STATUS');
        if($tables == ''){
            $tables_arr = array();
            foreach ($data_list as $key => $value) {
                array_push($tables_arr, $value['name']);
            }
        }else{
            $tables_arr = explode(",", $tables);
        }
        $this->tmp_data['tables_arr']      = $tables_arr;
        $this->tmp_data['tables_num']      = count($this->tmp_data['tables_arr']);
        $this->tmp_data['all_tables_data'] = $data_list;
        foreach ($data_list as $key => $value) {
            if( in_array($value['name'],$this->tmp_data['tables_arr']) || count($this->tmp_data['tables_arr']) == 0){
                $export_tables_data[$key] = $value;
            }
        }
        $this->tmp_data['tables_data']     = $export_tables_data;
        return true;
    }

    protected function start_export_database(){

        $file = array(
            'name' => date('Ymd-His', NOW_TIME),
            'part' => 1,
        );
        $this->tmp_data['filename'] = $file['name'];


        $config = $this->_backup_config;
        // 创建备份文件
        $Database = new Database($file, $config);
        if(false !== $Database->create()){
            // 备份指定表
            $start = 0;
            foreach ($this->tmp_data['tables_arr'] as $table) {
                $start = $Database->backup($table, $start);
                while (0 !== $start) {
                    if (false === $start) { // 出错
                        return false;
                    }
                    $start = $Database->backup($table, $start[0]);
                }
            }
            // 备份完成，删除锁定文件
            unlink($this->tmp_data['lock']);
            return true;
        }else{
            return false;
        }
    }

    protected function is_filename_pass($filename){
        // 是否真实存在，目前不做判断
        $this->tmp_data['filename'] = $filename;
        return true;
    }

    /**
     * 还原数据库
     */
    protected function start_import_database(){
       // 初始化
        $name  = $this->tmp_data['filename']. '-*.sql*';
        $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
        $files = glob($path);
        $list  = array();
        foreach($files as $name){
            $basename = basename($name);
            $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
            $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
            $list[$match[6]] = array($match[6], $name, $gz);
        }
        ksort($list);

        // 检测文件正确性
        $last = end($list);
        if(count($list) === $last[0]){
            foreach ($list as $item) {
                $config = array(
                    'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                    'compress' => $item[2]
                );
                $Database = new Database($item, $config);
                $start = $Database->import(0);

                // 循环导入数据
                while (0 !== $start) {
                    if (false === $start) { // 出错
                        return false;
                    }
                    $start = $Database->import($start[0]);
                }
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     ***********************
     * 记录方法
     ***********************
     */
    protected function _after_insert($data, $options) {
        // $id = $this->getLastInsID();
        // admin_log('AdminDatabase',self::ADMIN_ADD,$id,admin_session_admin_id(),'','',$data);
    }

}