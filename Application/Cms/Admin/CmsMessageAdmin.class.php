<?php
// +----------------------------------------------------------------------
// | 品客PHP框架 [ pinkePHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 浙江蓝酷网络科技有限公司 [ http://www.lankuwangluo.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://www.pinkephp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
namespace Cms\Admin;

class CmsMessageAdmin extends CmsBaseAdmin {

    protected function _initialize(){
        parent::_initialize();
    }

    public function webmsg(){
        $p         = I('p',1,'intval');
        $page_size = 15;
        $start     = ($p-1)*$page_size;
        $map       = $this->getMap();
        $order     = $this->getOrder();
        $order     = $order == "" ? " id desc " : $order;

    	$data_list = M('cms_post')->where($map)->limit($start.",".$page_size)->order($order)->select();
        $data_num  = M('cms_post')->where($map)->count();

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('网站留言列表')
                ->setSearch(array('name'=>'用户姓名','phone'=>'手机','email'=>'邮箱','message'=>'留言内容','ip'=>'ip地址'),'',U('admin/cms/webmsg'))
                ->ajax_url(U('Cms/ajaxCmsPost'))
                ->addOrder('id,name,phone,email,message,ip,addtime,status')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('name', '用户姓名')
                ->addTableColumn('phone', '手机')
                ->addTableColumn('email', '邮箱')
                ->addTableColumn('message', '留言内容')
                ->addTableColumn('ip', 'IP')
                ->addTableColumn('addtime', '留言时间','time')
                ->addTableColumn('status', '处理情况', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsPost','data-itemname'=>'网站留言'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }

    public function servicemsg(){
        $p         = I('p',1,'intval');
        $page_size = 15;
        $start     = ($p-1)*$page_size;
        $map       = $this->getMap();
        $order     = $this->getOrder();
        $order     = $order == "" ? " id desc " : $order;

        $data_list = M('cms_service_post')->where($map)->limit($start.",".$page_size)->order($order)->select();
        $data_num  = M('cms_service_post')->where($map)->count();

        $builder = D('Common/List','Builder');
        $builder->theme('one')->setMetaTitle('维修反馈列表')
                ->ajax_url(U('Cms/ajaxCmsServicePost'))
                ->setSearch(array('orderno'=>'订单号','phone'=>'手机','message'=>'留言内容','ip'=>'ip地址'),'',U('admin/cms/servicemsg'))
                ->addOrder('id,orderno,phone,message,ip,addtime,status')
                ->addTableColumn('id', 'ID')
                ->addTableColumn('orderno', '订单号')
                ->addTableColumn('phone', '手机')
                ->addTableColumn('message', '留言内容')
                ->addTableColumn('ip', 'IP')
                ->addTableColumn('addtime', '留言时间','time')
                ->addTableColumn('status', '处理情况', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)
                ->setPage($data_num,$page_size)
                ->addRightButton('delete_confirm',array('data-action'=>'deleteCmsServicePost','data-itemname'=>'维修反馈'))
                ->assign_builder()
                ->admindisplay('Common@builder:ListBuilder');
    }


}