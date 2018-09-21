<?php
/**
 * Date: 2018/09/20
 * Time: 13:47
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Source extends Admin_Controller {
    //构造函数
    public function __construct()
    {
        parent::__construct();
        //加载model模型    
        $this->load->model('Source_model');      
    }
    //默认页
    public  function index()
    {
        echo date("H:i:s",time());      
     }
     //左侧菜单
    public function source_menu()
    {
         $path = '0-63';
         $where="path like '%$path%' AND type_id=2";
         $sql = "SELECT * FROM bro_column WHERE ".$where." ORDER BY ord ASC";
         $res_sql = $this->db->query($sql);
         $data['column']  = $res_sql->result_array();
    	 $this->load->view('admin/source/source_menu.html',$data);
    }
    //列表
    public function table()
    {         
        $pid=0;
        if(!empty($this->uri->segment(4))){
            $pid=$this->uri->segment(4);
        }
        $tableData = $this->table_datas($pid);
        $tableData['selectHtml']=$this->treeId($pid);
        $tableData['pid']=$pid;
        $tableData['pname']='全部';
        if($pid!='0'){
            $sql="SELECT title FROM bro_column WHERE id=".$pid;
            $res_sql = $this->db->query($sql);
            $res       = $res_sql->row_array();
            $tableData['pname'] = $res['title'];
        }
        $this->load->view('admin/source/table.html',$tableData);
    }
    public function table_datas($pid)
    {        
        if($pid=='' || $pid=='0' || $pid==NULL){
            $where=array('status'=>1);   
        }else{
            $where=array('pid'=>$pid,'status'=>1);   
        }             
        $this->load->library('pagination');
        $perPage = 200;
         //配置项设置
        $config['base_url'] = site_url('admin/source/table/')."/".$pid;
        $config['total_rows'] = $this->db->where($where)->count_all_results('sources');
        $config['per_page'] = $perPage;
        $config['uri_segment'] = 5;//url的位置获取分页的参数
        $config['first_link'] = '第一页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '最后一页';
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $offset = $this->uri->segment(5);
        $this->db->limit($perPage, $offset);
        $data['links_offset']=$offset;
        $data["sources"]= $this->db
                        ->select('*')
                        ->order_by('ord','asc')
                        ->where($where)
                        ->from('sources')
                        ->limit($perPage, $offset)         
                        ->get()
                        ->result_array();        
        return $data;
    }
    //添加 
    public function add()
    {    	
        $pid=$this->uri->segment(4);
        if(empty($this->uri->segment(4))){
            $pid=64;
        }
        $tree = $this->Source_model->GetTreeData(63,0);
        $html =  $this->Source_model
        ->GetTreeArrHtml($tree, $pid);
        $data['selectHtml'] = $html; 
        $this->load->view('admin/source/add.html',$data); 
    }
    public function insert()
    {
        if($_POST){
            
            $_POST['display']=$_POST['radio'];unset($_POST['radio']);unset($_POST['file']);
            if(!empty($_POST['imgs']) && !empty($_POST['imgs_url'])){
                $_POST['image'] = '/'.$_POST['imgs_url'][0].'/'.$_POST['imgs'][0];      
                unset($_POST['imgs']);unset($_POST['imgs_url']);
            }
            $re=$this->Source_model->addSource($_POST);
            if($re=='1'){
                $data['message']='添加成功';
                $data['url']='admin/source/table/'.$_POST['pid'];
                //mess_redirect(3,$data['message'],$data['url']);
                redirect($data['url']);
            }else{
                $data['message']='添加失败';                
                $data['url'] = '';        
                mess_redirect(0,$data['message'],$data['url']);
            }
        }
    }
    /**
     * 修改
     *  
     */
    public function mod()
    {
        $id= $this->uri->segment(4);
        $data = $this->Source_model->getSourceInfo($id); 
        $tree = $this->Source_model->GetTreeData(0,0);
        $html =  $this->Source_model
        ->GetTreeArrhtml($tree, $data['source']['pid']);      
        $data['selectHtml'] = $html;  
        $this->load->view('admin/source/mod.html',$data); 
    }
    public function update(){
        $id = $_POST['id'];unset($_POST['id']);unset($_POST['file']);
        $_POST['display']=$_POST['radio'];unset($_POST['radio']);
        if(!empty($_POST['imgs']) && !empty($_POST['imgs_url'])){
            $_POST['image'] = '/'.$_POST['imgs_url'][0].'/'.$_POST['imgs'][0];            
            unset($_POST['imgs']);unset($_POST['imgs_url']);
        }          
        if($_POST['pid']=='0'){
            $path='0-'.$_POST['id'];
        }else{ 
            $data['s']=$this->Source_model->getColumnInfo($_POST['pid']);    
            $path=$data['s']['column']['path'].'-'.$id;
        } 
        $_POST['path']=$path;
        $re=$this->Source_model->actionSourceUpdate($_POST,$id);
        if($re=='1'){
            $data['message'] = '修改成功';
            $data['url']='admin/source/table/'.$_POST['pid'];
            //mess_redirect(3,$data['message'],$data['url']); 
            redirect($data['url']);
        }else{
            $data['url'] = '';
            $data['message'] = '修改失败';
            mess_redirect(0,$data['message'],$data['url']);
        }
    }
    //更新排序
    public function update_ord(){
        if($_POST){
            switch ($_POST['action']) {
                case 'ord':
                    $ord=$_POST['ord'];
                    $re=$this->Source_model->actionSourceUpdate(array('ord'=>$ord),$_POST['id']);
                    break;
            }
            echo $re;
        }
    }
    /**
     *  逻辑删除/隐藏/删除/
     *  
     */
    public function delete()
    {   
        if($_POST){
            switch ($_POST['action']) {
                case 'delete':
                    $re=$this->Source_model->actionSourceUpdate(array('status'=>'2'),$_POST['id']);
                    break;                
                case 'display':
                    $re=$this->Source_model->actionSourceUpdate(array('display_'=>'1'),$_POST['id']);
                    break;
                case 'destroy':
                    $re=$this->Source_model->actionSourceDel($POST['id']);
                    break;                    
            }
            echo $re;
        }
    }   
    /**
     * 一级栏目
     *  
     */
    public function columnOne()
    {
        $data=$this->Column_model->typeId('0');
        $html = '';
        $html .= "<option value='0' path='' title=''>添加</option>";
        foreach ($data['column'] as $key => $value) {
            $html .= "<option value='".$value['id']."' path='".$value['path']."' title='".$value['description']."'>".$value['title']."</option>";
        }          
        return  $html;      
    }
    //栏目树    
    public function treeId($pid)
    {
        $data = $this->Source_model->typeId($pid);
        $html = '';
        $html .= "<option value='0' path='' title=''>--</option>";
        if($pid=='0'){
            $html .= "<option value='0' path='' title='' selected>全部</option>";
        }else{
            $html .= "<option value='0' path='' title=''>全部</option>";
        }        
        foreach ($data['column'] as $key => $value) {
            $html .= "<option value='".$value['id']."' path='".$value['path']."' title='".$value['description']."'";
            if($value['id']==$pid){
                $html .= " selected>";
            }else{
                $html .= ">";
            }            
            $html .= $value['title']."</option>";
        }          
        return  $html;      
    } 
}