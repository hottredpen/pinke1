<?php
namespace Cms\Model;
use Think\Model;
class CmsProductModel extends Model{

    const ADMIN_ADD      = 11;//管理员添加
    const ADMIN_SAVE     = 12;//管理员修改
    const ADMIN_DEL   = 13;//管理员删除

    private $pic_ids;

    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员添加
        array('addtime','time',self::ADMIN_ADD,'function'),
        array('cover_ids','set_cover_ids',self::ADMIN_ADD,'callback'),
        array('cover_id','set_cover_id',self::ADMIN_ADD,'callback'),
        array('content','common_filter_editor_content',self::ADMIN_ADD,'function'),
        //管理员修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('cover_ids','set_cover_ids',self::ADMIN_SAVE,'callback'),
        array('cover_id','set_cover_id',self::ADMIN_SAVE,'callback'),
        array('content','common_filter_editor_content',self::ADMIN_SAVE,'function'),


    );

    protected $_validate = array(

        // 添加
        array('title', 'is_notempty_pass', '标题不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('cover_ids', 'get_cover_ids', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),

        // 修改
        array('title', 'is_notempty_pass', '标题不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('cover_ids', 'get_cover_ids', 'callback_true', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),

    );

    protected function get_cover_ids($cover_ids){
        $this->pic_ids = implode(",", $cover_ids);

        return true;
    }

    protected function set_cover_ids(){
        $ids_arr = array_filter(explode(",", $this->pic_ids));
        return implode(",", $ids_arr);
    }

    protected function set_cover_id(){
        $ids_arr = array_filter(explode(",", $this->pic_ids));
        return (int)$ids_arr[0];
    }

}