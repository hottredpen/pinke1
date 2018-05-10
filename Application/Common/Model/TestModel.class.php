<?php
namespace Common\Model;
use Think\Model;
class TestModel extends Model{

    const TEST_ERROR         = 100; // 测试错误数据
    const TEST_SUCCESS       = 200; // 测试正确数据

    //字段衍射
    protected $_map = array(
                                
                        );
    //修改插入后自动完成
    protected $_auto = array(
        // 测试错误数据
        array('create_time','getVisitorId',self::TEST_ERROR,'function'),
        // 测试正确数据
        array('create_time','getVisitorId',self::TEST_SUCCESS,'function'),

    );

    protected $_validate = array(


    );

    public function start(){
        echo "string";
    }

}