<?php
namespace Cms\HandleObject;
use Admin\HandleObject\BaseHandleObject;
/**
 * 管理员操作对象
 */
class CmsAdminHandleObject extends BaseHandleObject {
    protected $uid;
    function __construct($uid=0) {
        parent::__construct($uid);
        C('CPK_FROM_MODULE_ADMIN',1);
        $this->uid = (int)$uid;
    }
    public function saveCmsSetting(){
        $settingModel = D("Cms/CmsSetting");
        if (!$settingModel->field('setting')->create($_POST,11)){
            return array("error"=>1,"info"=>$settingModel->getError());
        }
        $settingData = $settingModel->getSettingData();
        $allok = true;
        foreach ($settingData as $key => $value) {
            if (!$settingModel->field('name,data')->create($value,12)){
                return array("error"=>1,"info"=>$adminModel->getError());
            }
            $res = $settingModel->where(array("name"=>$value['name']))->save();
            if(!$res){
                $allok = false;
            }
        }
        if($allok){
            return array("error"=>0,"info"=>"保存成功");
        }else{
            return array("error"=>1,"info"=>"保存失败");
        }
    }


    public function createCmsDocument(){
        $documentModel       = D('Cms/CmsDocument');
        $documentArtcleModel = D('Cms/CmsDocumentArticle');
        if (!$documentModel->field('title,category_id,display,list_p_data,listdata,otherdata,cover_id,keywords,description,status')->create($_POST,11)){
            return array("error"=>1,"info"=>$documentModel->getError());
        }
        $res = $documentModel->add();
        $_POST['id']          = $res;
        if (!$documentArtcleModel->field('id,content')->create($_POST,11)){
            return array("error"=>1,"info"=>$documentArtcleModel->getError());
        }
        $res_article = $documentArtcleModel->add();
        if($res && $res_article){
            return array("error"=>0,"info"=>"添加成功",'id'=>$res,'category_id'=>$_POST['category_id']);
        }else{
            return array("error"=>1,"info"=>"添加失败");
        }
    }
    public function saveCmsDocument($id){
        $documentModel       = D('Cms/CmsDocument');
        $documentArtcleModel = D('Cms/CmsDocumentArticle');
        if (!$documentModel->field('id,title,category_id,display,list_p_data,listdata,otherdata,cover_id,keywords,description,create_date,status')->create($_POST,12)){
            return array("error"=>1,"info"=>$documentModel->getError());
        }
        $res = $documentModel->where(array('id'=>$id))->save();
        $_POST['id']          = $res;
        if (!$documentArtcleModel->field('content')->create($_POST,12)){
            return array("error"=>1,"info"=>$documentArtcleModel->getError());
        }
        $res_article = $documentArtcleModel->where(array('id'=>$id))->save();
        if($res && $res_article){
            return array("error"=>0,"info"=>"修改成功",'category_id'=>$_POST['category_id']);
        }else{
            return array("error"=>1,"info"=>"修改失败".$res.$res_article);
        }
    }


    public function deleteCmsDocument($id){
        $res         = M("cms_document")->where(array("id"=>$id))->delete();
        $res_article = M("cms_document_article")->where(array("id"=>$id))->delete();
        if($res){
            return array("error"=>0,"info"=>"删除成功");
        }else{
            return array("error"=>1,"info"=>"删除失败");
        }
    }


}