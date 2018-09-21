<?php
/**
 * Date: 2018/08/20
 * Time: 14:28
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    //构造函数
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
    }
    //登录页
    public function index(){
        $this->load->view('admin/login/login.html');
    }
    public function login_in(){
        if($_POST){                        
            #获取表单数据
            $captcha = strtolower($this->input->post('admin_code'));            
            #获取session中保存的验证码
            $code = strtolower($this->session->userdata('admin_code'));    
             if ($captcha == $code){                
                    #验证码正确，则需要验证用户名和密码
                    $username = $this->input->post('username',true);
                    $userpwd = md5($this->input->post('userpwd',true));
                    $admin=$this->Admin_model->adminLogin($username,$userpwd);
                    if(!empty($admin['admin']) && !empty($admin['admin_group']) && !empty($admin['admin_info'])){
                        # SUCCESS，保存session信息,然后跳转到首页
                        $sessionData = array(
                            'admin_id'  => $admin['admin']['id'],
                            'admin_name'       => $admin['admin']['username'],
                            'admin_groupname'       => $admin['admin_group']['groupname'],
                            'admin_login_status'       => $admin['admin']['status']
                        );
                        $this->session->set_userdata("admin_session",$sessionData);
                        redirect('admin/main');
                    }else{
                        $data['message']='密码错误，请重新填写';
                        $data['url']='admin/main/index';
                        mess_redirect(3,$data['message'],$data['url']); 
                    }
            } else {
                #ERROR,验证码不正确，给出提示页面，然后返回                 
                $data['message']='验证码错误，请重新填写';
                $data['url']='admin/main/index';
                mess_redirect(3,$data['message'],$data['url']); 
            }
        }else{
            $this->load->view('admin/login/login.html');
        }    	
    }
    //退出登陆
    public function login_out(){
    	$this->session->sess_destroy("admin_session");
        $data['message']='退出成功';
        $data['url']='admin/login/index';
        mess_redirect(3,$data['message'],$data['url']); 
    }
    //验证码
    public function code(){
        $config = array(
            'width' =>  80,
            'height'=>  27,
            'codeLen'=> 4,
            'fontSize'=>16,
            'sessionName'=>'admin_code'
            );
        $this->load->library('code', $config);
        $this->code->show();
    }
    //公共左侧导航菜单
    public function menu_head()
    {
        $this->load->view('admin/public/menu_head.html');
    }
    //公共头部
    public function main_head()
    {
        $this->load->view('admin/public/main_head.html');
    }
}