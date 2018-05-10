<?php
namespace User\Model;
use Think\Model;
class UserModel extends Model{

    const ADMIN_ADD        = 11; // 后台添加用户，密码默认为123456
    const ADMIN_SAVE       = 12; // 后台修改用户，无法修改密码，如果忘记密码只能走前台的忘记密码流程
    const ADMIN_DEL        = 13; // 删除用户

    const USER_LOGIN       = 202; // 用户登录

    const OTHER_USER_ADD         = 1101; // 通过第三方登陆过来时的注册
    const OTHER_TOKEN_UPDATE     = 1102; // 通过第三方登陆过来时的更新token 

    const USER_INFO_UPDATE    = 20002; // 用户更新用户信息



    protected $tmp_data;
    protected $old_data;
    protected $sence_id;


    //字段衍射
    protected $_map = array(
                            
                        );
    //修改插入后自动完成
    protected $_auto = array(
        //管理员后台添加
        array('password','set_password',self::ADMIN_ADD,'callback'),
        array('reg_time','time',self::ADMIN_ADD,'function'),
        array('reg_from','3',self::ADMIN_ADD),  // 来自后台导入
        array('create_time','time',self::ADMIN_ADD,'function'),
        array('update_time','time',self::ADMIN_ADD,'function'),
        array('token','set_token_admin_add',self::ADMIN_ADD,'callback'),

        // 管理员后台修改
        array('update_time','time',self::ADMIN_SAVE,'function'),
        array('password','set_password',self::ADMIN_SAVE,'callback'),

        // 用户登录
        array('update_time','time',self::USER_LOGIN,'function'),
        array('password','set_password',self::USER_LOGIN,'callback'),
        array('token','set_token_by_user_login',self::USER_LOGIN,'callback'),

        // 通过第三方登陆过来时的注册
        array('create_time','time',self::OTHER_USER_ADD,'function'),
        array('update_time','time',self::OTHER_USER_ADD,'function'),
        array('password','set_password',self::OTHER_USER_ADD,'callback'),
        array('last_login_ipaddress','get_client_ip',self::OTHER_USER_ADD,'function'),
        array('nickname','set_nickname_by_telephone',self::OTHER_USER_ADD,'callback'),

        // 通过第三方登陆过来时的更新token 
        array('update_time','time',self::OTHER_TOKEN_UPDATE,'function'),
        array('token','set_token',self::OTHER_TOKEN_UPDATE,'callback'),
        array('android_id','set_android_id',self::OTHER_TOKEN_UPDATE,'callback'),
        array('ios_id','set_ios_id',self::OTHER_TOKEN_UPDATE,'callback'),
        array('last_login_ipaddress','get_client_ip',self::OTHER_TOKEN_UPDATE,'function'),

        // 用户更新用户信息
        array('update_time','time',self::USER_INFO_UPDATE,'function'),
        array('nickname','set_nickname_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('qq','set_qq_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('wechat','set_wechat_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('email','set_email_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('shop_name','set_shop_name_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('shop_address','set_shop_address_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('shop_fixed_phone','set_shop_fixed_phone_by_field_name',self::USER_INFO_UPDATE,'callback'),
        array('shop_fax','set_shop_fax_by_field_name',self::USER_INFO_UPDATE,'callback'),

    );

    protected $_validate = array(
        // 后台添加
        array('username', 'get_sence_id_is_admin_add', 'return_true', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('username', 'is_username_pass', '已经存在用户名', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        array('nickname', 'is_notempty_pass', '昵称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_ADD),
        array('password', 'is_passwordlength_pass', '密码长度必须大于4个字符', self::MUST_VALIDATE,'callback',self::ADMIN_ADD),
        
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('username', 'is_username_pass', '已经存在用户名', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        array('nickname', 'is_notempty_pass', '昵称不能为空', self::MUST_VALIDATE,'function',self::ADMIN_SAVE),
        array('password', 'is_passwordlength_pass', '密码长度必须大于4个字符', self::MUST_VALIDATE,'callback',self::ADMIN_SAVE),
        
        // 用户本地登录
        array('username', 'is_login_username_pass', '用户名或密码错误11', self::MUST_VALIDATE,'callback',self::USER_LOGIN),
        array('password', 'is_passwordlength_pass', '密码长度必须大于4个字符1', self::MUST_VALIDATE,'callback',self::USER_LOGIN),
        array('password', 'is_login_password_pass', '用户名或密码错误', self::MUST_VALIDATE,'callback',self::USER_LOGIN),


        // 通过第三方登陆过来时的注册
        array('origin_id', 'is_origin_id_has_pass', '已经存在的id', self::MUST_VALIDATE,'callback',self::OTHER_USER_ADD),
        array('username', 'is_username_pass', '已经存在的用户名', self::MUST_VALIDATE,'callback',self::OTHER_USER_ADD),
        array('telephone', 'is_telephone_pass', '手机号码错误', self::MUST_VALIDATE,'callback',self::OTHER_USER_ADD),
        array('password', 'is_passwordlength_pass', '密码长度必须大于4个字符', self::MUST_VALIDATE,'callback',self::OTHER_USER_ADD),

        // 通过第三方登陆过来时的更新token
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::OTHER_TOKEN_UPDATE),
        array('token', 'get_token', 'return_true', self::MUST_VALIDATE,'callback',self::OTHER_TOKEN_UPDATE),
        array('android_id', 'get_android_id', 'return_true', self::MUST_VALIDATE,'callback',self::OTHER_TOKEN_UPDATE),
        array('ios_id', 'get_ios_id', 'return_true', self::MUST_VALIDATE,'callback',self::OTHER_TOKEN_UPDATE),


        // 用户更新用户信息
        array('id', 'is_id_pass', '错误的id', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('field_name', 'get_update_field_name', 'return_true', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('cover_id', 'get_cover_id_pass', '头像不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('nickname', 'get_nickname_pass', '昵称不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('qq', 'get_qq_pass', 'qq不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('wechat', 'get_wechat_pass', 'wechat不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('email', 'get_email_pass', 'email不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('shop_name', 'get_shop_name_pass', 'shop_name不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('shop_address', 'get_shop_address_pass', 'shop_address不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('shop_fixed_phone', 'get_shop_fixed_phone_pass', '座机不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),
        array('shop_fax', 'get_shop_fax_pass', '传真不能为空', self::MUST_VALIDATE,'callback',self::USER_INFO_UPDATE),

    );
    protected function _after_insert($data, $options) {
        // $id = $this->getLastInsID();
        // if($this->sence_id == self::ADMIN_ADD){
        //     // todo 转移因为此处无业务（临时方法）
        //     $res_company = D('Company/Company','Service')->reg_common_companay($id,"company_user_id_".$id);
        //     $res_store   = D('Store/Store','Service')->reg_store($id);
        //     $res_finance = D('Finance/FinanceUser','Service')->initFinanceUser($id);
        // }
    }

    public function getOldData(){
        return $this->old_data;
    }

    protected function is_id_pass($id){
        $data = $this->where(array('id'=>$id))->find();
        if($data){
            $this->old_data = $data;
            return true;
        }
        return false;
    }


    protected function is_username_pass($username){
        if($this->old_data['id'] > 0){
            $has = $this->where(array('username'=>$username,'id'=>array('neq',$this->old_data['id'])))->find();
        }else{
            $has = $this->where(array('username'=>$username))->find();
        }
        if($has){
            return false;
        }else{
            return true;
        }
    }


    protected function set_token(){
        return $this->tmp_data['token'];
    }

    protected function is_origin_id_has_pass($origin_id=0){
        $has = $this->where(array('origin_id'=>$origin_id))->find();
        if($has){
            return false;
        }
        return true;
    }
    protected function is_passwordlength_pass($password){
        if(strlen($password) > 4){
            $this->tmp_data['password'] = $password;
            return true;
        }
        return false;
    }

    protected function get_token($token){
        $this->tmp_data['token'] = $token;
        return true;
    }

    protected function set_password(){
        return md5(C('USER_PASSWORD_SALT').$this->tmp_data['password']);
    }

    protected function get_android_id($android_id){
        $this->tmp_data['android_id'] = $android_id;
        return true;
    }

    protected function get_ios_id($ios_id){
        $this->tmp_data['ios_id'] = $ios_id;
        return true;
    }

    protected function set_android_id(){
        if($this->tmp_data['android_id'] != ''){
            return $this->tmp_data['android_id'];
        }
        return $this->old_data['android_id'];
    }

    protected function set_ios_id(){
        if($this->tmp_data['ios_id'] != ''){
            return $this->tmp_data['ios_id'];
        }
        return $this->old_data['ios_id'];
    }

    protected function set_token_by_user_login(){
        return md5(get_client_ip().$this->old_data['id'].time());
    }

    protected function set_token_admin_add(){
        return md5(get_client_ip().time());
    }

    protected function get_sence_id_is_admin_add(){
        $this->sence_id = self::ADMIN_ADD;
        return true;
    }

    protected function is_login_username_pass($username){
        $has = $this->where(array('username'=>$username))->find();
        if($has){
            $this->old_data = $has;
            return true;
        }
        return false;
    }

    protected function is_login_password_pass($password){
        $md5password = md5(C('USER_PASSWORD_SALT').$password);
        if($md5password == $this->old_data['password']){
            return true;
        }
        return false;
    }
    protected function is_telephone_pass($telephone){
        $this->tmp_data['telephone'] = $telephone;
        return true;
    }

    protected function set_nickname_by_telephone(){
        return $this->tmp_data['telephone'];
    }

    protected function get_update_field_name($field_name){
        if($field_name != ''){
            $this->tmp_data['field_name'] = $field_name;
        }else{
            $this->tmp_data['field_name'] = '*';
        }
        return true;
    }

    protected function is_nickname_pass($nickname){
        if($nickname != ''){
            $this->tmp_data['nickname'] = $nickname;
            return true;
        }
        return false;
    }

    protected function get_qq_pass($qq){
        $this->tmp_data['qq'] = $qq;
        return true;
    }

    protected function get_wechat_pass($wechat){
        $this->tmp_data['wechat'] = $wechat;
        return true;
    }

    protected function get_email_pass($email){
        $this->tmp_data['email'] = $email;
        return true;
    }

    protected function get_shop_name_pass($shop_name){
        $this->tmp_data['shop_name'] = $shop_name;
        return true;
    }

    protected function get_shop_address_pass($shop_address){
        $this->tmp_data['shop_address'] = $shop_address;
        return true;
    }

    protected function get_shop_fixed_phone_pass($shop_fixed_phone){
        $this->tmp_data['shop_fixed_phone'] = $shop_fixed_phone;
        return true;
    }

    protected function get_shop_fax_pass($shop_fax){
        $this->tmp_data['shop_fax'] = $shop_fax;
        return true;
    }

    protected function set_cover_id_by_field_name(){
        if($this->tmp_data['field_name'] == 'cover_id'){ // 为了手机的单个赋值
            return $this->tmp_data['cover_id'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['cover_id'];
        }
        return $this->old_data['cover_id'];
    }

    protected function set_nickname_by_field_name(){
        if($this->tmp_data['field_name'] == 'nickname'){ // 为了手机的单个赋值
            return $this->tmp_data['nickname'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['nickname'];
        }
        return $this->old_data['nickname'];
    }

    protected function set_qq_by_field_name(){
        if($this->tmp_data['field_name'] == 'qq'){ // 为了手机的单个赋值
            return $this->tmp_data['qq'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['qq'];
        }
        return $this->old_data['qq'];
    }

    protected function set_wechat_by_field_name(){
        if($this->tmp_data['field_name'] == 'wechat'){ // 为了手机的单个赋值
            return $this->tmp_data['wechat'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['wechat'];
        }
        return $this->old_data['wechat'];
    }

    protected function set_email_by_field_name(){
        if($this->tmp_data['field_name'] == 'email'){ // 为了手机的单个赋值
            return $this->tmp_data['email'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['email'];
        }
        return $this->old_data['email'];
    }

    protected function set_shop_name_by_field_name(){
        if($this->tmp_data['field_name'] == 'shop_name'){ // 为了手机的单个赋值
            return $this->tmp_data['shop_name'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['shop_name'];
        }
        return $this->old_data['shop_name'];
    }

    protected function set_shop_address_by_field_name(){
        if($this->tmp_data['field_name'] == 'shop_address'){ // 为了手机的单个赋值
            return $this->tmp_data['shop_address'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['shop_address'];
        }
        return $this->old_data['shop_address'];
    }

    protected function set_shop_fixed_phone_by_field_name(){
        if($this->tmp_data['field_name'] == 'shop_fixed_phone'){ // 为了手机的单个赋值
            return $this->tmp_data['shop_fixed_phone'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['shop_fixed_phone'];
        }
        return $this->old_data['shop_fixed_phone'];
    }

    protected function set_shop_fax_by_field_name(){
        if($this->tmp_data['field_name'] == 'shop_fax'){ // 为了手机的单个赋值
            return $this->tmp_data['shop_fax'];
        }else if($this->tmp_data['field_name'] == "*"){
            return $this->tmp_data['shop_fax'];
        }
        return $this->old_data['shop_fax'];
    }

    protected function get_cover_id_pass($cover_id){
        $this->tmp_data['cover_id'] = $cover_id;
        return true;
    }

    protected function get_nickname_pass($nickname){
        $this->tmp_data['nickname'] = $nickname;
        return true;
    }

}