<?php
/**
 * Description:资源模型
 * User: MeiYouFan
 * Date: 2018/08/22
 * Time: 13:38
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Source_model extends CI_Model{
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
    public function typeId($pid){
        $data['column']= $this->db
        ->select('*')
        ->where(array('pid'=>$pid,'status'=>'1'))
        ->from('column')
        ->get()
        ->result_array();
        return $data;
    }
    public function typeFindId($pid){
    	$pdata=$this->getColumnInfo($pid);
        $data['column']= $this->db
        ->select('*')
        ->where(array('pid'=>$pdata['column']['pid'],'status'=>'1'))
        ->from('column')
        ->get()
        ->result_array();
        return $data;
    }
    /**
     * 获取资源信息
     *  
     * @var  id   
     * @return array
     */    
    public function getSourceInfo($id){
        $data['source']= $this->db
        ->select('*')
        ->where(array('id'=>$id))
        ->from('sources')
        ->get()
        ->row_array();
        return $data;
    }
    public function getSourceInfo_($id){
        $data['sourceinfo']= $this->db
        ->select('*')
        ->where(array('id'=>$id))
        ->from('source_info')
        ->get()
        ->row_array();
        return $data;
    }
    /**
     * 获取栏目信息
     *
     * @var  id
     * @return array
     */
    public function getColumnInfo($id){
        $data['column']= $this->db
        ->select('*')
        ->where(array('id'=>$id))
        ->from('column')
        ->get()
        ->row_array();
        return $data;
    }
     /**
     * 素材信息添加
     *  
     * @var  data  需要添加的素材信息
     * @return int
     */ 
    public function addSource($data){
        $path=0;
        if($data['pid']=='0'){
            $columnData = $this->getColumnInfo($data['pid']);
            if(!empty($columnData['column']['path'])){
                $path=$columnData['column']['path'];
            } 
        }else{
            $columnData = $this->getColumnInfo($data['pid']);
            $path=$columnData['column']['path'];
        }
        $data['create_time']=time();
        $data['path']=$path;
    	$this->db->trans_begin();
    	$this->db->insert('sources', $data);
    	$id_data=$this->db->insert_id();
    	$path=$path."-".$id_data;
    	$updateArray=array("path"=>$path);
    	$re=$this->db->where(array('id'=>$id_data))->update("sources",$updateArray);    	
		if($re){
		    $this->db->trans_commit();
            $message="添加成功";
            return 1;
        }else{
            $this->db->trans_rollback();
            return 2;
        }
    }
    public function addSourceinfo($data){
        $path=0;
        $columnData = $this->getSourceInfo($data['source_id']);
        if(!empty($columnData['source']['path'])){
            $path=$columnData['source']['path'];
        }
        $data['create_time']=time();
        $data['path']=$path;
        $this->db->trans_begin();
        $this->db->insert('source_info', $data);
        $id_data=$this->db->insert_id();
        $path=$path."-".$id_data;
        $updateArray=array("path"=>$path);
        $re=$this->db->where(array('id'=>$id_data))->update("source_info",$updateArray);
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
     * 资源删除
     *  
     * @var  data  需要添加的栏目信息
     * @return int
     */
    public function actionSourceDel($id){
 		$re = $this->db->delete('sources', array('id' => $id));
 		if($re){
        	return 1;
        }else{ 
        	return 2;
        }
    }
    public function actionSourceinfoDel($id){
        $re = $this->db->delete('source_info', array('id' => $id));
        if($re){
            return 1;
        }else{
            return 2;
        }
    }
     /**
     * 资源更新信息
     *  
     * @var  id  需要资源更新Id
     * @var  data  需要资源更新信息
     * @return int
     */
    public function actionSourceUpdate($data,$id){
    	if(!empty($data['display_'])){
    		$source = $this->getSourceInfo($id);
    		if($source['source']['display']=='1'){
    			$data['display']=2;
    		}else{
    			$data['display']=1;
    		}
            unset($data['display_']);
    	}
    	$data['update_time']=time();
        $re = $this->db->where(array('id'=>$id))->update("sources",$data);
        if($re){
        	return 1;
        }else{ 
        	return 2;
        }
    }
    public function actionSourceinfoUpdate($data,$id){
        if(!empty($data['display'])){
            $source = $this->getSourceInfo_($id);
            if($source['sourceinfo']['display']=='1'){
                $data['display']=2;
            }else{
                $data['display']=1;
            }
        }
        $data['update_time']=time();
        $re = $this->db->where(array('id'=>$id))->update("source_info",$data);
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
	public function gettree_main($typeid){
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
	private function GetTree_arr($arr,$pid,$step){
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
	public function GetTreeData($pid,$step){
	    $arr = $this->db->get('column')->result_array();
	    return $this->GetTree_arr($arr,$pid,$step);
	}
	/**
	 * 数组进行无限极分类-数据转换html
	 * @var $tree：数据；$typeid：父类型
	 * @return html
	 */
	public function GetTreeArrhtml($tree,$typeid){
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
	/**
	 * 获取资源info信息
	 *
	 * @var  id
	 * @return array
	 */
	public function sourceInfo($id){
	    $data['sourceinfo']= $this->db
	    ->select('*')
	    ->where(array('id'=>$id))
	    ->from('source_info')
	    ->get()
	    ->row_array();
	    return $data;
	}
	public function findSourceInfo($source_id){
	    $data['sourceinfo']= $this->db
	    ->select('*')
	    ->where(array('source_id'=>$source_id))
	    ->from('source_info')
	    ->get()
	    ->row_array();
	    return $data;
	}
}