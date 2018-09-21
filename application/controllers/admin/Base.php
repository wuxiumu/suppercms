<?php
/**
 * Created by PhpStorm.
 * User: DM
 * Date: 2018/4/3
 * Time: 17:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Base extends Admin_Controller {
    //构造函数
    public function __construct()
    {
        parent::__construct();
    }
    //网站基本配置
    function base_sysinfo(){
        $this->load->database();
        $this->load->library('Sysinfo');
        $sysinfo = new Sysinfo;
        $date["sysinfo"] = $sysinfo->getSysInfos();
        $date["sysinfo"]["MYSQL版本"] = $this->db->version();
        $this->load->view("admin/base/base_sysinfo.html", $date);
    }
    //获取网站基本设置，显示
    public function base_view(){
        $this->load->library("Baseset");
        $base = New Baseset();
        $data["base"] = $base->getsystem();
        if (file_exists(substr(FCPATH,0,strlen(FCPATH)-1).$data["base"]['web_logo'])) {
            
        }else{ 
            $data["base"]['web_logo'] = '';
        }
        $this->load->view("admin/base/base_system.html",$data);
    }
    //更新网站配置操作
    public function base_update(){
        if($_POST){
            $this->load->library('Baseset');
            $base = New Baseset();
            //获取文件信息
            $data["base"] = $base->getsystem();
            $web_logo = substr(FCPATH,0,strlen(FCPATH)-1). $data["base"]['web_logo'];
            $path = "/public_file/logo/"; 
            if(!empty($_FILES['web_logo']['name'])){
                $web_log_arr = $this->fileUpload($_FILES['web_logo'],$web_logo,$path);
                $_POST['web_logo'] = $web_log_arr['url'];
            }else{
                $_POST['web_logo'] = $data["base"]['web_logo'];
            }
            if($base->writesystem($_POST)){
                $data['url'] = 'admin/Base/base_view';
                $data['message'] = '编辑成功';
                mess_redirect(3,$data['message'],$data['url']); 
            }elseif($base->writesystem($_POST)==0){
                $data['url'] = 'admin/Base/base_view';
                $data['message'] = '内容未发生变化';
                mess_redirect(3,$data['message'],$data['url']); 
            }else{
                $data['url'] = 'admin/Base/base_view';
                $data['message'] = '编辑失败';
                mess_redirect(3,$data['message'],$data['url']); 
            }
        }       
    }
    //接收文件
    public function fileUpload($data,$file,$path){
        #允许上传的图片后缀
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $data["name"]);
        $extension = end($temp);     // 获取文件后缀名
        if ((($data["type"] == "image/gif")
            || ($data["type"] == "image/jpeg")
            || ($data["type"] == "image/jpg")
            || ($data["type"] == "image/pjpeg")
            || ($data["type"] == "image/x-png")
            || ($data["type"] == "image/png"))
            && ($data["size"] < 20480000)   // 小于 20000 kb
            && in_array($extension, $allowedExts))
        {
            if ($data["error"] > 0){
                $data['msg']="错误：: " . $data["error"] . "<br>";
            }else{                
                #创建目录
                $this->makedir(substr(FCPATH,0,strlen(FCPATH)-1).$path);
                #如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                move_uploaded_file( $data["tmp_name"], substr(FCPATH,0,strlen(FCPATH)-1).$path.$data["name"]);
                $this->deletefile($file);
                $data['msg'] = "文件存储在: " . $path.$data["name"];
                $data['url'] = $path.$data["name"];
            }
        }
        else
        {
            $data['msg']="非法的文件格式";
        }
        return $data;      
    }
    //创建文件
    public function makedir($path){
        #conv方法是为了防止中文乱码，保证可以创建识别中文目录，不用iconv方法格式的话，将无法创建中文目录
        $data=[];
        $dir = iconv("UTF-8", "GBK", $path);
        if (!file_exists($dir)){
            mkdir ($dir,0777,true);    
            $data['status']='1';
            $data['msg']='创建文件夹".$path."成功';
        } else {
            $data['status']='0';
            $data['msg']='需创建的文件夹".$path."已经存在';
        }
        return $data;
    }
    //删除文件
    public function deletefile($file){
        if (file_exists($file)) {
            $data['msg']="文件".$file."存在";
            if (!unlink($file)){
                $data['status'] = 2;
                $data['msg']="删除".$file."时出错";
            }else{
                $data['status'] = 1;
                $data['msg']="删除了".$file;
            }
        } else {
            $data['status'] = 0;
            $data['msg']    = "文件".$file."不存在";
        }
        return $data;
    }
    
}