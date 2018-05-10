<?php
/**
 * admin模块的映射部分
 */
namespace Admin\Controller;

class AdminController extends BackController{

    protected function _initialize() {
        parent::_initialize();
        $this->action_list =  array(
            'setting'                 => 'AdminSetting/setting',
            'saveSetting'             => 'AdminSetting/saveSetting',
            
            // Admin
            'index'                   => 'Admin/index',
            'addAdmin'                => 'Admin/addAdmin',
            'editAdmin'               => 'Admin/editAdmin',
            'createAdmin'             => 'Admin/createAdmin',
            'updateAdmin'             => 'Admin/updateAdmin',
            'deleteAdmin'             => 'Admin/deleteAdmin',
            
            // AdminMenu
            'menu'                    => 'AdminMenu/menu',
            'addAdminMenu'            => 'AdminMenu/addAdminMenu',
            'editAdminMenu'           => 'AdminMenu/editAdminMenu',
            'createAdminMenu'         => 'AdminMenu/createAdminMenu',
            'updateAdminMenu'         => 'AdminMenu/updateAdminMenu',
            'ajaxAdminMenu'           => 'AdminMenu/ajaxAdminMenu',
            'deleteAdminMenu'         => 'AdminMenu/deleteAdminMenu',
            
            // AdminConfig
            'adminConfig'             => 'AdminConfig/adminConfig',
            'addAdminConfig'          => 'AdminConfig/addAdminConfig',
            'editAdminConfig'         => 'AdminConfig/editAdminConfig',
            'createAdminConfig'       => 'AdminConfig/createAdminConfig',
            'updateAdminConfig'       => 'AdminConfig/updateAdminConfig',
            'deleteAdminConfig'       => 'AdminConfig/deleteAdminConfig',
            
            // AdminUploadconfig
            'uploadconfig'            => 'AdminUploadconfig/uploadconfig',
            'addAdminUploadconfig'    => 'AdminUploadconfig/addAdminUploadconfig',
            'editAdminUploadconfig'   => 'AdminUploadconfig/editAdminUploadconfig',
            'createAdminUploadconfig' => 'AdminUploadconfig/createAdminUploadconfig',
            'updateAdminUploadconfig' => 'AdminUploadconfig/updateAdminUploadconfig',
            'deleteAdminUploadconfig' => 'AdminUploadconfig/deleteAdminUploadconfig',

            // Cache
            'cache'                   => 'Cache/cache',
            'cacheclear'              => 'Cache/cacheclear',
            'qclear'                  => 'Cache/qclear',

            // AdminLog
            'adminlog'                => 'AdminLog/adminlog',

            // AdminGroup
            'admingroup'              => 'AdminGroup/admingroup',
            'addAdminGroup'           => 'AdminGroup/addAdminGroup',
            'editAdminGroup'          => 'AdminGroup/editAdminGroup',
            'createAdminGroup'        => 'AdminGroup/createAdminGroup',
            'updateAdminGroup'        => 'AdminGroup/updateAdminGroup',
            'deleteAdminGroup'        => 'AdminGroup/deleteAdminGroup',

            // AdminPlugin
            'plugin'                  => 'AdminPlugin/plugin',
            'install_plugin'          => 'AdminPlugin/install_plugin',
            'uninstall_plugin'        => 'AdminPlugin/uninstall_plugin',
            'plugin_config'           => 'AdminPlugin/plugin_config',
            'update_plugin_config'    => 'AdminPlugin/update_plugin_config',

            'module'                  => 'AdminModule/module',
            'before_install_module'   => 'AdminModule/before_install_module',
            'before_uninstall_module' => 'AdminModule/before_uninstall_module',
            'install_module'          => 'AdminModule/install_module',
            'uninstall_module'        => 'AdminModule/uninstall_module',

            // AdminDatabase
            'adminDatabase'           => 'AdminDatabase/adminDatabase',
            'exportDatabaseConfirm'   => 'AdminDatabase/exportDatabaseConfirm',
            'exportDatabase'          => 'AdminDatabase/exportDatabase',
            'db_list'                 => 'AdminDatabase/db_list',
            'importConfirm'           => 'AdminDatabase/importConfirm',
            'importDatabase'          => 'AdminDatabase/importDatabase',

        );
    }
}