<?php
namespace Common\Util;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         hottredpen <hottredpen@126.com>
// +----------------------------------------------------------------------

class Page {
    
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 分页地址
    public $path = '';
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =    array('header'=>'条记录','prev'=>'<上一页','next'=>'下一页>','first'=>'第一页','last'=>'最后一页','theme'=>'%totalRow% %header% %nowPage%/%totalPage% 页 %first% %upPage% %linkPage% %downPage% %end%');
    // 默认分页变量名
    protected $varPage;

    protected $is_pjax;      // 0-普通  1-pjax 2-ajax

    protected $not_need_num; //主要是为了弹窗里，去掉多余的数字显示todo 转数字，多种简短的显示方式

    protected $pjax_container;


    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     * @param string $is_pjax  是否是pjax,默认为否
     * @param string $not_need_num  不需要分页，只需显示上一页和下一页（弹窗宽度较小时使用） 
     * @param string $pjax_container  pjax局部刷新的容器id
     */
    public function __construct($totalRows,$listRows='',$parameter='',$is_pjax=0,$not_need_num=false,$pjax_container="#pjax_container") {
        $this->totalRows      =   $totalRows;
        $this->parameter      =   $parameter;
        $this->varPage        =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        $this->is_pjax        =   $is_pjax;
        $this->not_need_num   =   $not_need_num;
        $this->pjax_container =   $is_pjax == 1 ? "data-pjax-container='".$pjax_container."' " : "";
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     * hottredpen@126.com 修改了分页的
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $middle = ceil($this->rollPage/2); //中间位置

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                if(empty($_GET)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $_GET;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            //$url            =   U($this->path,$parameter);
            $oldurl = (is_ssl()?'https://':'http://').$_SERVER['HTTP_HOST'].rtrim($_SERVER["REQUEST_URI"],"/")."/";
            $oldurl = preg_replace("/([&?])?([_pjax]+)=%23([A-Za-z-_]+)/", '', $oldurl); // 去掉pjax
            if(!strstr($oldurl,"?")){
                // 原有链接不存在？
                $url    = rtrim($oldurl,"/").'/?'.$this->varPage."=".'__PAGE__';
            }else if(strstr($oldurl,"/?p")){
                // 已经存在/?p=1
                $url    = rtrim(preg_replace("/\/([\?]+)p=([\d]+)/", '', $oldurl),"/").'/?'.$this->varPage."=".'__PAGE__';
            }else{
                // 存在？的搜索链接
                $url    = rtrim(preg_replace("/([&]+)p=([\d]+)/", '', $oldurl),"/").'&'.$this->varPage."=".'__PAGE__';
            }
            


        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;

        $target   = $this->is_pjax === 1 ? "" : "target='_self'";

        if ($upRow>0){
            $upPage     =   "<a class='btn btn-default' ".$target."  ".$this->pjax_container."  href='".str_replace('__PAGE__',$upRow,$url)."'>上一页</a>";
        }else{
            if($this->not_need_num){
                $upPage     =   "<a class='btn btn-default' disabled >上一页</a>";
            }else{
                $upPage     =   "";
            }
            
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<a class='btn btn-default' ".$target." ".$this->pjax_container." href='".str_replace('__PAGE__',$downRow,$url)."'>下一页</a>";
        }else{
            
            if($this->not_need_num){
                $downPage   =   "<a class='btn btn-default' disabled >下一页</a>";
            }else{
                $downPage   =   "";
            }

        }

        // << < > >>
        $theFirst = $theEnd = '';
        if ($this->totalPages > $this->rollPage) {
            if($this->nowPage - $middle < 1){
                $theFirst   =   '';
            }else{
                if($this->not_need_num){
                    $theFirst   =   "";
                }else{
                    $theFirst   =   "<a class='btn btn-default' ".$target."  ".$this->pjax_container."  href='".str_replace('__PAGE__',1,$url)."' >第一页</a>";
                }
                
            }
            if($this->nowPage + $middle > $this->totalPages){
                

                if($this->not_need_num){
                    $theEnd     =   "<a class='btn btn-default' disabled >最一页</a>";
                }else{
                    $theEnd     =   "";
                }


            }else{
                $theEndRow  =   $this->totalPages;
                $theEnd     =   "<a class='btn btn-default' ".$target."  ".$this->pjax_container."  href='".str_replace('__PAGE__',$theEndRow,$url)."' >最一页</a>";
            }
        }





        // 1 2 3 4 5
        $linkPage = "";
        if ($this->totalPages != 1) {
            if ($this->nowPage < $middle) { //刚开始
                $start = 1;
                $end = $this->rollPage;
            } elseif ($this->totalPages < $this->nowPage + $middle - 1) {
                $start = $this->totalPages - $this->rollPage + 1;
                $end = $this->totalPages;
            } else {
                $start = $this->nowPage - $middle + 1;
                $end = $this->nowPage + $middle - 1;
            }
            $start < 1 && $start = 1;
            $end > $this->totalPages && $end = $this->totalPages;
            for ($page = $start; $page <= $end; $page++) {
                if ($page != $this->nowPage) {
                    $linkPage .= " <a class='btn btn-default' ".$target."  ".$this->pjax_container."  href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a>";
                } else {
                    $linkPage .= " <a class='btn btn-primary' ".$target."  ".$this->pjax_container."  class='current'>".$page."</a>";
                    $thiscurrentpage = $page;
                }
            }



            $theEnd    .= "<a id='j_page_jump_btn' ".$target." ".$this->pjax_container."  class='btn btn-default'  href='".str_replace('__PAGE__',$thiscurrentpage,$url)."'  >跳到</a><input name='page_jump_to_value' class='form-control'  type='text'  style='width:50px;display: inline-block;' value='".$thiscurrentpage."'  data-maxpage='".$this->totalPages."'   />页";


        }

        if($this->not_need_num){
            $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,"",$theEnd),$this->config['theme']);
        }else{
            $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%linkPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$linkPage,$theEnd),$this->config['theme']);
        }


        return $pageStr;
    }
}