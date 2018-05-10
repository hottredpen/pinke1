<?php
namespace Common\Controller;
use Common\Util\Upload\UploadObject;
use Common\Controller\CommonBaseController;
class FileBaseController extends CommonBaseController {
	public function _initialize() {
        parent::_initialize();
    }

    public function webUploader(){
        $webuploader_post = $_FILES;
        $user_id = common_session_user_id();
        return $this->uploadImgBase('webuploader','addimg',$user_id,'file');
    }


    /**
     * 上传
     * @return [type] [description]
     */
    public function uploadImgBase($type="kindeditor",$act="addimg",$uid=0,$fileinput='mypic'){
        $act=strtolower($act);

        $type_data = M('admin_uploadconfig')->field('typename')->select();
        $type_arr = array();
        foreach ($type_data as $key => $value) {
            array_push($type_arr, $value['typename']);
        }

        if(!in_array(strtolower($type), $type_arr)){

            $arr = array(
                'code'   => 400,
                'msg'    =>"没有该类型的上传方法".$type
            );
            echo json_encode($arr);
            exit;
        }

        // $fileinput = $type=="kindeditor" ? "upfile" :"mypic";


        if(!$_FILES && $act=="addimg"){
            echo " ";
            exit;
        }
        $user_id   = common_session_user_id();
        $uploadObj = new UploadObject($type,$_FILES,$user_id,$fileinput);
        if($act=="addimg"){
            $res=$uploadObj->upFile();
            if($res['error']==1){
                $arr = array(
                    'code'   => 400,
                    'msg'    => $res['info']
                );
                echo json_encode($arr);
            }else{

                $res_data = array(
                    'fileid'    => $res['fileid'],
                    'name'      => $res['oldname'],
                    'oldname'   => $res['oldname'],
                    'pic'       => $res['newname'],
                    'size'      => $res['filesize'],
                    'width'     => $res['width'],
                    'height'    => $res['height'],
                    'sub_url'   => "/".$res['sub_url'],
                    'scale_url' => "/".$res['scale_url'],
                    'befor_url' => "/".$res['befor_url'],
                    'url'       => "/".$res['url'],
                    'ext'       => $res['ext']
                );
                $arr = array(
                    'code'      => 200,
                    'msg'       =>'上传成功',
                    'data'      => $res_data
                );
                echo json_encode($arr);
            }
        }else if($act=="delimg"){
            $filename = $_POST['imagename'];
            $res= $uploadObj->delFile($filename);
            if($res['error']==1){
                $arr = array(
                    'code'   => 400,
                    'msg'    => $res['info']
                );
                echo json_encode($arr);
            }else{
                $arr = array(
                    'code'   => 200,
                    'msg'    =>'删除成功'
                );
                echo json_encode($arr);
            }
        }
    }
}
