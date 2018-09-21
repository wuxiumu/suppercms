<?php
/**
 * Description:栏目模型
 * User: MeiYouFan
 * Date: 2018/08/19
 * Time: 09:38
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Column_model extends CI_Model{
    /**
     * 构造函数
     *  
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /** 
     * 获取栏目信息
     * 
     * @var  pid  栏目父亲id
     * @var  status=1  正常状态
     * @return array 
     */
    public function type_id($pid)
    {
        $data['column']= $this->db
        ->select('*')
        ->where(array('pid'=>$pid,'status'=>'1'))
        ->from('column')
        ->get()
        ->result_array();
        return $data;
    }
    public function typeFindId($pid)
    {
    	$pdata=$this->get_column_info($pid);
        $data['column']= $this->db
        ->select('*')
        ->where(array('pid'=>$pdata['column']['pid'],'status'=>'1'))
        ->from('column')
        ->get()
        ->result_array();
        return $data;
    }
    /**
     * 获取栏目信息
     *  
     * @var  id  栏目表id
     * @return array
     */    
    public function get_column_info($id)
    {
        $data['column']= $this->db
        ->select('*')
        ->where(array('id'=>$id))
        ->from('column')
        ->get()
        ->row_array();
        return $data;
    }
     /**
     * 栏目信息获取
     *  
     * @var  data  需要添加的栏目信息
     * @return int
     */ 
    public function add_column($data)
    {
    	$columnData = $this->get_column_info($data['pid']);
    	$columnData['column']['pid'] = $data['pid'];
    	$columnData['column']['title'] = $data['title'];
    	$columnData['column']['description'] = $data['description'];
    	if($columnData['column']['pid']=='0'){
    		$path = '0';
    	}else{
    		$path = $columnData['column']['path'];
    	}
    	unset($columnData['column']['id']);
    	$this->db->trans_begin();
    	$this->db->insert('column', $columnData['column']);
    	$id_data=$this->db->insert_id();
    	$path=$path."-".$id_data;
    	$updateArray=array("path"=>$path);
    	$re=$this->db->where(array('id'=>$id_data))->update("column",$updateArray);    	
		if($re){
		    $this->db->trans_commit();
            $message="添加成功";
            return 1;
        }else{
            $this->db->trans_rollback();
            return 2;
        }
    }
    /**
     * 栏目删除
     *  
     * @var  data  需要添加的栏目信息
     * @return int
     */
    public function action_column_del($id)
    {
 		$re = $this->db->delete('column', array('id' => $id));
 		if($re){
        	return 1;
        }else{ 
        	return 2;
        }
    }
     /**
     * 栏目更新信息
     *  
     * @var  id  需要栏目更新Id
     * @var  data  需要栏目更新信息
     * @return int
     */
    public function action_column_update($data,$id)
    {
    	if(!empty($data['display_'])){
    		$column = $this->get_column_info($id);
    		if($column['column']['display']=='1'){
    			$data['display']=2;
    		}else{
    			$data['display']=1;
    		}
            unset($data['display_']);
    	}
        $re = $this->db->where(array('id'=>$id))->update("column",$data);
        if($re){
        	return 1;
        }else{ 
        	return 2;
        }
    }
    /**
	 * 数组进行无限极分类-数据转换html
	 * @var $typeid：父类型
	 * @return html
	 */
	public function gettree_main($typeid)
    {
		$query = $this->db->query('SELECT *,concat(path,"-",id) as abspath FROM bro_column where status=1 order by ord asc');
	    $html='<option value="0" path="0" title="0">全部</option>';
	    foreach ($query->result_array() as $row)
	    {
	    	if($typeid==$row['id']){
	    		$html.= "<option value='".$row['id']."' path='".$row['path']."' title='".$row['id']."' selected>";

	    	}else{
	            $html.= "<option value='".$row['id']."' path='".$row['path']."' title='".$row['id']."'>";
	    	}
	        $title=$row['title'];
	        $html.= $title;
	        $html.= "</option>";
	    }
	    return $html;
	}
	/**
	 * 数组进行无限极分类
	 * @var array
	 * @return text
	 */
	private function GetTree_arr($arr,$pid,$step)
    {
	    global $tree;
	    foreach($arr as $key=>$val) {
	        if($val['pid'] == $pid) {
	            $flg = str_repeat('└―',$step);
	            $val['title'] = $flg.$val['title'];
	            $val['step']=$step;
	            $tree[] = $val;
	            unset($arr[$key]);
	            $this->GetTree_arr($arr , $val['id'],$step+1);
	        }
	    }
	    return $tree;
	}
	/**
	 * 数组进行无限极分类-获取数据
	 * @var $pid:父类；$step：步骤
	 * @return array
	 */
	public function GetTree_data($pid,$step)
    {
	    $arr = $this->db->get('column')->result_array();
	    return $this->GetTree_arr($arr,$pid,$step);
	}
	/**
	 * 数组进行无限极分类-数据转换html
	 * @var $tree：数据；$typeid：父类型
	 * @return html
	 */
	public function GetTree_arr_html($tree,$typeid)
    {
	    $html='';
	    $html.= "<option value='0' path='0' title='0'>顶级栏目</option>";
	    foreach ($tree as $row)
	    {
	        if($typeid==$row["id"]){
	            $html.= "<option value='".$row['id']."' path='".$row['path']."' title='".$row['id']."' selected>";
	        }else{
	            $html.= "<option value='".$row['id']."' path='".$row['path']."' title='".$row['id']."'>";
	        }
	        $title=$row['title'];
	        $html.= $title;
	        $html.= "</option>";
	    }
	    return $html;
	}
}