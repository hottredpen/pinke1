<?php
namespace Cms\Datamanager;

class AutoHandleDatamanager {

	public function getConfigData($name){

		switch ($name) {

            // 碎片化内容
            case 'createCmsBlock':
                $thisConfig = array(
                    'name'        => '碎片化内容',
                    'action'      => 'create',
                    'field'       => 'name,title,category_id,content,list_p_data,otherdata,status,cover_ids,orderid',
                    'key'         => 11
                );
                break;
            case 'saveCmsBlock':
                $thisConfig = array(
                    'name'        => '碎片化内容',
                    'action'      => 'save',
                    'field'       => 'id,name,title,category_id,content,list_p_data,otherdata,status,cover_ids,orderid',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsBlock':
                $thisConfig = array(
                    'name'        => '碎片化内容',
                    'action'      => 'ajax',
                    'field'       => 'id,status,sort,orderid',
                    'key'         => 12
                );
                break;
            case 'deleteCmsBlock':
                $thisConfig = array(
                    'name'        => '碎片化内容',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 区块分类
            case 'createCmsBlockCategory':
                $thisConfig = array(
                    'name'        => '区块分类',
                    'action'      => 'create',
                    'field'       => 'name,title,pid,status',
                    'key'         => 11
                );
                break;
            case 'saveCmsBlockCategory':
                $thisConfig = array(
                    'name'        => '区块分类',
                    'action'      => 'save',
                    'field'       => 'id,name,title,pid,status',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsBlockCategory':
                $thisConfig = array(
                    'name'        => '区块分类',
                    'action'      => 'ajax',
                    'field'       => 'id,name,title,pid,status,sort',
                    'key'         => 12
                );
                break;
            case 'deleteCmsBlockCategory':
                $thisConfig = array(
                    'name'        => '区块分类',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;


            // 网站留言
            case 'deleteCmsPost':
                $thisConfig = array(
                    'name'        => '网站留言',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;
            // 维修反馈
            case 'deleteCmsServicePost':
                $thisConfig = array(
                    'name'        => '维修反馈',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 产品
            case 'addCmsProduct':
                $thisConfig = array(
                    'name'        => '产品',
                    'action'      => 'add',
                    'field'       => 'name,title,category_id,price,content,keywords,description,list_p_data,status,cover_ids,url_taobao,url_weixin',
                    'key'         => 11
                );
                break;
            case 'saveCmsProduct':
                $thisConfig = array(
                    'name'        => '产品',
                    'action'      => 'save',
                    'field'       => 'id,name,title,category_id,price,content,keywords,description,list_p_data,status,cover_ids,url_taobao,url_weixin',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsProduct':
                $thisConfig = array(
                    'name'        => '产品',
                    'action'      => 'ajax',
                    'field'       => 'id,recommend_index_new,recommend_index_product,status',
                    'key'         => 12
                );
                break;
            case 'deleteCmsProduct':
                $thisConfig = array(
                    'name'        => '产品',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 产品分类
            case 'addCmsProductCategory':
                $thisConfig = array(
                    'name'        => '产品分类',
                    'action'      => 'add',
                    'field'       => 'name,title,pid,status',
                    'key'         => 11
                );
                break;
            case 'saveCmsProductCategory':
                $thisConfig = array(
                    'name'        => '产品分类',
                    'action'      => 'save',
                    'field'       => 'id,name,title,pid,status',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsProductCategory':
                $thisConfig = array(
                    'name'        => '产品分类',
                    'action'      => 'ajax',
                    'field'       => 'id,name,title,pid,status',
                    'key'         => 12
                );
                break;
            case 'deleteCmsProductCategory':
                $thisConfig = array(
                    'name'        => '产品分类',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;
            case 'batchdeleteCmsProductCategory':
                $thisConfig = array(
                    'name'        => '产品分类',
                    'action'      => 'batchdelete',
                    'field'       => 'ids',
                    'key'         => 23
                );
                break;

            // cmsDocument
            case 'ajaxCmsDocument':
                $thisConfig = array(
                    'name'        => '文章',
                    'action'      => 'ajax',
                    'field'       => 'status,recommend_side',
                    'key'         => 12
                );
                break;

            // category
            case 'createCmsCategory':
                $thisConfig = array(
                    'name'        => '分类',
                    'action'      => 'create',
                    'field'       => 'name,action,title,pid,is_page,status',
                    'key'         => 11
                );
                break;
            case 'saveCmsCategory':
                $thisConfig = array(
                    'name'        => '分类',
                    'action'      => 'save',
                    'field'       => 'id,name,action,title,pid,is_page,status',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsCategory':
                $thisConfig = array(
                    'name'        => '分类',
                    'action'      => 'ajax',
                    'field'       => 'id,name,action,title,display_tpl,pid,is_page,sort,status',
                    'key'         => 12
                );
                break;
            case 'deleteCmsCategory':
                $thisConfig = array(
                    'name'        => '分类',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;


            // seo
            case 'addCmsSeo':
                $thisConfig = array(
                    'name'        => 'Seo',
                    'action'      => 'add',
                    'field'       => 'module,action,pid,name,other,title_tpl,keywords_tpl,description_tpl,status',
                    'key'         => 11
                );
                break;
            case 'saveCmsSeo':
                $thisConfig = array(
                    'name'        => 'Seo',
                    'action'      => 'save',
                    'field'       => 'id,module,action,pid,name,other,title_tpl,keywords_tpl,description_tpl,status',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsSeo':
                $thisConfig = array(
                    'name'        => 'Seo',
                    'action'      => 'ajax',
                    'field'       => 'id,module,action,pid,name,other,title_tpl,keywords_tpl,description_tpl,status',
                    'key'         => 12
                );
                break;
            case 'deleteCmsSeo':
                $thisConfig = array(
                    'name'        => 'Seo',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // cms模型
            case 'addCmsType':
                $thisConfig = array(
                    'name'        => 'cms模型',
                    'action'      => 'add',
                    'field'       => 'title,name,icon,status',
                    'key'         => 11
                );
                break;
            case 'saveCmsType':
                $thisConfig = array(
                    'name'        => 'cms模型',
                    'action'      => 'save',
                    'field'       => 'title,name,icon,status',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsType':
                $thisConfig = array(
                    'name'        => 'cms模型',
                    'action'      => 'ajax',
                    'field'       => 'title,name,icon,status',
                    'key'         => 12
                );
                break;
            case 'deleteCmsType':
                $thisConfig = array(
                    'name'        => 'cms模型',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;

            // 模型字段
            case 'addCmsAttribute':
                $thisConfig = array(
                    'name'        => '模型字段',
                    'action'      => 'add',
                    'field'       => 'title,name,doc_type,type,status',
                    'key'         => 11
                );
                break;
            case 'saveCmsAttribute':
                $thisConfig = array(
                    'name'        => '模型字段',
                    'action'      => 'save',
                    'field'       => 'title,name,doc_type,type,status',
                    'key'         => 12
                );
                break;
            case 'ajaxCmsAttribute':
                $thisConfig = array(
                    'name'        => '模型字段',
                    'action'      => 'ajax',
                    'field'       => 'title,name,doc_type,type,status',
                    'key'         => 12
                );
                break;
            case 'deleteCmsAttribute':
                $thisConfig = array(
                    'name'        => '模型字段',
                    'action'      => 'delete',
                    'field'       => 'id',
                    'key'         => 13
                );
                break;
            default:
                // 没有找到
                return false;
                break;

		}

        $thisConfig['model'] = str_replace($thisConfig['action'], "" , $name);

		return $thisConfig;
	}

}