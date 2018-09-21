<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date: 2018/08/20
 * Time: 17:06
 */
#前台父控制器
class Home_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->home_html_path();
    }
    //获取用户信息
    public function get_userinfo(){
        return 1;
    }
}
#前台商户父控制器
class Shop_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->shop_html_path();
        //必须登录才能访问
        $location_class=strtolower($this->router->fetch_class());//获取控制器名
        //$this->router->fetch_method();//获取方法名  
        $model_array=array('login','merchantchild');//控制器名数组 
        //判断当前控制器名，是否存在控制名数组。
        if (in_array($location_class, $model_array)) {
            //echo "没有权限";die;
        }else{
            if (!$this->session->userdata('merchant_session')){
                 $data['url'] = site_url('merchant_shop/login/index');
                 $data['message'] = '请先登录账号';
                 echo "<script>alert('".$data['message']."'); window.location.href='".$data['url']."'</script>";exit;
            } 
        }
    }
}
#后台父控制器
class Admin_Controller extends CI_Controller{
    public function __construct(){
        parent::__construct();
        
        //echo $location_auth_array;
        /* $auth_array=array(
         'main/indexheader',
         'login/index',
         'message/index',
         'message/indexmain',
         'main/indexmainsidebar',
         'public/publicprofilemenu'
         );//控制器名数组
         
         if (in_array($location_auth_array, $auth_array)) {
         //echo "没有权限";die;
         }else{
         if (!$this->session->userdata('admin_session')){
         $data['url'] = 'admin/Login/index';
         $data['message'] = '请先登录账号';
         mess_redirect(3,$data['message'],$data['url']);
         }
         }*/
        
        #权限验证
        $this->PermissionValidation();
        
    }
    //权限验证+添加日志
    private function PermissionValidation(){
        if(empty($_SESSION['admin_session'])){
            redirect('admin/login/index');
        }else{
            $location_class = strtolower($this->router->fetch_class());//获取控制器名
            ##过滤公共的控制器
            //控制器名数组
            $auth_class_array=array(
                'functionjs'
            );
            if (in_array($location_class, $auth_class_array)) {
                //不用写日志
            }else{
                $location_method = strtolower($this->router->fetch_method());//获取方法名
                $location_auth_array=$location_class.'/'.$location_method;//当前模块
                $data = $_SESSION['admin_session']['admin_name'].' '.$location_auth_array.' '.date("Y/m/d H:i:s");
                $this->write_log($data);
            }
        }
    }
    private function write_log($data){
        $years = date('Y-m');
        $day = date('d');
        $hour = date('H');
        $years = date('Y-m').'/'.$day.'/'.$hour;
        #设置路径目录信息
        $url = FCPATH.'log/txlog/'.$years.'/'.date('Ymd').'_request_log.txt';
        $dir_name=dirname($url);
       #目录不存在就创建
        if(!file_exists($dir_name))
        {
            #iconv防止中文名乱码
            $res = mkdir(iconv("UTF-8", "GBK", $dir_name),0777,true);
        }
        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建
        fwrite($fp,var_export($data,true)."\r\n");//写入文件
        fclose($fp);//关闭资源通道
    }
}