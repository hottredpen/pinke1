<?php
return array(
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1',
	'DB_NAME'   => 'xrs',
	'DB_USER'   => 'root',
	'DB_PWD'    => 'root',
	'DB_PORT'   => '3306',
	'DB_PREFIX' => 'gxr_',
	'URL_CASE_INSENSITIVE' => false,

	// 开启语言包功能
	'LANG_SWITCH_ON' => true,   

	'TMPL_PARSE_STRING' => array (
		'__ASSETS__' => __ROOT__.'/assets',
		'__STATIC__' => __ROOT__.'/static',
	),

	'DEFAULT_GROUP'         => '',  // 默认分组
	'DEFAULT_MODULE'        => 'Home', // 默认模块名称
	'DEFAULT_ACTION'        => 'index', // 默认操作名称
	'DEFAULT_THEME'         => 'default',	// 默认模板主题名称
	'BASIC_THEME'   		=> '',
	'URL_HTML_SUFFIX' 		=> '',  // URL伪静态后缀设置
	'URL_MODEL' 			=> 2,
	'URL_HTML_SUFFIX' 		=> '',
	'URL_PATHINFO_DEPR' 	=> '/',
	'VAR_URL_PARAMS' 		=> '',
	'URL_ROUTER_ON'   		=> true, //开启路由
	'MODULE_ALLOW_LIST' => array (
                'Home',
                'Admin',
                'Api'
        ),
	'TMPL_ACTION_ERROR'     => 'Public:error', // 默认错误跳转对应的模板文件
  	'TMPL_ACTION_SUCCESS'   => 'Public:success',

);