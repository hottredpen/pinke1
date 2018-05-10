<?php
namespace Common\Datamanager;
/**
 * BaseDatamanager
 */
class BaseDatamanager {

    protected $replaceOptions = array();
    protected $is_for_admin   = false;

    /**
     * 必须实现
     */
    protected function getNewModel(){
        return M();
        // return M("store_supplier as ss");
    }

    public function getData($p=1,$page_size=20,$map=array(),$order=" id desc "){
        $data  = $this->_takeFormatData("data",$map,$p,$page_size,$order);
        return $data;
    }

    public function getInfo($id=0,$map=array()){
        $map['id'] = $id;
        $data      = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNum($map){
        $data = $this->_takeData("num",$map);
        return (int)$data;
    }


    public function getDataForAdmin($p=1,$page_size=15,$map=array(),$order=' id desc '){
        $this->is_for_admin = true;
        $data  = $this->_takeFormatData("data",$map,$p,$page_size,$order);
        return $data;
    }

    public function getInfoForAdmin($id=0,$map=array()){
        $this->is_for_admin = true;
        $map['id'] = $id;
        $data      = $this->_takeFormatData("data",$map,1,1);
        return $data[0];
    }

    public function getNumForAdmin($map){
        $this->is_for_admin = true;
        $data = $this->_takeData("num",$map);
        return (int)$data;
    }

    protected function _takeFormatData($type="data",$map=array(),$p=1,$page_size=10,$order=" id desc "){
        $data  = $this->_takeData("data",$map,$p,$page_size,$order);
        return $data;
    }

    protected function _takeData($type="data",$searchmap=array(),$p=1,$page_size=20,$order=" id desc "){
        $searchmap = $this->replaceMap($searchmap);
        $order     = $this->replaceOrder($order);
        $offset    = $this->getOffset($p,$page_size);

        
        $map = array();
        //$map['status'] = 1; // 此处添加默认值

        //合并覆盖
        if(count($searchmap) > 0){
            $newmap = array_merge($map, $searchmap);
        }else{
            $newmap = array();
        }
        // 此处添加固定死的条件
        // $newmap['sys_id'] = store_session_sys_id();

        $model = $this->getModel();

        if($type=="data"){
            $list = $model->field('ss.*')
                    ->where($newmap)
                    ->order($order)
                    ->limit($offset.','.$page_size)
                    ->select();

        }else{
            $list = $model->field('ss.id')
                    ->where($newmap)
                    ->count();
        }
        return $list;
    }


    /**
     * 多表查询时替换本Datamanager里的order字符
     * @param  string $order [description]
     * @return [type]        [description]
     */
    public function replaceOrder($order=""){
        $order = " ".$order." "; // 先在两边加点空白
        foreach ($this->replaceOptions as $key => $value) {
            $order = preg_replace('/\s+('.$key.')\s+/', " ".$value." ", $order);
        }
        return $order;
    }
    /**
     * 多表查询时的相同字段处理
     */
    public function replaceMap($map){
        $newmap = common_replace_dbpre_name_for_leftjoin_map($map,$this->replaceOptions);
        return $newmap;
    }

    public function getOffset($p=1,$page_size=10){
        $offset = ($p - 1) * $page_size;
        $offset = $offset < 0 ? 0 : $offset;
        return $offset;
    }

}