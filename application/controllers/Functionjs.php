<?php
/**
 * Date: 2018/08/18
 * Time: 17:11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Functionjs extends Admin_Controller {
    public function index(){
        echo 'Functionjs';
    }
    //百度文件上传
    public function webuploader()
    {
        //图片上传操作
            $this->load->library('MyfileUpload');            
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            
            $uploader = new MyfileUpload();//实例化
            //定义上传路径
            $uploadpath=$uploader->path($_POST['imagetype']);
            //用于断点续传，验证指定分块是否已经存在，避免重复上传
            if(isset($_POST['status'])){
                if($_POST['status'] == 'chunkCheck'){
                    $target = "../flies/".$_POST['name'].'/'.$_POST['chunkIndex'];
                    if(file_exists($target) && filesize($target) == $_POST['size']){
                        die('{"ifExist":1}');
                    }
                    die('{"ifExist":0}');
                    
                }elseif($_POST['status'] == 'md5Check'){
                    //todo 模拟持久层查询
                    $dataArr = array(
                        'b0201e4d41b2eeefc7d3d355a44c6f5a' => 'kazaff2.jpg'
                    );
                    if(isset($dataArr[$_POST['md5']])){
                        die('{"ifExist":1, "path":"'.$dataArr[$_POST['md5']].'"}');
                    }
                    die('{"ifExist":0}');
                }elseif($_POST['status'] == 'chunksMerge'){
                    if($path = $uploader->chunksMerge($_POST['name'], $_POST['chunks'], $_POST['ext'])){
                        //todo 把md5签名存入持久层，供未来的秒传验证
                        die('{"status":1, "filename": "'.$path.'"}');
                    }
                    die('{"status":0');
                }
            }
            
            if(($path = $uploader->upload('file', $_POST)) !== false){
                die('{"status":1,"uploadpath":"'.$uploadpath.'", "filename": "'.$path.'"}');
            }{
            die('{"status":0}');            
            }
    }    
}