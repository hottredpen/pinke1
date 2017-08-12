<?php 
namespace Api\Controller;
use Common\Controller\CommonBaseController;
/**
 * 
 */
class PublicController extends CommonBaseController {

	public function _initialize() {
        parent::_initialize();
    }

	public function checkversion(){

		// 假设的数据库数据
		$data = array(
			'id'            => 10, // 1010
			'verfiy'        => 'asfadd',
			'username'      => 'hottredpen',
			'pub_id'        => 'abcdefghijklmnopqrst-bcdefghijklmnopqqssu',
			'secret_key'    => '5be7534289bb3e325f4c9391990294ff',
		);

		// 最新版本
		$product = array(
			'version' => '1.0.1'
		);

		if($_POST['auth_username'] == $data['username'] && $_POST['secret_key'] == $data['secret_key'] ){
			$this->ajaxReturn(1,'已授权',array('is_authorized'=>1,'version'=>$product['version'],'post_data'=>$_POST));
		}else{
			$this->ajaxReturn(1,'未授权',array('is_authorized'=>0,'version'=>$product['version'],'post_data'=>$_POST));
		}

		$this->ajaxReturn(1,'asdf',array('get_data'=>$_GET,'post_data'=>$_POST));

	}

}