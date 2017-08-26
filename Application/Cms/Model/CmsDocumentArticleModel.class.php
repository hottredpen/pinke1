<?php
namespace Cms\Model;
use Common\Model\CommonModel;
class CmsDocumentArticleModel extends CommonModel{

    const INFO_ADD  = 11; // 添加
    const INFO_SAVE = 12; // 修改

    //字段衍射
    protected $_map = array(
                            'info'     => 'content', // 添加
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 添加
        array('content','common_filter_editor_content',self::INFO_ADD,'function'),
        array('update_time','time',self::INFO_ADD,'function'),

        // 修改
        array('content','common_filter_editor_content',self::INFO_SAVE,'function'),
        array('update_time','time',self::INFO_SAVE,'function'),
    );

    protected $_validate = array(
        // 添加
        array('content', 'is_notempty_pass', '文章内容不能为空', self::MUST_VALIDATE,'function',self::INFO_ADD),

        // 修改
        array('content', 'is_notempty_pass', '文章内容不能为空', self::MUST_VALIDATE,'function',self::INFO_SAVE),
    );

}