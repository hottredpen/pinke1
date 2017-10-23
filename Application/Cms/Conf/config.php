<?php
return array(
	'TAGLIB_PRE_LOAD' => 'Cms\TagLib\Cms',// 预先加载标签   

	'URL_ROUTE_RULES' 		=> array( 
		'/^([a-zA-Z]+)\/([a-zA-Z0-9]+)_(\d+)$/'    => 'Cms/:1/:2?id=:3',
		'/^([a-zA-Z]+)\/([a-zA-Z0-9]+)$/'          => 'Cms/:1/:2',
	),
	'REPLACE_CPK_URL' => array(
		'/Cms\/([a-zA-Z]+)\/([a-zA-Z0-9]+)\/id\/([\d]+)/'     => '/{:0}/{:1}_{:2}',
		'/Cms\/([a-zA-Z]+)\/([a-zA-Z0-9]+)/'                  => '/{:0}/{:1}',
	),
);