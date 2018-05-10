<?php
namespace Index\Controller;
class BlockController extends ApiBaseController {

    protected function _initialize() {
    	parent::_initialize();
    }


    public function banners(){
        $page      = I('page',1,'intval');
        $pageSize  = I('pageSize',15,'intval');
        $map       = array();

        $data_list = D('Cms/CmsBlock','Datamanager')->getBannerDataForApp($page,$pageSize,$map);
        $data_num  = 0;// D('Cms/CmsBlock','Datamanager')->getNum($map);

        $data                 = array();
        $data['total']        = $data_num;
        $data['per_page']     = $pageSize;
        $data['current_page'] = $page;
        $data['last_page']    = ceil($data_num/$pageSize) > 0 ? ceil($data_num/$pageSize) : 1;
        $data['data']         = $data_list;


        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }










    public function index(){
        $page      = I('page',1,'intval');
        $pageSize  = I('pageSize',15,'intval');
        $map       = array();

        $data_list = D('Cms/CmsDocument','Datamanager')->getData($page,$pageSize,$map);
        $data_num  = D('Cms/CmsDocument','Datamanager')->getNum($map);

        $data                 = array();
        $data['total']        = $data_num;
        $data['per_page']     = $pageSize;
        $data['current_page'] = $page;
        $data['last_page']    = ceil($data_num/$pageSize) > 0 ? ceil($data_num/$pageSize) : 1;
        $data['data']         = $data_list;


        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }

    public function detail(){
        $this->details();
    }
    public function details(){
        $id   = I('id',0,'intval');
        $info = D('Order/OrderOuter','Datamanager')->getInfoWithDetailForApp($id);
        if(!$info){
            $res = array('error'=>400,'info'=>'错误的order_id');
            $this->json_return($res);
        }else{
            $result['code']  = 200;
            $result['msg']   = "成功";
            $result['data']  = $info;
            $this->json($result);
        }
    }
    //获取销售单编号
    public function getOuterOrderNo(){
        $today_order_num  = D('Order/OrderOuter','Datamanager')->getTodayOrderNum();
        $data['user_id']  = common_session_user_id();
        $data['order_no'] = 'KD'.Date('Ymd').sprintf("%'04d",$today_order_num+1);

        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }


    public function save(){
        $id             = I('id',0,'intval');
        $state          = I('state',0,'intval'); // 1为保存，2为下单
        $_POST['order_detail'] = json_decode($_POST['order_detail'], true); // json
        if($state == 1){
            $OrderUserHandleObject = new \Order\HandleObject\OrderUserHandleObject();
            if($id > 0){
                $res = $OrderUserHandleObject->updateOrderOuterDraftByData();
            }else{
                $res = $OrderUserHandleObject->createOuterOrderDraft();
            }
            $this->json_return($res);
        }
        if($state == 2){
            $result['code']  = 400;
            $result['msg']   = "目前只有草稿的状态,无真实的下单";
            $result['data']  = $data;
            $this->json($result);
        }
    }
    /**
     * 发货
     * @return [type] [description]
     */
    public function update(){
        $result['code']  = 400;
        $result['msg']   = "目前只有草稿的状态,无真实的下单";
        $result['data']  = array();
        $this->json($result);
        exit();
        $order_id  = I('id',0,'intval');
        $state     = I('state',0,'intval');

        $OrderUserHandleObject = new \Order\HandleObject\OrderUserHandleObject();
        switch ($state) {
            // 更新草稿(此为撤单todo)
            case 1:


                break;
            // 从草稿到待发货（下单）,如果有定金，则生成定金流水
            case 2:
                $res = $OrderUserHandleObject->outerDraftToWaitByOrderId($order_id);
                $this->json_return($res);
                break;
            // 从待发货到收款中(如果已经收全款则直接完成)
            case 3:
                $res = $OrderUserHandleObject->outerWaitToSend($order_id);
                $this->json_return($res);
                break;
            // 从收款中到完成
            case 4:
                $res = $OrderUserHandleObject->outerGetMoneyToOver($order_id);
                $this->json_return($res);
                break;
        }
    }
    public function delete(){
        $id = I('id',0,'intval');
        $OrderUserHandleObject = new \Order\HandleObject\OrderUserHandleObject();
        $res = $OrderUserHandleObject->deleteOrderOuterByUser();
        $this->json_return($res);
    }
    /**
     * 获取支付记录
     * @return [type] [description]
     */
    public function findPayMessageByOrderId(){
        $order_id        = I('order_id',0,'intval');
        $map['order_id'] = $order_id;
        $data            = D('Order/OrderMoneyLog','Datamanager')->getData(1,1000,$map);

        $result['code']  = 200;
        $result['msg']   = "成功";
        $result['data']  = $data;
        $this->json($result);
    }
    /**
     * 记录支付
     * @return [type] [description]
     */
    public function savePayMessage(){
        $order_id = I('order_id',0,'intval');
        $money    = I('money',0,'floatval');
        $pay_time = I('pay_time',0,'trim');

        $pay_time  = strtotime($pay_time);
        $res_order = D('Order/OrderOuter','Service')->payMoney($order_id,$money,$pay_time);
        $this->json_return($res_order);
    }

    public function viewPDF(){
        $id   = I('id',0,'intval');
        $info = D('Order/OrderOuter','Datamanager')->getInfoWithDetailForApp($id);
        if(!$info){
            $this->json_return(array('code'=>400,'msg'=>'错误的订单号'));
        }
        $user_id      = common_session_user_id();
        $user_info    = D('User/User','Datamanager')->getInfoForApp($user_id);
        $pdf_html     = $this->_return_pdf_html($info,$user_info);
        $filename     = md5($pdf_html);
        $file_info    = $user_info['shop_name'] != "" ? "来自".$user_info['shop_name']."的销售订单" : "销售订单";
        $pdf          = "data/order/pdf/".$filename.".pdf";
        $img_base_str = "data/order/images/".$filename;

        if(file_exists(SITE_PATH.$pdf)){

            if(!file_exists($img_base_str.'_all.png')){
                $image_arr = $this->_pdf2png($pdf,$filename);
                $image_path = $this->_splice_img($image_arr,$img_base_str.'_all.png');
            }

            $result['code']  = 200;
            $result['msg']   = "成功";
            $result['data']  = array('pdf_path'=>$pdf,'image_path'=>$img_base_str.'_all.png','file_info'=>$file_info);
            $this->json($result);
        }else{
            // 生成一个pdf文件
            $mpdf = new Mpdf(array('lang'=>'+aCJK', 'mode' => 'utf-8' ,'format' => 'A4', 'tempDir'=>APP_PATH.'Runtime'));
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->useAdobeCJK = TRUE;  
            $mpdf->autoScriptToLang = true;  
            $mpdf->autoLangToFont = true;  
            $mpdf->WriteHTML($pdf_html);
            $mpdf->Output($pdf);// 'pdf001.pdf'

            $image_arr  = $this->_pdf2png($pdf,$filename);
            $image_path = $this->_splice_img($image_arr,$img_base_str.'_all.png');
            $result['code']  = 200;
            $result['msg']   = "成功";
            $result['data']  = array('pdf_path'=>$pdf,'image_path'=>$image_path,'file_info'=>$file_info);
            $this->json($result);
        }
    }


    private function _pdf2png($pdf,$filename,$page=-1){
        if(!extension_loaded('imagick')){
            return false;
        }
        if(!file_exists($pdf)){
            return false;
        }
        if(!is_readable($pdf)){
            return false;
        }
        $im = new \Imagick();
        $im->setResolution(150,150);
        $im->setCompressionQuality(100);
        if($page==-1)
            $im->readImage(SITE_PATH.$pdf);
        else
            $im->readImage(SITE_PATH.$pdf."[".$page."]");
        foreach ($im as $key => $Var){
            $Var->setImageFormat('png');
            $file_path = 'data/order/images/'.$filename.'_'.$key.'.png';
            if($Var->writeImage(SITE_PATH.$file_path) == true){
                $Return[] = $file_path;
            }
        }
        //返回转化图片数组，由于pdf可能多页，此处返回二维数组。
        return $Return;
    }


    private function _splice_img($imgs = array(),$img_path = ''){
        //自定义宽度
        $width = 1230;
        //获取总高度
        $pic_tall = 0;
        foreach ($imgs as $key => $value) {
            $info = getimagesize($value);
            $pic_tall += $width/$info[0]*$info[1];
        }
        // 创建长图
        $temp = imagecreatetruecolor($width,$pic_tall);
        //分配一个白色底色
        $color = imagecolorAllocate($temp,255,255,255);
        imagefill($temp,0,0,$color);
        $target_img = $temp;
        $source = array();
        foreach ($imgs as $k => $v) {
            $source[$k]['source'] = Imagecreatefrompng($v);
            $source[$k]['size'] = getimagesize($v);
        }
        $num  = 0;
        $tmp  = 0;
        $tmpy = 0; //图片之间的间距
        $count = count($imgs);
        for ($i = 0; $i < $count; $i++) {
            imagecopy($target_img, $source[$i]['source'], $tmp, $tmpy, 0, 20, $source[$i]['size'][0], $source[$i]['size'][1]);
            $tmpy = $tmpy + $source[$i]['size'][1];
            //释放资源内存
            imagedestroy($source[$i]['source']);
        }
        $return_imgpath = $img_path;
        imagepng($target_img,$return_imgpath);
        return $return_imgpath;
    }

    private function _return_pdf_html($info,$user_info){

        $info['event_time'] = Date('Y-m-d',strtotime($info['event_time']));
        $info['origin_send_time'] = Date('Y-m-d',strtotime($info['origin_send_time']));

        $order_detail = "";

        foreach ($info['order_detail'] as $key => $value) {
            $order_detail .= '
                    <tr>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['product_no'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['product_name'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['product_piece'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['box_amount'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['product_num'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['untax_price'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['untax_price']*$value['product_num'].'</th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$value['volume'].'</th>
                    </tr>
            ';
        }

        if( count($info['order_detail']) < 22 ){
            $need_add_empty_num = 22-count($info['order_detail']);
            for ($i=0; $i < $need_add_empty_num; $i++) { 
                $order_detail .= '
                        <tr>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                            <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                        </tr>
                ';
            }
        }

        $order_detail .= '
                <tr>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">总 计<p style="font-size:12px;">Total</p></th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> </th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;">'.$info['total_money'].'</th>
                    <th style="height:40px;border: 1px solid #ccc;font-weight: normal; text-align:center;font-size:14px;"> '.$info['total_volume'].'</th>
                </tr>
        ';

        $pdf_html = '
            <body style="background: #fff;">
            <h1 style="text-align:center;">'.$user_info['shop_name'].'</h1>

            <h2 style="text-align:center;">销售单</h2>

            <div style="margin-top:20px;">
                <div style="width:300px;height:25px;">地址(Address): '.$user_info['shop_address'].'</div>
                <div style="width:300px;height:25px;">联系人(Contact): ' .$user_info['nickname'].'</div>
                <div style="width:300px;height:25px;float:left;">联系电话(Mobile):' .$user_info['telephone'].'</div>
                <div style="width:300px;height:25px;float:left;">微信(Wechat):' .$user_info['wechat'].'</div>
                <div style="width:300px;height:25px;float:left;">QQ:' .$user_info['qq'].'</div>
                <div style="width:300px;height:25px;float:left;">邮箱(Email):' .$user_info['email'].'</div>
            </div>

            <table style="border-collapse: collapse; width: 900px; height: 56px; font-family: Source Sans Pro; font-size: 18px; position: relative;">
                <thead>
                    <tr>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">货   号<p style="font-size:12px;">No.</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 20%;text-align:center;font-size:14px;">品名<p style="font-size:12px;">Description</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">箱数<p style="font-size:12px;">CTN</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">每箱数量<p style="font-size:12px;">QTY/CTN</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">总数量<p style="font-size:12px;">T.QTY</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">单价(元)<p style="font-size:12px;">U.price</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">金额(元)<p style="font-size:12px;">T.price</p></th>
                        <th style="height:40px;border: 1px solid #ccc;font-weight: normal; width: 13%;text-align:center;font-size:14px;">体积(m3)<p style="font-size:12px;">Measure</p></th>
                    </tr>
                </thead>
        
                <tbody>'.$order_detail.'</tbody>


            </table>
            <div style="width:997px; border: 1px solid #ccc;font-weight: normal; text-align:left;font-size:14px;" >
                <div style="float:left;display:block;width:120px;height:40px;text-align:center;margin-top:5px;">
                    <div>备注</div>
                    <div>Remark</div>
                </div>
                <div style="with:780px;float:left;">'.$info['remark'].'</div>
            </div>
            <div style="margin-top:5px;">
                <div style="width:300px; height:25px;">客户名称(Customer):'.$info['customer_name'].'</div>
                <div style="width:300px; height:25px;float:left;">联系人(Contact):'.$info['customer_linkman'].'</div>
                <div style="width:300px; height:25px;float:left;">联系电话(Phone):'.$info['customer_telephone'].'</div>
                <div style="width:300px; height:25px;float:left;">开单日期(Order Date):'.$info['event_time'].'</div>
                <div style="width:300px; height:25px;float:left;">交货日期(Delivery Date):'.$info['origin_send_time'].'</div>
            </div>
            </body>

        ';
        return $pdf_html;
    }
}