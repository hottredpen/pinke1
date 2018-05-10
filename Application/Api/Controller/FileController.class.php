<?php 
namespace Api\Controller;
use Common\Controller\FileBaseController;
/**
 * 文件上传接口,todo待优化
 */
class FileController extends FileBaseController {

    public function _initialize() {
        parent::_initialize();
    }

    public function uploadconfig(){
		$catid       = I('catid',0,'intval');
		$from_module = I('from_module',"",'trim');
        if($catid == 0){
            IS_AJAX && $this->ajaxReturn(0,'请先选择具体分类');
        }
        $configData = M('admin_uploadconfig')->where(array('catid'=>array("like","%,".$catid.",%"),'from_module'=>$from_module))->find();
        if(!$configData){
            IS_AJAX && $this->ajaxReturn(0,'请到附件上传配置处，添加该分类下的缩略图大小配置');
        }
        if($configData['upload_return_type'] == -1 || $configData['upload_return_type'] == 1){
            $width    = $configData['sub_width'];
            $height   = $configData['sub_height'];
            $autosize = 0;
        }
        if($configData['upload_return_type'] == 2){
            $width    = $configData['sub_width'];
            $height   = $configData['sub_height'];
            $autosize = 0;
        }
        if($configData['upload_return_type'] == 0){
            $width    = 480;
            $height   = 48;
            $autosize = 1;
        }

        $returnData['uploadtype'] = $configData['typename'];
        $returnData['width']      = $width;
        $returnData['height']     = $height;
        $returnData['autosize']   = $autosize;
        
        IS_AJAX && $this->ajaxReturn(1,'ok',$returnData);
    }

}