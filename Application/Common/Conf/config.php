<?php
return array(

	'USER_PASSWORD_SALT'  => 'pinkephp', // 用户密码md5的盐

	// 加载扩展配置文件
	'LOAD_EXT_CONFIG' => 'db,pinke', 

	// 数据备份
	'DATA_BACKUP_PATH'           => './data/backup/', // 数据库备份根路径
	'DATA_BACKUP_PART_SIZE'      => '20971520', // 数据库备份卷大小
	'DATA_BACKUP_COMPRESS'       => '1', // 数据库备份文件是否启用压缩
	'DATA_BACKUP_COMPRESS_LEVEL' => '9', // 数据库备份文件压缩级别

	//是否不区分大小写
	'URL_CASE_INSENSITIVE' => false,

	// 开启语言包功能
	'LANG_SWITCH_ON' => true,   

	'TMPL_PARSE_STRING' => array (
		'__ASSETS__' => __ROOT__.'/assets',
		'__STATIC__' => __ROOT__.'/static',
	),
	'TAGLIB_PRE_LOAD' => 'Common\TagLib\BeforeTemplate',// 预先加载标签 

	'DEFAULT_GROUP'         => '',  // 默认分组
	'DEFAULT_MODULE'        => 'Index', // 默认模块名称
	'DEFAULT_ACTION'        => 'index', // 默认操作名称
	'DEFAULT_THEME'         => 'lanku',	// 默认模板主题名称
	'BASIC_THEME'   		=> '',
	'URL_HTML_SUFFIX' 		=> '',  // URL伪静态后缀设置
	'URL_MODEL' 			=> 2,
	'URL_HTML_SUFFIX' 		=> '',
	'URL_PATHINFO_DEPR' 	=> '/',
	'VAR_URL_PARAMS' 		=> '',
	'URL_ROUTER_ON'   		=> true, //开启路由
	'MODULE_ALLOW_LIST' => array (
                'Admin',
                'Api',
                'Cms',
                'User',
                'Weixin',
                'Store',
                'Order',
                'Finance',
                'index',
                'Message',
                'Plugins',
                'Company',
                'Test'
        ),

	// 'URL_MODULE_MAP' => array('Admin'=>'admin_plugin'),//模块映射

	'TMPL_ACTION_ERROR'     => 'Public:error', // 默认错误跳转对应的模板文件
  	'TMPL_ACTION_SUCCESS'   => 'Public:success',

    'APP_SUB_DOMAIN_DEPLOY'   =>    0, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES'    =>    array(   
        //'shop.cpk.com'   => 'Home/Shop',  // shop.cpk.com域名指向Home分组的Shop模块  todo
    ),
	'REPLACE_CPK_DOMAIN' => array(
			//'shop'      =>'@shop.cpk.com/shop' //todo 
	),

    // 插件目录路径
    'PLUGIN_PATH'        => APP_PATH. 'Plugins/',
    'COMPONENTS_PATH'    => __ROOT__. 'static/components/form_builder/',

);