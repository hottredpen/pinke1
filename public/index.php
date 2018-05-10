<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, token");
header("Access-Control-Allow-Method:PUT,POST,GET,DELETE,OPTIONS");
header('X-Powered-By:3.2.1');
header('Content-Type:application/json;charset=utf-8');
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//当前目录路径
define('SITE_PATH', getcwd() . '/');
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',false);
// 开启微信调试模式
define('WEIXIN_DEBUG',false);

// 当前系统是否是demo，（demo情况下无法进行增删改）
define('IS_DEMO',false);

// 定义应用目录
define('APP_PATH','./../Application/');

require './../vendor/autoload.php';
// 引入ThinkPHP入口文件
require './../ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
