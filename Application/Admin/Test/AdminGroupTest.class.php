<?php
namespace Admin\Test;
use Common\Test\CommonTest;
class AdminGroupTest extends CommonTest{

    function  __construct(){
        $this->_config_data = array(
            'title'                    => '管理员分组模型',
            'is_plugin'                => 0,
            'handle_object'            => '\\Admin\\HandleObject\\AdminAdminHandleObject',
            'module_name'              => 'Admin',
            'controller_name'          => 'AdminGroup',
            // 单元测试
            'units'       => array(
                // 添加管理员分组
                11 => array(
                    'handle_action'  => 'createAdminGroup',
                    'test_title'     => '测试添加管理员分组',
                    'success_assert' => '添加用户组成功',
                    'assert_data'    => array(
                        // test_detail的的数据
                        // 表  字段  预期判断  判断方法  具体方法
                        array('admin_group','row_num','row_num +1 ','function','admin_test_assert_add_1'),
                        array('admin_group','last_id','last_id +1 ','function','admin_test_assert_add_1'),
                        array('admin_group','data.title','title由null变为{{post_data.title}}','function','admin_test_assert_null_to_value'),
                    ),
                    'log_origin_data' => array(
                        array('admin_group',0),
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'log_update_data' => array(
                        array('admin_group',"{{log_data.handle_return_data.id}}"),
                        array('admin_auth',0) // 试验多表记录
                    ),
                    // 对数据的断言 
                    'test_data'      => array(
                        // 对提交字段的
                        0 => array(
                            array("pid",'text','上级部门','上级部门',
                                array(
                                    'assert_title'   => '测试错误的上级部门',
                                    'success_assert' => '错误的上级部门',
                                    'success_value'  => '0',
                                    'error_value'    => '-1'
                                )
                            ),
                            array("title",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试用户组名称不能为空',
                                    'success_assert' => '用户组名称不能为空',
                                    'success_value'  => '测试用户组_{{test.last_id}}',
                                    'error_value'    => ''
                                )
                            ),
                            array("title",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试用户组名不能包含特殊符号',
                                    'success_assert' => '用户组名不能包含特殊符号',
                                    'success_value'  => '测试用户组_{{test.last_id}}',
                                    'error_value'    => '【ss】/?'
                                )
                            ),
                            array("title",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试已有相同的分组名称',
                                    'success_assert' => '已有相同的分组名称',
                                    'success_value'  => '测试用户组_{{test.last_id}}',
                                    'error_value'    => '超级管理员'
                                )
                            )
                        ),
                    )
                ),
                12 => array(
                    'handle_action'  => 'updateAdminGroup',
                    'test_title'     => '测试修改管理员分组',
                    'success_assert' => '修改用户组成功',
                    'assert_data'    => array(
                        // test_detail的的数据
                        // 表  字段  预期判断  判断方法  具体方法
                        array('admin_group','row_num','row_num +0 ','function','admin_test_assert_eq'),
                        array('admin_group','last_id','last_id +0 ','function','admin_test_assert_eq'),
                        array('admin_group','data.title','title由{{origin_data.data.title}}变为{{post_data.title}}','function','admin_test_assert_origin_to_update'),
                    ),
                    'log_origin_data' => array(
                        array('admin_group','{{post_data.id}}'),
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'log_update_data' => array(
                        array('admin_group',"{{post_data.id}}"),
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'test_data'      => array(
                        // 存在某字段不同时，后面逻辑有稍有差异的情况,所以test_data为一个数组
                        0 => array(
                            array("id",'text','id','admin的id',
                                array(
                                    'assert_title'   => '测试错误的id参数',
                                    'success_assert' => '错误的id',
                                    'success_value'  => '{{post_data.id}}',
                                    'error_value'    => '99998'
                                )
                            ),
                            array("pid",'text','上级部门','上级部门',
                                array(
                                    'assert_title'   => '测试错误的上级部门',
                                    'success_assert' => '错误的上级部门',
                                    'success_value'  => '0',
                                    'error_value'    => '-1'
                                )
                            ),
                            array("title",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试用户组名称不能为空',
                                    'success_assert' => '用户组名称不能为空',
                                    'success_value'  => '测试用户组_{{test.last_id}}',
                                    'error_value'    => ''
                                )
                            ),
                            array("title",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试用户组名不能包含特殊符号',
                                    'success_assert' => '用户组名不能包含特殊符号',
                                    'success_value'  => '测试用户组_{{test.last_id}}',
                                    'error_value'    => '【ss】/?'
                                )
                            ),
                            array("title",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试已有相同的分组名称',
                                    'success_assert' => '已有相同的分组名称',
                                    'success_value'  => '测试用户组_{{test.last_id}}',
                                    'error_value'    => '超级管理员'
                                )
                            )
                        )
                    )
                ),
                13 => array(
                    'handle_action'  => 'deleteAdminGroup',
                    'test_title'     => '测试删除管理员',
                    'success_assert' => '删除用户组成功',
                    'assert_data'    => array(
                        array('admin_group','row_num','row_num -1','function','admin_test_assert_sub_1'),
                        array('admin_group','data.title','title由{{origin_data.data.title}}变为null','function','admin_test_assert_null'),
                    ),
                    'log_origin_data' => array(
                        array('admin_group','{{post_data.id}}'), // $post.id
                    ),
                    'log_update_data' => array(
                        array('admin_group','{{post_data.id}}'), // $post.id
                    ),
                    'test_data'      => array(
                        // 存在某字段不同时，后面逻辑有稍有差异的情况,所以test_data为一个数组
                        0 => array(
                            array("id",'text','id','admin的id',
                                array(
                                    'assert_title'   => '测试错误的id',
                                    'success_assert' => '错误的id',
                                    'success_value'  => '{{post_data.id}}',
                                    'error_value'    => '99998'
                                )
                            ),
                        )
                    )
                )
            )
        );
    }
}