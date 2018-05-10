<?php
namespace Admin\Test;
use Common\Test\CommonTest;
class AdminTest extends CommonTest{
    function  __construct(){
        $this->_config_data = array(
            'title'           => '管理员模型',
            'is_plugin'       => 0,
            'handle_object'   => '\\Admin\\HandleObject\\AdminAdminHandleObject',
            'module_name'     => 'Admin',
            'controller_name' => 'Admin',
            // 单元测试
            'units'       => array(
                // 添加管理员
                11 => array(
                    'handle_action'  => 'createAdmin',
                    'test_title'     => '测试添加管理员',
                    'success_assert' => '添加管理员成功',
                    'assert_data'    => array(
                        // test_detail的的数据
                        // 表  字段  预期判断  判断方法  具体方法
                        array('admin','row_num','row_num +1 ','function','admin_test_assert_add_1'),
                        array('admin','last_id','last_id +1 ','function','admin_test_assert_add_1'),
                        array('admin','data.username','username由null变为{{post_data.username}}','function','admin_test_assert_null_to_value'),
                    ),
                    'log_origin_data' => array(
                        // 记录测试前的数据
                        // table_name cur_id
                        array('admin',0),
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'log_update_data' => array(
                        // 记录测试前的数据
                        // table_name cur_id
                        array('admin',"{{log_data.handle_return_data.id}}"),
                        array('admin_auth',0) // 试验多表记录
                    ),
                    // 对数据的断言 
                    'test_data'      => array(
                        // 对提交字段的
                        0 => array(
                            array("username",'text','管理员名称','管理员名称',
                                array(
                                    'assert_title'   => '测试用户名不为空',
                                    'success_assert' => '用户名不能为空',
                                    'success_value'  => 'admin_{{test.last_id}}',
                                    'error_value'    => ''
                                )
                            ),
                            array("username",'text','管理员名称','管理员名称',
                                array(
                                    'assert_title'   => '测试用户名已存在',
                                    'success_assert' => '用户名已存在',
                                    'success_value'  => 'admin_{{test.last_id}}',
                                    'error_value'    => 'admin'
                                )
                            ),
                            array("username",'text','管理员名称','管理员名称',
                                array(
                                    'assert_title'   => '测试用户名不能包含特殊符号',
                                    'success_assert' => '用户名不能包含特殊符号',
                                    'success_value'  => 'admin_{{test.last_id}}',
                                    'error_value'    => '【ss】/?'
                                )
                            ),
                            array("password",'text','密码','密码',
                                array(
                                    'assert_title'   => '测试密码长度必须大于4个字符',
                                    'success_assert' => '密码长度必须大于4个字符',
                                    'success_value'  => 'admin123',
                                    'error_value'    => 'ddd'
                                )
                            ),
                            array("repassword",'text','确认密码','确认密码',
                                array(
                                    'assert_title'   => '测试两次密码输入不一致',
                                    'success_assert' => '两次密码输入不一致',
                                    'success_value'  => 'admin123',
                                    'error_value'    => 'admin'
                                )
                            ),
                            array("email",'text','邮箱','邮箱',
                                array(
                                    'assert_title'   => '测试错误的邮箱格式',
                                    'success_assert' => '错误的邮箱格式',
                                    'success_value'  => 'hottredpen11@126.com',
                                    'error_value'    => 'admin'
                                )
                            ),
                            array("group",'text','用户组','用户组',
                                array(
                                    'assert_title'   => '测试只能有一位超级管理员',
                                    'success_assert' => '只能有一位超级管理员',
                                    'success_value'  => '14',
                                    'error_value'    => '1'
                                )
                            ),
                        ),
                    )
                ),
                12 => array(
                    'handle_action'  => 'updateAdmin',
                    'test_title'     => '测试修改管理员',
                    'success_assert' => '修改管理员成功',
                    'assert_data'    => array(
                        array('admin','row_num','row_num不变，还是{{origin_data.row_num}}','function','admin_test_assert_eq'),
                        array('admin','last_id','last_id不变，还是{{origin_data.last_id}}','function','admin_test_assert_eq'),
                        array('admin','data.username','username由{{origin_data.data.username}}变为{{post_data.username}}','function','admin_test_assert_origin_to_update'),
                    ),
                    'log_origin_data' => array(
                        array('admin','{{post_data.id}}'), // $post.id
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'log_update_data' => array(
                        array('admin','{{post_data.id}}'), // $post.id
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'test_data'      => array(
                        // 存在某字段不同时，后面逻辑有稍有差异的情况,所以test_data为一个数组
                        0 => array(
                            array("id",'text','id','admin的id',
                                array(
                                    'assert_title'   => '测试错误的id参数',
                                    'success_assert' => '错误的id参数',
                                    'success_value'  => '{{post_data.id}}',
                                    'error_value'    => '99998'
                                )
                            ),
                            array("username",'text','管理员名称','管理员名称',
                                array(
                                    'assert_title'   => '测试用户名不为空',
                                    'success_assert' => '用户名不能为空',
                                    'success_value'  => 'admin_{{test.last_id}}',
                                    'error_value'    => ''
                                )
                            ),
                            array("username",'text','管理员名称','管理员名称',
                                array(
                                    'assert_title'   => '测试用户名已存在',
                                    'success_assert' => '用户名已存在',
                                    'success_value'  => 'admin_{{test.last_id}}',
                                    'error_value'    => 'admin'
                                )
                            ),
                            array("username",'text','管理员名称','管理员名称',
                                array(
                                    'assert_title'   => '测试用户名不能包含特殊符号',
                                    'success_assert' => '用户名不能包含特殊符号',
                                    'success_value'  => 'admin_{{test.last_id}}',
                                    'error_value'    => '【ss】/?'
                                )
                            ),
                            array("password",'text','密码','密码',
                                array(
                                    'assert_title'   => '测试密码长度必须大于4个字符',
                                    'success_assert' => '密码长度必须大于4个字符',
                                    'success_value'  => 'admin123',
                                    'error_value'    => 'ddd'
                                )
                            ),
                            array("repassword",'text','确认密码','确认密码',
                                array(
                                    'assert_title'   => '测试两次密码输入不一致',
                                    'success_assert' => '两次密码输入不一致',
                                    'success_value'  => 'admin123',
                                    'error_value'    => 'admin'
                                )
                            ),
                            array("email",'text','邮箱','邮箱',
                                array(
                                    'assert_title'=>'测试错误的邮箱格式',
                                    'success_assert'=>'错误的邮箱格式',
                                    'success_value'=>'hottredpen11@126.com',
                                    'error_value'=>'admin'
                                )
                            ),
                            array("group",'text','用户组','用户组',
                                array(
                                    'assert_title'=>'测试只能有一位超级管理员',
                                    'success_assert'=>'只能有一位超级管理员',
                                    'success_value'=>'14',
                                    'error_value'=>'1'
                                )
                            ),
                        )
                    )
                ),
                13 => array(
                    'handle_action'  => 'deleteAdmin',
                    'test_title'     => '测试删除管理员',
                    'success_assert' => '删除管理员成功',
                    'assert_data'    => array(
                        array('admin','row_num','row_num -1','function','admin_test_assert_sub_1'),
                        array('admin','data.username','username由{{origin_data.data.username}}变为null','function','admin_test_assert_null'),
                    ),
                    'log_origin_data' => array(
                        array('admin','{{post_data.id}}'), // $post.id
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'log_update_data' => array(
                        array('admin','{{post_data.id}}'), // $post.id
                        array('admin_auth',0) // 试验多表记录
                    ),
                    'test_data'      => array(
                        // 存在某字段不同时，后面逻辑有稍有差异的情况,所以test_data为一个数组
                        0 => array(
                            array("id",'text','id','admin的id',
                                array(
                                    'assert_title'   => '测试错误的id参数',
                                    'success_assert' => '错误的id参数',
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