<?php
/**
 * Date: 2018/08/19
 * Time: 04:17
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Column extends Admin_Controller {
    //构造函数
    public function __construct()
    {
        parent::__construct();
        //加载model模型    
        $this->load->model('Column_model');      
    }
    //默认页
    public function index(){
       echo date("H:i:s",time());
    }
    //左侧菜单页
    public function column_menu()
    {
       $this->load->view('admin/column/column_menu.html');
     }
     //列表页
    public function table()
    {         
        $pid=0;
        if(!empty($this->uri->segment(4))){
            $pid=$this->uri->segment(4);
        }
        $tableData = $this->table_datas($pid);
        $tableData['selectHtml']=$this->treeId($pid);
        $tableData['pid']=$pid;
        $this->load->view('admin/column/table.html',$tableData);
    }
    public function table_datas($pid)
    {        
        $where=array('pid'=>$pid,'status'=>1);        
        $this->load->library('pagination');
        $perPage = 20;
         //配置项设置
        $config['base_url'] = site_url('admin/column/table/')."/".$pid;
        $config['total_rows'] = $this->db->where($where)->count_all_results('column');
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
        $data["column"]= $this->db
                        ->select('*')
                        ->order_by('ord','asc')
                        ->where($where)
                        ->from('column')
                        ->limit($perPage, $offset)         
                        ->get()
                        ->result_array();        
        return $data;
    }
    //添加 
    public function add()
    {    	
        $id= $this->uri->segment(4);
        if(empty($id)){
           $id=0; 
        }
        $tree = $this->Column_model->GetTree_data(0,0);
        $html =  $this->Column_model
        ->GetTree_arr_html($tree, $id);
        $data['selectHtml'] = $html; 
        $this->load->view('admin/column/add.html',$data); 
    }
    public function insert()
    {
        if($_POST){
            $_POST['display']=$_POST['radio'];unset($_POST['radio']);          
            $re=$this->Column_model->add_column($_POST);
            if($re=='1'){
                $data['message']='添加成功';
                $data['url']='admin/column/table/'.$_POST['pid'];
                redirect($data['url']);
            }else{
                $data['message']='添加失败';                
                $data['url'] = '';        
                mess_redirect(0,$data['message'],$data['url']);
            }
        }
    }
    //修改 
    public function mod()
    {
        $id= $this->uri->segment(4);
        $data = $this->Column_model->get_column_info($id);        
        $tree = $this->Column_model->GetTree_data(0,0);
        $html =  $this->Column_model
                 ->GetTree_arr_html($tree, $data['column']['pid']);      
        $data['selectHtml'] = $html;  
        $this->load->view('admin/column/mod.html',$data); 
    }
    public function update()
    {
        $data['post']=$_POST;  
        $data['s']=$this->Column_model->get_column_info($_POST['id']);
        if($_POST['pid']=='0'){
            $path='0-'.$_POST['id'];
        }else{
            $data['p']=$this->Column_model->get_column_info($_POST['pid']);
            $path=$data['p']['column']['path'].'-'.$_POST['id'];
        }        
        $id=$_POST['id'];unset($_POST['id']); 
        $_POST['path']=$path;
        $_POST['display']=$_POST['radio'];unset($_POST['radio']);    
        $re=$this->Column_model->action_column_update($_POST,$id);
        if($re=='1'){
            $data['message'] = '修改成功';
            $data['url']='admin/column/table/'.$_POST['pid'];
            redirect($data['url']);
        }else{
            $data['url'] = '';
            $data['message'] = '修改失败';
            mess_redirect(0,$data['message'],$data['url']);
        }
    }
    //更新排序
    public function update_ord()
    {
        if($_POST){
            switch ($_POST['action']) {
                case 'ord':
                    $ord=$_POST['ord'];
                    $re=$this->Column_model->action_column_update(array('ord'=>$ord),$_POST['id']);
                    break;
            }
            echo $re;
        }
    }
    //逻辑删除/隐藏/删除/
    public function delete()
    {       
        if($_POST){
            switch ($_POST['action']) {
                case 'delete':
                    $re=$this->Column_model->action_column_update(array('status'=>'9'),$_POST['id']);
                    break;                
                case 'display':
                    $re=$this->Column_model->action_column_update(array('display_'=>'1'),$_POST['id']);
                    break;
                case 'seldelete':
                    $ids=explode(',', $_POST['ids']); 
                    foreach ( $ids as $key => $val) {
                      $this->Column_model->action_column_update(array('status'=>'9'),$val);
                    }       
                    $re=1;
                    break;     
                case 'destroy':
                    $re=$this->Column_model->action_column_del($POST['id']);
                    break;                    
            }
            echo $re;
        }
    } 
    //一级栏目
    public function columnOne()
    {
        $data=$this->Column_model->type_id('0');
        $html = '';
        $html .= "<option value='0' path='' title=''>添加</option>";
        foreach ($data['column'] as $key => $value) {
            $html .= "<option value='".$value['id']."' path='".$value['path']."' title='".$value['description']."'>".$value['title']."</option>";
        }          
        return  $html;      
    }
    //列表页栏目    
    public function treeId($pid)
    {
        $data = $this->Column_model->type_id($pid);
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
    //ajax 请求 
    public function columnPost()
    {
        $pid=0;
        if(!empty($_POST)){
            $pid=$_POST['id'];
        }else{ die('error');}
        $data=$this->Column_model->type_id($pid);
        $html = '';
        $html .= "<option>--</option>";
        $html .= "<option value='0' path='' title=''>添加</option>";
        foreach ($data['column'] as $key => $value) {
            $html .= "<option value='".$value['id']."' path='".$value['path']."' title='".$value['description']."'>".$value['title']."</option>";
        }          
        echo $html;      
    }
}
