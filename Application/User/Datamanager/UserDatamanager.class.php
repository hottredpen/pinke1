<?php
namespace User\Datamanager;
use Common\Datamanager\BaseDatamanager;

class UserDatamanager extends BaseDatamanager{

    protected $replaceOptions = array(
        'id'            => 'u.id',
        'origin_id'     => 'u.origin_id'
    );

    public function getInfoForApp($id){
        $map['id'] = $id;
        $data = $this->_takeFormatDataForApp("data",$map,1,1);
        return $data[0];
    }

    public function getInfoByOriginIdForApp($origin_id=0){
        $map['origin_id'] = (int)$origin_id;
        $data = $this->_takeFormatDataForApp("data",$map,1,1);
        return $data[0];
    }

    protected function _takeFormatDataForApp($type="data",$map=array(),$p=1,$page_size=20,$order=" id desc "){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        foreach ($data as $key => $value) {
            $new_data[$key]['id']           = (int)$value['id'];
            $new_data[$key]['username']     = (string)$value['username'];
            // 基本信息
            $new_data[$key]['nickname']     = (string)$value['nickname'];
            $new_data[$key]['headimg']      = (string)trim(common_trans_sub_image($value['cover_id_url']),'/');
            $new_data[$key]['cover_id']     = (int)$value['cover_id'];
            $new_data[$key]['telephone']    = (string)$value['telephone'];
            $new_data[$key]['qq']           = (string)$value['qq'];
            $new_data[$key]['wechat']       = (string)$value['wechat'];
            $new_data[$key]['email']        = (string)$value['email'];
            // 拓展
            $new_data[$key]['store_id']     = (int)$value['store_id'];
            $new_data[$key]['qrcode']       = (string)$value['qrcode'];
            $new_data[$key]['shop_name']    = (string)$value['shop_name'];
            $new_data[$key]['shop_address'] = (string)$value['shop_address'];
            $new_data[$key]['shop_fixed_phone'] = (string)$value['shop_fixed_phone'];
            $new_data[$key]['shop_fax'] = (string)$value['shop_fax'];
            $new_data[$key]['android_id']   = (string)$value['android_id'];
            $new_data[$key]['ios_id']       = (string)$value['ios_id'];
            // 时间
            $new_data[$key]['create_time']  = Date('Y-m-d H:i:s',$data['user']['create_time']);
            $new_data[$key]['update_time']  = Date('Y-m-d H:i:s',$data['user']['update_time']);
        }
        return $new_data;
    }
    protected function _takeFormatData($type="data",$map=array(),$p=1,$page_size=20,$order=" id desc "){
        $data = $this->_takeData("data",$map,$p,$page_size,$order);
        foreach ($data as $key => $value) {
            $_tmp[$key] = M('user_login')->where(array('user_id'=>$value['id']))->select();

            $data[$key]['is_bind_phone'] = 0;
            $data[$key]['is_bind_email'] = 0;
            $data[$key]['is_bind_qq']    = 0;
            foreach ($_tmp[$key] as $key2 => $value2) {
                if($value2['identity_type'] == "phone" && $value2['is_verified'] == 1){
                    $data[$key]['is_bind_phone'] = 1;
                }
                if($value2['identity_type'] == "email" && $value2['is_verified'] == 1){
                    $data[$key]['is_bind_email'] = 1;
                }
                if($value2['identity_type'] == "qq" && $value2['is_verified'] == 1){
                    $data[$key]['is_bind_qq']    = 1;
                }
            }
        }
        return $data;
    }

    protected function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $searchmap = $this->replaceMap($searchmap);
        $order     = $this->replaceOrder($order);
        $offset    = $this->getOffset($p,$page_size);        

        $map = array();

        //合并覆盖
        if(count($searchmap) > 0){
            $newmap = array_merge($map, $searchmap);
        }else{
            $newmap = array();
        }

        if($type=="data"){
            $list = M("User as u")
                    ->join('left join '.C('DB_PREFIX').'store AS s on s.sys_cid=u.id')
                    ->join('left join '.C('DB_PREFIX').'file AS f on u.cover_id=f.id')
                    ->field('u.*,s.id AS store_id,f.url AS cover_id_url')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();
            // 默认图片
            foreach ($list as $key => $value) {
                if($value['cover_id_url'] == ''){
                    $list[$key]['cover_id_url'] = 'static/images/default_head_img.png';
                }
            }
            
        }else{
            $list = M("User as u")
                    ->field('u.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }
}