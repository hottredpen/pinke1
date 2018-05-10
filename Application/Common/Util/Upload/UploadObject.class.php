<?php
namespace Common\Util\Upload;
class UploadObject{


    private $maxSize;
    private $maxSizeError;
    private $allowext = array();
    private $extError;
    private $savePath;
    private $uploadType;
    private $file;
    private $file_model;
    private $subSize;
    private $fileid;
    private $is_hold_old_data;
    private $newname;


    private $sub_width  = 0;
    private $sub_height = 0;
    private $sub_type   = 0;//0、不裁剪 1、居中裁剪 2、左上角裁剪 3、右下角裁剪

    private $scale_width  = 0;
    private $scale_height = 0;
    private $scale_type   = 0;//0、不放缩 1、放缩不填充 2、放缩填充 3、变形放缩


    private $upload_return_type = 0;//0、原图返回 1、裁剪图返回 2、放缩图返回

    // /* 水印相关常量定义 */
    // const IMAGE_WATER_NORTHWEST =   1 ; //常量，标识左上角水印
    // const IMAGE_WATER_NORTH     =   2 ; //常量，标识上居中水印
    // const IMAGE_WATER_NORTHEAST =   3 ; //常量，标识右上角水印
    // const IMAGE_WATER_WEST      =   4 ; //常量，标识左居中水印
    // const IMAGE_WATER_CENTER    =   5 ; //常量，标识居中水印
    // const IMAGE_WATER_EAST      =   6 ; //常量，标识右居中水印
    // const IMAGE_WATER_SOUTHWEST =   7 ; //常量，标识左下角水印
    // const IMAGE_WATER_SOUTH     =   8 ; //常量，标识下居中水印
    // const IMAGE_WATER_SOUTHEAST =   9 ; //常量，标识右下角水印

    function __construct($uploadType="talentpic",$file,$uid=0,$fileinput="mypic") {
        $this->uploadType = strtolower($uploadType);
        $this->file       = $file;
        $this->uid        = $uid;
        $this->file_model = M("File");
        $this->fileinput  = $fileinput;
        $this->init();
	}
    private function getThinkThumbTypeId($type){
        switch ($type) {
            case 1://居中裁剪类型 IMAGE_THUMB_CENTER
                $retypeid =  3;
                break;
            case 2://左上角裁剪类型 IMAGE_THUMB_NORTHWEST
                $retypeid =  4;
                break;
            case 3://右下角裁剪类型 IMAGE_THUMB_SOUTHEAST
                $retypeid =  5;
                break;
        }
        return $retypeid;
    }

    private function getThinkScaleTypeId($type){
        switch ($type) {
            case 1: //等比例缩放类型 IMAGE_THUMB_SCALE
                $retypeid =  1;
                break;
            case 2://缩放后填充类型 IMAGE_THUMB_FILLED
                $retypeid =  2;
                break;
            case 3://变形缩放类型 IMAGE_THUMB_FIXED
                $retypeid =  6;
                break;
        }
        return $retypeid;
    }

    public function init(){

        $file = $this->file;
        $this->oldname  = $file[$this->fileinput]['name'];
        $this->filesize = $file[$this->fileinput]['size'];
        $this->type     = $file[$this->fileinput]['type'];
        $this->tmpfile  = $file[$this->fileinput]['tmp_name'];

        $this->getExt($this->oldname);

        $itemConfig = M("admin_uploadconfig")->where(array("typename"=>$this->uploadType))->find();

        
        $this->maxSize            = (int)$itemConfig['maxsize']*1024000 + 200000;//额外增加一些
        $this->maxSizeError       = '大小不能超过'.$this->maxSize.'M';
        $this->extError           = $itemConfig['allowext_errorinfo'];
        
        $this->sub_type           = $itemConfig['sub_type'];
        $this->sub_width          = $itemConfig['sub_width'];
        $this->sub_height         = $itemConfig['sub_height'];
        $this->scale_type         = $itemConfig['scale_type'];
        $this->scale_width        = $itemConfig['scale_width'];
        $this->scale_height       = $itemConfig['scale_height'];
        $this->is_hold_old_data   = $itemConfig['is_hold_old_data'];
        $this->upload_return_type = $itemConfig['upload_return_type'];
        $this->newname            = $this->getNewName_retask();
        //获取允许的格式
        $ext_arr            = explode("|", $itemConfig["allowext"]);
        $this->allowext     = array();
        foreach ($ext_arr as $key => $value) {
            array_push($this->allowext,".".$value);
        }

        if($itemConfig['save_path']!=""){
            $this->savePath = $itemConfig['save_path'];
            $this->savePath = str_replace("{Ym}",date("Ym"),$this->savePath);
            $this->savePath = str_replace("{typename}",$this->uploadType,$this->savePath);
        }else{
            $this->savePath     = "data/uploads/".date("Ym")."/".$this->uploadType."/";
        }

        if(!is_dir($this->savePath)) {
            mkdir($this->savePath, 0755, true);
        }

    }
    private function getExt($name){
        $nameArr    = explode(".",$name);
        $this->ext  = ".".strtolower($nameArr[count($nameArr)-1]);
    }

    public function checkFile(){

        $resFileExt  = $this->checkFileExt();
        if(isset($resFileExt['error']) && $resFileExt['error']==1){
            return $resFileExt;
        }

        $resFileSize = $this->checkFileSize();
        if(isset($resFileSize['error']) && $resFileSize['error']==1){
            return $resFileSize;
        }

        $resNotScript = $this->_check_file_not_script();
        if(isset($resNotScript['error']) && $resNotScript['error']==1){
            return $resNotScript;
        }

    }

    private function _check_file_not_script(){
        if(!in_array(trim($this->ext,"."), array("jpg","png","jpeg","bmp","gif","xls","xlsx","zip","rar")) ){
            return array("error"=>0,"info"=>'其他的格式目前不检测如"7z","doc","docx"');//jpg png bmp jpeg反正后面会进行裁剪，也直接先放它过去
        }
        $filename = $this->tmpfile;
        //为图片的路径可以用d:/upload/11.jpg等绝对路径
        $file = fopen($filename, "rb");
        $bin  = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        $fileType = '';
        switch ($typeCode) {
            case 255216: $fileType = 'jpg|jpeg'; break;
            case 7173: $fileType   = 'gif'; break;
            case 6677: $fileType   = 'bmp'; break;
            case 13780: $fileType  = 'png'; break;

            case 208207 : $fileType   = 'doc|xls'; break;
            case 8085   : $fileType   = 'docx'; break;
            case 8075   : $fileType   = 'xlsx|zip'; break;

            case 8297: $fileType   = 'rar'; break;
            default: $fileType     = 'unknown';
        }
        if($fileType == "unknown" ||  !in_array(trim($this->ext,"."), explode("|", $fileType)) ){
            return array("error"=>1,"info"=>'不允许的文件格式或被篡改过的文件格式');
        }
        return array("error"=>0,"info"=>'这是一个'.$fileType.' file:'.$typeCode);
    }

    public function upFile(){
        $res = $this->checkFile();

        if($res['error']==0 && in_array(trim($this->ext,"."), array("bmp","jpeg","gif","jpg","png"))){

            //裁剪
            if($this->sub_type>0 && $this->sub_width>0 && $this->sub_height>0){
                $imageObj1 = new \Think\Image(); 
                $imageObj1->open($this->tmpfile);
                if($this->upload_return_type==-1){//裁剪图替换原图
                    $imageObj1->thumb($this->sub_width,$this->sub_height,$this->getThinkThumbTypeId($this->sub_type))->save($this->savePath.$this->newname);
                }else{
                    $imageObj1->thumb($this->sub_width,$this->sub_height,$this->getThinkThumbTypeId($this->sub_type))->save($this->savePath."sub_".$this->newname);
                }
            }
            //缩放
            if($this->scale_type>0 && $this->scale_width>0 && $this->scale_height>0){
                $imageObj2 = new \Think\Image(); 
                $imageObj2->open($this->tmpfile);
                $imageObj2->thumb($this->scale_width,$this->scale_height,$this->getThinkScaleTypeId($this->scale_type))->save($this->savePath."scale_".$this->newname);
            }

            if($this->upload_return_type==-1){
                $res_up   = true;
            }else{
                //为防止图片木马，所有原图都进行等比裁剪（目前除gif外）
                if(in_array(trim($this->ext,"."), array("gif"))){
                    //todo 安装PHP中的Imagick模块可解决
                    $imageObj3 = new \Think\Image(); 
                    $imageObj3->open($this->tmpfile);
                    $scale_width = $imageObj3->width(); // 返回图片的宽度
                    $scale_height = $imageObj3->height(); // 返回图片的高度
                    $imageObj3->thumb($scale_width,$scale_height,$this->getThinkScaleTypeId(1))->save($this->savePath.$this->newname);
                    $res_up   = true;
                }else{
                    if($this->is_hold_old_data){
                        $res_up = move_uploaded_file($this->tmpfile, $this->savePath.$this->newname);
                    }else{
                        $imageObj3 = new \Think\Image(); 
                        $imageObj3->open($this->tmpfile);
                        $scale_width = $imageObj3->width(); // 返回图片的宽度
                        $scale_height = $imageObj3->height(); // 返回图片的高度
                        $imageObj3->thumb($scale_width,$scale_height,$this->getThinkScaleTypeId(1))->save($this->savePath.$this->newname);
                        $res_up   = true;
                    }
                }
            }
            
            $returnSubUrl   = $this->savePath."sub_".$this->newname;
            $returnScaleUrl = $this->savePath."scale_".$this->newname;
            $returnBeforUrl = $this->savePath.$this->newname;

            switch ($this->upload_return_type) {
                case 0:
                    $returnUrl = $this->savePath.$this->newname;
                    break;
                case 1:
                    $returnUrl = $this->savePath."sub_".$this->newname;
                    break;
                case 2:
                    $returnUrl = $this->savePath."scale_".$this->newname;
                    break;
                default:
                    $returnUrl = $this->savePath.$this->newname;
                    break;
            }


            if($res_up){
                $this->inputDB();
                return array(
                    "error"     => 0,
                    "fileid"    => $this->fileid,
                    "oldname"   => $this->oldname,
                    "newname"   => $this->newname,
                    "filesize"  => $this->filesize,
                    "width"     => $this->sub_width,
                    "height"    => $this->sub_height,
                    "sub_url"   => $returnSubUrl,
                    "scale_url" => $returnScaleUrl,
                    "befor_url" => $returnBeforUrl,
                    "url"       => $returnUrl,
                    "ext"       => trim($this->ext,".")
                );
            }else{
                return array("error"=>1,"info"=>"上传失败2");
            }

        }elseif($res['error']==0){
                $res_up = move_uploaded_file($this->tmpfile, $this->savePath.$this->newname);
                if($res_up){
                    $this->inputDB();
                    return array("error"=>0,"fileid"=>$this->fileid,"oldname"=>$this->oldname,"newname"=>$this->newname,"filesize"=>$this->filesize,'url'=>$this->savePath.$this->newname,"ext"=>trim($this->ext,"."));
                }else{
                    return array("error"=>1,"info"=>"上传失败4");
                }
        }else{
            return $res;
        }
    }
    public function delFile($filename){
        //return array("error"=>1,"info"=>$filename);
        $filenameArr = explode(".", $filename);
        $justfilename  = $filenameArr[0];
        //从表中查询所属人
        $picData = $this->file_model->where(array("filename"=>$justfilename))->find();
        if($picData["uid"]!=$this->uid && $picData["uid"]>0){
            return array("error"=>1,"info"=>"不能删除别人的图片");
        }else{
            if($picData){
                $res_exists=file_exists($this->savePath.$filename);
                if($res_exists){
                    $res_delfile = @unlink($this->savePath.$filename);
                    if($res_delfile){
                        $res_delLog = $this->file_model->where(array("id"=>$picData["id"]))->delete();
                        return array("error"=>0,"info"=>"删除成功");
                    }else{
                        return array("error"=>1,"info"=>"删除失败");
                    }
                }else{
                    return array("error"=>1,"info"=>"文件不存在");
                }
            }else{
                return array("error"=>1,"info"=>"查无记录");
            }
        }
    }
    public function getNewName_retask(){
        if($this->newname == null){
            $rand              = rand(100, 999);
            $this->newfilename = substr(md5(date("YmdHis") . $rand),2,17);
            $this->newname     = $this->newfilename . $this->ext;
        }
        return $this->newname;
    }
    public function checkFileSize(){
        if($this->filesize > $this->maxSize){
            return array("error"=>1,"info"=>$this->maxSizeError);
        }
    }
    public function checkFileExt(){
        
        if(!in_array(strtolower($this->ext),$this->allowext)){
            return array("error"=>1,"info"=>$this->extError.$this->ext);
        }else{
            return $this->ext;
        }
    }

    public function inputDB(){
        // 插入附件管理表()
        $_data = array();
        $_data['uid']       = $this->uid;
        $_data['item_id']   = 0;
        $_data['media_id']  = "";
        $_data['wechat_url '] = "";
        $_data['url']       = "/".$this->savePath.$this->newname;
        $_data['addtime']   = time();
        $_data['item_id']   = 0;
        $_data['code']      = $this->uploadType;//I('code', '', 'trim');
        $_data['filename']  = $this->newfilename;
        $_data['ext']       = trim($this->ext,".");
        $_data['filesize']  = $this->filesize;
        $_data['width']     = 0; // @todo
        $_data['height']    = 0; // @todo
        $_data['name']      = $this->oldname;
        $_data['sha1']      = sha1_file(realpath (__ROOT__ . $this->savePath.$this->newname));
        $res = $this->file_model->add($_data);
        $this->fileid = $res;
        return $res;
    }





}
