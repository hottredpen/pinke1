<?php
namespace Common\Controller;
use Common\Util\Upload\UploadObject;
use Common\Controller\CommonBaseController;
class FileBaseController extends CommonBaseController {
	public function _initialize() {
        parent::_initialize();
    }
    /**
     * 上传
     * @return [type] [description]
     */
    public function uploadImgBase($type="kindeditor",$act="addimg",$uid=0){
        $act=strtolower($act);

        $type_data = M('admin_uploadconfig')->field('typename')->select();
        $type_arr = array();
        foreach ($type_data as $key => $value) {
            array_push($type_arr, $value['typename']);
        }

        if(!in_array(strtolower($type), $type_arr)){

            $arr = array(
                'status' =>0,
                'msg'    =>"没有该类型的上传方法".$type
            );
            echo json_encode($arr);
            exit;
        }

        $fileinput = $type=="kindeditor" ? "upfile" :"mypic";


        if(!$_FILES && $act=="addimg"){
            echo " ";
            exit;
        }
        if($this->visitor->info["id"]==0){
            $user_id = (int)$uid;
        }else{
            $user_id = $this->visitor->info["id"];
        }
        $uploadObj = new UploadObject($type,$_FILES,$user_id,$fileinput);
        if($act=="addimg"){
            $res=$uploadObj->upFile();
            if($res['error']==1){
                //echo $type.$act;
                //echo $res['info'];
                $arr = array(
                    'error'  =>1,
                    'state'  => 'SUCCESS',
                    'status' =>0,
                    'message'=>$res['info'],
                    'msg'    =>$res['info']
                );
                echo json_encode($arr);
            }else{
                if($type=="banner"){
                    update_shop_html($user_id);
                }

                $arr = array(
                    'error'     =>0,
                    'status'    =>1,
                    'state'     => 'SUCCESS',
                    'msg'       =>'上传成功',
                    'message'   =>'上传成功',
                    'fileid'    =>$res['fileid'],
                    'name'      =>$res['oldname'],
                    'oldname'   =>$res['oldname'],
                    'pic'       =>$res['newname'],
                    'size'      =>$res['filesize'],
                    'width'     =>$res['width'],
                    'height'    =>$res['height'],
                    'sub_url'   =>"/".$res['sub_url'],
                    'scale_url' =>"/".$res['scale_url'],
                    'befor_url' =>"/".$res['befor_url'],
                    'url'       =>"/".$res['url'],
                    'ext'       =>$res['ext']
                );
                echo json_encode($arr);
            }
        }else if($act=="delimg"){
            $filename = $_POST['imagename'];
            $res= $uploadObj->delFile($filename);
            if($res['error']==1){
                //echo $res['info'];
                $arr = array(
                    'status' =>0,
                    'msg'    =>$res['info']
                );
                echo json_encode($arr);
            }else{
                //echo "1";
                $arr = array(
                    'status' =>1,
                    'msg'    =>'删除成功'
                );
                echo json_encode($arr);
            }
        }
    }
}
