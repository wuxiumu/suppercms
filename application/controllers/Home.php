<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Home_Controller {
	//首页
	public function index()
	{
		$data=[];
		$this->layout($data);
	}
	//容器
	public function layout($data)
	{	 
	    ##首页内容1.轮播，(2.图文)
	    $where = 'pid=1 AND display=1 AND status=1';
		$sql = "SELECT * FROM bro_notice WHERE ".$where." ORDER BY ord ASC";
		$res_sql = $this->db->query($sql);
		$data['carousel']  = $res_sql->row_array();      		
		##首页导航
		$this->head(array("current_page_id"=>64));
		$this->load->view('home/home.html',$data);
		##底部导航（友情链接,基本信息）
		$this->footer(array("links"=>1,"basic"=>1));
	}
	//头部导航
	public function head($arr)
	{
		$current_page_id=$arr['current_page_id'];
		##基本信息 
		if(!empty($arr['current_page']) && $arr['current_page']=='companyProfile'){
			$sql = "SELECT title,keyword,description FROM bro_sources WHERE id=".$arr['id'];
			$res_sql = $this->db->query($sql);
			$data['base']  = $res_sql->row_array();			 
		}else{
		    $this->load->library("Baseset");
		    $base = New Baseset();
		    $data["base"] = $base->getsystem();
		    $data['base']['title']="首页"; 	
		}                       

		$where="path like '%0-63-%' AND pid=63 AND display=1 AND status=1";
        $sql = "SELECT * FROM bro_column WHERE ".$where." ORDER BY ord ASC";
        $res_sql = $this->db->query($sql);
        $data['column']  = $res_sql->result_array();
        $data['current_page'] = $current_page_id;
        $this->load->view('public/public_head.html',$data);
		$this->load->view('home/header.html',$data);
	}
	//底部导航	
	public function footer($arr)
	{
		##友情链接
		$where = 'pid=2';
		$sql = "SELECT * FROM bro_notice WHERE ".$where." ORDER BY ord ASC";
		$res_sql = $this->db->query($sql);
		$data['notice']  = $res_sql->result_array();
		##基本信息                       
	    $this->load->library("Baseset");
	    $base = New Baseset();
	    $data["base"] = $base->getsystem();
	    $data['compose'] = $arr;	
	    $this->load->view('home/footer.html',$data);	 
		$this->load->view('public/public_foot.html',$data); 

	}
	//公司介绍
	public function companyProfile()
	{   
		$id=$this->uri->segment(3);
		if(empty($id)){
		 	$id=198;
		} 
	    $pid=65;
	    $where = "pid=$pid AND display=1 AND status=1";
		$sql = "SELECT * FROM bro_sources WHERE ".$where." ORDER BY ord ASC";
		$res_sql = $this->db->query($sql);
		$data['sources']  = $res_sql->result_array();
		$data['current_page'] = $id;	   
		##首页导航
		$this->head(array("current_page_id"=>65,"current_page"=>"companyProfile","id"=>$id));
		$this->load->view('home/about.html',$data);
		##底部导航（友情链接,基本信息）
		$this->footer(array("links"=>1,"basic"=>1));
	}
	//业务领域
	public function businessArea(){
		$id=$this->uri->segment(3);
		if(empty($id)){			
		 	$id=204;
		}
	    $pid=65;
	    $where = "pid=72 AND display=1 AND status=1";
		$sql = "SELECT * FROM bro_sources WHERE ".$where." ORDER BY ord ASC";
		$res_sql = $this->db->query($sql);
		$data['sources']  = $res_sql->result_array();
		$data['current_page'] = $id;	
	    ##P($data);	  
		$where="path like '%0-63-66-%' AND display=1 AND status=1";
        $sql = "SELECT * FROM bro_column WHERE ".$where." ORDER BY ord ASC";
        $res_sql = $this->db->query($sql);
        $data['column']  = $res_sql->result_array();
        $data['current_column_id']=72;	
		##首页导航
		$this->head(array("current_page_id"=>66,"current_page"=>"companyProfile","id"=>$id));
		$data['sources_dispaly_navigate']='1';
		$breadLine=[];
		$data['column3']=[];
		$data['column4']=[];
		if($id!='204' && $id!='205'  && $id!='206'){
			$data['current_column_id']=$this->uri->segment(4);;
		    $where = "id=$id AND display=1 AND status=1";
			$sql = "SELECT * FROM bro_sources WHERE ".$where." ORDER BY ord ASC";
			$res_sql = $this->db->query($sql);
			$data['sources']  = $res_sql->result_array();
			$data['sources_dispaly_navigate']='2';
			$sql = "SELECT a.title,a.id,b.title as b_title,b.id as b_id,b.url FROM bro_sources a,bro_column b WHERE a.id=".$id." AND a.pid=b.id";
			$res_sql = $this->db->query($sql);
			$breadLine  = $res_sql->row_array();
			$data['column3']['title']=$breadLine['title'];
			$data['column3']['url']=site_url('home/businessArea/').$id;
			$data['column4']['title']='';
			$data['column4']['url']='';
		}		
		if($id=='204' || $id=='205' || $id=='206'){
			$sql = "SELECT a.title,a.id,b.title as b_title,b.id as b_id,b.url FROM bro_sources a,bro_column b WHERE a.id=".$id." AND a.pid=b.id";
			$res_sql = $this->db->query($sql);
			$breadLine  = $res_sql->row_array();
			$data['column3']['title']=$breadLine['b_title'];
			$data['column3']['url']=$breadLine['url'];
			$data['column4']['title']=$breadLine['title'];
			$data['column4']['url']=site_url('home/businessArea/').$breadLine['id'];
		}
		$this->load->view('home/gqtz.html',$data);
		##底部导航（友情链接,基本信息）
		$this->footer(array("links"=>0,"basic"=>1));
	}
	//资讯动态
	public function dynamicInformation(){
		$id=$this->uri->segment(3);
		if(empty($id)){  
	       	$data['message'] = '页面找不到';
	        $data['url']='/home/dynamicInformation/70';
	        redirect($data['url']); 
       }
	    $pid=67;
	    $where = "pid=$pid AND display=1 AND status=1";
		$sql = "SELECT * FROM bro_column WHERE ".$where." ORDER BY ord ASC";
		$res_sql = $this->db->query($sql);
		$data['column']  = $res_sql->result_array();
		$data['current_column_id'] = $id;
        $table_datas = $this->table_datas(array("pid"=>$id));
        ##P($table_datas);
		##导航
		$this->head(array("current_page_id"=>67));
		$this->load->view('home/yndt.html',$data);
		$this->load->view('home/tables.html',$table_datas);
		##底部导航（友情链接,基本信息）
		$this->footer(array("links"=>0,"basic"=>1));
	}
    public function table_datas($arr)
    {

    	$pid = $arr['pid'];
        if($pid=='' || $pid=='0' || $pid==NULL){
            $where=array('status'=>1);
        }else{
            $where=array('pid'=>$pid,'status'=>1);
        }        
        $this->load->library('pagination');
        $perPage = 2;
        //配置项设置
        $config['base_url'] = site_url('home/dynamicInformation/').$pid;
        $config['total_rows'] = $this->db->where($where)->count_all_results('sources');
        $config['per_page'] = $perPage;
        $config['uri_segment'] = 4;//url的位置获取分页的参数
        $config['first_link'] = '第一页';
        $config['prev_link'] = '< 上一页';
        $config['next_link'] = '下一页 >';
        $config['last_link'] = '最后一页';
        ##自定义当前页面链接 <a href="#" class="z-crt">2</a>
        $config['cur_tag_open'] = '<a href="#" class="z-crt">';
        $config['cur_tag_close'] = '</a>';
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $offset = $this->uri->segment(4);
        $this->db->limit($perPage, $offset);
        $data['links_offset']=$offset;
        $data["sources"]= $this->db
        ->select('*')
        ->order_by('id','asc')
        ->where($where)
        ->from('sources')
        ->limit($perPage, $offset)
        ->get()
        ->result_array();
        return $data;
    }
    //新闻详情
    public function news(){
       $id = $this->uri->segment(3);
       if(empty($id)){  
	       	$data['message'] = '页面找不到';
	        $data['url']='/home/dynamicInformation/70';
	        redirect($data['url']); 
       }
	   $where = "id=$id AND display=1 AND status=1";
	   $sql = "SELECT * FROM bro_sources WHERE ".$where." ORDER BY ord ASC";
	   $res_sql = $this->db->query($sql);
	   $data['sources']  = $res_sql->row_array();	   
       ##导航
	   $this->head(array("current_page_id"=>67));
       $this->load->view('home/news.html',$data);
       ##底部导航（友情链接,基本信息）
	   $this->footer(array("links"=>0,"basic"=>1));
    }
	//诚聘英才
    public function talentsWanted(){
    	$data=[];
    	##首页导航
		$this->head(array("current_page_id"=>68));
    	$this->load->view('home/cpyc.html',$data);
    }
	//联络我们
    public function contactUs(){
    	$data=[];
    	##首页导航
		$this->head(array("current_page_id"=>69));
    	$this->load->view('home/lxwm.html',$data);
    	##底部导航（友情链接,基本信息）
		$this->footer(array("links"=>0,"basic"=>1));
    }
    //地图
    public function map(){
    	$data=[];
    	$this->load->view('home/map.html',$data);
    }
}
