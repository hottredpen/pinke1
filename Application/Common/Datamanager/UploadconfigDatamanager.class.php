<?php
namespace Common\Datamanager;

class UploadconfigDatamanager {

    public function getConfigData_by_typename($typename){
        $configData  = M('admin_uploadconfig')->where(array('typename'=>$typename))->find();
        if(!$configData){
            $configData['error_info'] = '请到附件上传配置处，添加该分类下的缩略图大小配置';
        }
        if($configData['upload_return_type'] == -1 || $configData['upload_return_type'] == 1){
            $configData['width']    = $configData['sub_width'];
            $configData['height']   = $configData['sub_height'];
            $configData['autosize'] = 0;
        }
        if($configData['upload_return_type'] == 2){
            $configData['width']    = $configData['sub_width'];
            $configData['height']   = $configData['sub_height'];
            $configData['autosize'] = 0;
        }
        if($configData['upload_return_type'] == 0){
            $configData['width']    = 480;
            $configData['height']   = 48;
            $configData['autosize'] = 1;
        }
        return $configData;
    }


}