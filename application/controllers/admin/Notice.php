<?php
/**
 * Date: 2018/3/31
 * Create_Time: 11:53
 * Update_Time: 2018.05.09 14:59
 * Description: 前端管理
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends Admin_Controller 
{
	//构造函数
    public function __construct()
    {
        parent::__construct(); 
        $this->load->helper(array('form','url'));//载入辅助函数：
        $this->load->model('notice_model');
    }
	//默认页面
	public function index()
	{
		// header("refresh:3;url=/index.php/admin/happymod/main");
		// print('正在加载，请稍等...<br>三秒后自动跳转到商户后台页~~~');
	}
	//菜单页
	public function notice_menu()
	{
		$this->load->view('admin/notice/notice_menu.html');
	} 	
	//列表添加页
	public function main()
	{ 
		$typeid = $this->uri->segment(5);
	    if($typeid){
	        $data['typeid']=$typeid;
	        $where=array('pid'=>$typeid,'status'=>1);
	    }else{
	        $where=array('status'=>1,'pid !='=>'0');
	        $data['typeid']=1;
	    }
        //$this->output->enable_profiler(TRUE);//激活分析器以调试程序       
        $this->load->library('pagination'); //载入分页类
        $perPage = 10;
        //配置项设置
        $config['base_url'] = site_url('admin/notice/main');
        $config['total_rows'] = $this->db->where($where)->count_all_results('notice');
        $config['per_page'] = $perPage;
        $config['uri_segment'] = 4;//url的位置获取分页的参数
        $config['first_link'] = '第一页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '最后一页';
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $offset = $this->uri->segment(4);
        $this->db->limit($perPage, $offset);
        $data['links_offset']=$offset;
        $data["notice"]= $this->db
						        ->select('*')
						        ->order_by('id','asc')
						        ->where($where)
						        ->from('notice')
						        ->limit($perPage, $offset)         
						        ->get()
						        ->result_array();					        
        $data['gettree']=$this->notice_model->gettree_main($typeid);
		$this->load->view('admin/notice/main.html',$data);
	}
	// 添加前端显示组 
	public function add()
	{
		if($_POST){
			$data = array (
              'pid' => $this->input->post('pid'),
              'title' => $this->input->post('title'),
              'description' => $this->input->post('description'),
              'content' => $this->input->post('content'),
              'ord' => $this->input->post('ord'),
              'display' => $this->input->post('display')
            );
            $re=$this->notice_model->insert($data);
			if($re){
	            $message="添加成功";
	            $this->error($message,2);
	        }else{
	            $this->error("添加失败",2);
	        }								            
		exit;
		}else{
			$data['gettree']=$this->notice_model->gettree(0);
		    $this->load->view('admin/notice/add.html',$data);
		}
	}
	//修改页
	public function mod()
	{
		if($_POST){		
			switch ($_POST['status']) {
				case 'mod_ord':
					$data=array(
						'id_arr'=>$this->input->post('id_arr'),
						'ord_arr'=>$this->input->post('ord_arr')
					);
					$re=$this->notice_model->ajax_mod_ord($data);
					if($re=="1"){
						echo 1;
					}				
					break;
				case 'mod_change':
					$data=array(
						'id'=>$this->input->post('id'),
						'v'=>$this->input->post('v'),
						'k'=>$this->input->post('k')
					);
					$re=$this->notice_model->ajax_update($data);
					if($re=="1"){
						echo 1;
					}
					break;
				case 'display':
					if($_POST['display']=='1'){
						$v=2;
					}else{
						$v=1;
					}
					$data=array(
						'id'=>$this->input->post('id'),
						'v'=>$v,
						'k'=>'display'
					);
					$re=$this->notice_model->ajax_update($data);
					if($re=="1"){
						echo 1;
					}
					break;
				case 'del':
					$data=array(
						'id'=>$this->input->post('id'),
						'v'=>'9',
						'k'=>'status'
					);
					$re=$this->notice_model->ajax_update($data);
					if($re=="1"){
						echo 1;
					}
					break;										
				default:
					echo 'error';
					break;
			}
		exit;	
		}
	    $where="status=1 and pid=0";
	    $data["notice"]= $this->db
					        ->select('*')
			                ->where($where)
					        ->order_by('ord','asc')
					        ->from('notice')
					        ->get()	
					        ->result_array();
			        				        
	    $this->load->view('admin/notice/mod.html',$data);
	}
	//添加内容
	public function add_content()
	{
	    if($_POST){
			$data = array (
              'pid' => $this->input->post('pid'),
              'title' => $this->input->post('title'),
              'description' => $this->input->post('description'),
              'content' => $this->input->post('content'),
              'url' => $this->input->post('url'),
              'ord' => $this->input->post('ord'),
              'display' => $this->input->post('display')
            );
            if(!empty($_POST['imgs'])){
            	$data['image']='/'.$_POST['imgs_url'][0].'/'.$_POST['imgs'][0];
            }
            // var_dump($_POST);die;
            $re=$this->notice_model->insert($data);
			if($re){
	            $message="添加成功";
	            $this->error($message,2);
	        }else{
	            $this->error("添加失败",2);
	        }								            
		 exit;
		}else{
		    $typeid = $this->uri->segment(5);
			if(!empty($typeid)){
		        $data['typeid']=$typeid;
		        if($data['typeid']=="0"){$data['typeid']=3;}
		    }else{		         
		        $data['typeid']=3;
		    }
			$data['gettree']=$gettree=$this->notice_model->gettree($data['typeid']);
			$data['notice']=array (
			  'id' => NULL,
              'pid' => '',
              'title' => '',
              'description' => '',
              'content' => '',
              'url'=>'',
              'url' => '',
              'ord' => '',
              'display' => '',
            );
		    switch ($typeid) {
		    	case '3':
		    		$this->load->view('admin/notice/add_content.html',$data);
		    		break;
		    	case '1':
		    		$this->load->view('admin/notice/add_content_img.html',$data);
		    		break;
		    	case '2':
		    		$this->load->view('admin/notice/add_content_url.html',$data);
		    		break;
		    	case '21':
		    	    $this->load->view('admin/notice/add_fenxian_photo.html',$data);
		    	    break;
		    	case '12':
		    		$this->load->view('admin/notice/add_content_ad.html',$data);
		    		break;		    				    		
		    	case '11':
		    	    $this->load->view('admin/notice/add_content.html',$data);
		    	    break;	
		    	default:
		    		# code...
		    		break;
		    }
		    
		}
	}
    //修改内容页
    public function mod_content()
    {
    	if($_POST){
    		$data=array(
    			'id'=>$this->input->post('id'),
				'pid' => $this->input->post('pid'),
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'content' => $this->input->post('content'),
				'url' => $this->input->post('url'),
				'ord' => $this->input->post('ord'),
				'display' => $this->input->post('display'),
				'content' =>$this->input->post('content')
			    );
            if(!empty($_POST['imgs'])){
            	$data['image']='/'.$_POST['imgs_url'][0].'/'.$_POST['imgs'][0];
            }    		
    		$re=$this->notice_model->update($data);
			if($re){
	            $message="修改成功";
	            $this->error($message,2);
	        }else{
	            $this->error("修改失败",2);
	        }
    		exit;
    	}
    	 $id=$this->uri->segment(4);
	     $sql="SELECT * FROM bro_notice WHERE id=$id";
		 $query = $this->db->query($sql);
		 foreach ($query->result_array() as $row)
		 {
		     $data['notice']=$row;
		 }
		$data['gettree']=$this->notice_model->gettree_mods($data['notice']['pid']);
		//$data['gettree']=$this->notice_model->gettree_mod($data['notice']['pid']);
		switch ($data['notice']['pid']) {
		    	case '3':
		    		$this->load->view('admin/notice/add_content.html',$data);
		    		break;
		    	case '1':
		    		$this->load->view('admin/notice/add_content_img.html',$data);
		    		break;
		    	case '2':
		    		$this->load->view('admin/notice/add_content_url.html',$data);
		    		break;
		    	case '21':
		    	    $this->load->view('admin/notice/add_fenxian_photo.html',$data);
		    	    break;	
		    	case '12':
		    		$this->load->view('admin/notice/add_content_ad.html',$data);
		    		break;	
		    	case '11':
		    	    $this->load->view('admin/notice/add_content.html',$data);
		    	    break;	
		    	default:
		    		# code...
		    		break;
		}    	
    }
    public function del_content(){
    	 $id = $this->uri->segment(4);
    	 $re=$this->db->where('id',$id)->delete("notice");
    	 if($re){
                 $this->error("删除成功",2);
	     }else{
	            $this->error("删除失败",2);
	     }
    }    
    public  function  vue(){
        $id = $this->uri->segment(4);
        $data=array();
        $sql="SELECT * FROM bro_notice WHERE id=$id";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $data["notice"]=$row;
        }
        $this->load->view('admin/notice/vue.html',$data);
    }
    //逻辑判断获取数据
    public function mod_img_vue_get(){
        $id=$this->uri->segment(4);
        $data=array();
        $sql="SELECT * FROM bro_notice WHERE id=$id";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row)
        {
            $data["notice"]=$row;
        }  
        $background=$data["notice"]['image'];
        
        if(!empty($data["notice"]['api'])){
            $final=$data["notice"]['api'];
            $json_arr=json_decode($final,true);
            $vue_kuan_data=$json_arr;
            $vue_kuan_data['background']=$background;
        }else{
            $vue_kuan_data['background']=$background;
            $vue_kuan_data['ratio']=1;
            $vue_kuan_data['positions'][]=array(
                "x"=>34,
                "y"=>37,
                "width"=>241,
                "height"=>147
            );
        }
        echo json_encode ( $vue_kuan_data, JSON_UNESCAPED_UNICODE );
    }
    public function mod_img_vue_save(){
        $id = $this->uri->segment(4);
        if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            $final = $GLOBALS['HTTP_RAW_POST_DATA'];
        } else {
            $final = file_get_contents('php://input');
        }
        $json_arr=json_decode($final,true);
        unset($json_arr['background']);
        $json=json_encode ( $json_arr, JSON_UNESCAPED_UNICODE );
        $re=$this->db->where(array('id'=>$id))->update("notice",array("api"=>$json));
        if($re){
            echo 1;
        }else{
            echo '404';
        }
    }
	//回滚页
	public function back($id){
		$id = $this->uri->segment(4);
 		$data=array(
			'id'=>$id,
			'v'=>'1',
			'k'=>'status'
		);
		$re=$this->notice_model->ajax_update($data);
		if($re=="1"){
			$this->error("恢复成功",2);
		}else{
            $this->error("恢复失败",2);
        }
	}	
	//销毁页
	public function destroy(){
		if($_SESSION['admin_id']==1){
			$id = $this->uri->segment(4);
			$re=$this->db->where(array('id'=>$id))->delete('notice'); 
			if($re){
                 $this->error("删除成功",2);
	        }else{
	            $this->error("删除失败",2);
	        }
		}else{
			die;
		}	
	}
	//消息提示
	public function error($message,$num)
	{
      $this->notice_model->mess_redirect($message,$num);
	}
	//公用底部文件
	public function comm_footer(){
		$this->load->view('admin/notice/comm_footer.html');
	}
	//公用头部文件
	public function comm_head(){
		$this->load->view('admin/notice/comm_head.html');
	}	
}
