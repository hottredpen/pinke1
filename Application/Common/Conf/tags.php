<?php
return array(
     // 添加下面一行定义即可
    // 'app_begin' => array('Behavior\CheckLang'),
    // 如果是3.2.1版本 需要改成
   'app_begin' => array('Common\\Behavior\\CheckLangBehavior',
   						'Common\\Behavior\\HookBehavior',
   						'Common\\Behavior\\ForbidIpBehavior'
   				),
   
);

?>