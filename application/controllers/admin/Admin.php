<?php
/**
 * Date: 2018/3/30
 * Time: 16:50
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {
    //构造函数
    public function __construct()
    {
        parent::__construct();
        //加载model模型
        $this->load->model('Admin_model');
    }
    // 默认页
    public  function index(){
        echo date("H:i:s",time());
    }
    //左侧菜单页
    public function admin_menu()
    {
        $this->load->view('admin/admin/admin_menu.html');
    }
    //列表
    public  function table()
    {
        $pid=0;
        if(!empty($this->uri->segment(4))){
            $pid=$this->uri->segment(4);
        }
        $tableData = $this->table_datas($pid);
        $tree = $this->Admin_model->getAdmingroupInfo(0,0);
        $tableData['selectHtml'] =  $this->Admin_model
        ->GetTree_arr_html($tree['admin_group'],$pid); 
        $tableData['pid']=$pid;
        $this->load->view('admin/admin/table.html',$tableData);
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
        $config['base_url'] = site_url('admin/admin/table/')."/".$pid;
        $config['total_rows'] = $this->db->where($where)->count_all_results('admin');
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
        $data["admin"]= $this->db
        ->select('*')
        ->order_by('id','asc')
        ->where($where)
        ->from('admin')
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
        $tree = $this->Admin_model->getAdmingroupInfo(0,0);
        $data['selectHtml'] =  $this->Admin_model
        ->GetTree_arr_html($tree['admin_group'], $id);
        $this->load->view('admin/admin/add.html', $data);
    }
    public function insert()
    {
        if($_POST){
            if($_POST['userpwd']==$_POST['qruserpwd']){
                $_POST['userpwd']=md5($_POST['userpwd']);
                unset($_POST['qruserpwd']);
            }else{
                $data['message']='两次密码不一致';
                $data['url'] = '';
                mess_redirect(0,$data['message'],$data['url']);
            }
            $_POST['pid']=1;//pid=1管理员
            $_POST['status']=1;//正常
            $_POST['regtime']=time();//注册时间
            $re=$this->Admin_model->addAdminInfo($_POST);
            if($re){
                $data['message']='添加成功';
                $data['url']='admin/admin/table/'.$_POST['pid'];
                redirect($data['url']);
            }else{
                $data['message']='添加失败';
                $data['url'] = '';
                mess_redirect(0,$data['message'],$data['url']);
            }
        }
    }
    // 修改
    public function mod()
    {
        $id= $this->uri->segment(4);
        $data = $this->Admin_model->getAdminInfo($id);
        $tree = $this->Admin_model->getAdmingroupInfo(0,0);
        $data['selectHtml'] =  $this->Admin_model
        ->GetTree_arr_html($tree['admin_group'], $data['admin']['pid']);
        $this->load->view('admin/admin/mod.html',$data);
    }
    public function update()
    {
        if($_POST){
            if($_POST['userpwd']=='' || $_POST['qruserpwd']==''){
                unset($_POST['userpwd']);
                unset($_POST['qruserpwd']);
            }else{
                if($_POST['userpwd']==$_POST['qruserpwd']){
                    $_POST['userpwd']=md5($_POST['userpwd']);
                    unset($_POST['qruserpwd']);
                }else{
                    $data['message']='两次密码不一致';
                    $data['url'] = '';
                    mess_redirect(0,$data['message'],$data['url']);
                }
            }
            $id = $_POST['id'];
            unset($_POST['id']);
            $re=$this->Admin_model->updatelAdminInfo($_POST,$id);
            if($re){
                $data['message'] = '修改成功';
                $data['url']='admin/admin/table/'.$_POST['pid'];
                redirect($data['url']);
            }else{
                $data['url'] = '';
                $data['message'] = '修改失败';
                mess_redirect(0,$data['message'],$data['url']);
            }
        }
    }
    //逻辑删除/隐藏/删除/
    public function delete()
    {
        if($_POST){
            switch ($_POST['action']) {
                case 'delete':
                    $re=$this->Admin_model->updatelAdminInfo(array('status'=>'9'),$_POST['id']);
                    break;                
                case 'display':
                    $re=$this->Admin_model->updatelAdminInfo(array('disable_'=>'1'),$_POST['id']);
                    break;
                case 'destroy':
                    $re=$this->Admin_model->delAdminInfo($POST['id']);
                    break;                    
            }
            echo $re;
        }
    }        
}