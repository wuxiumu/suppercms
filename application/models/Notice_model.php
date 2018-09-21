<?php
/**
 * Description:前端文章模型
 * User: MeiYouFan
 * Date: 2018/08/22
 * Time: 13:38
 * get query result add update del
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_model extends CI_Model{
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
     *  获取组
     *  获取所有状态 正常 status=1
     * @var  admin 
     * @return array
     */ 
    public function noticeGroupResult($where=''){
    	 $data["notice"]= $this->db
        ->select('*')
        ->order_by('ord','asc')
        ->where($where)
        ->from('notice')
        ->get()
        ->result_array();
        return $data;
    }
    /**
     * 添加
     *  
     * @var  notice 
     * @return bool
     */
    public function addNotice($notice){
        return $this->db->insert("notice",$notice);
    }
    /**
     * 获取信息
     * @var  id  notice
     * @return array
     */         
    public function getNotice($id)
    {
        $data['notice']= $this->db
        ->select('*')
        ->where(array('id'=>$id))
        ->from('notice')
        ->get()
        ->row_array();
        return $data;
    }
    /**
     *  更新 
     *  
     * @var  admin 
     * @return bool
     */
    public function updateNotice($data,$id){
        if(!empty($data['display_'])){
            $d=$this->getNotice($id);
            $display=$d['notice']['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
        $notice['id'] = $id;
        return $this->db->where($notice)->update("notice",$data);
    }

    public function GetTree_arr_html($tree,$typeid='0')
    {
        $html='';      
        foreach ($tree as $ke=>$row)
        {
           if($typeid==$row["id"]){
                $html.= "<option value='".$row['id']."'  title='".$row['id']."' selected>";
            }else{
                $html.= "<option value='".$row['id']."'  title='".$row['id']."'>";
            }  
            $title=$row['title'];
            $html.= $title;
            $html.= "</option>";
        }
        return $html;
    }
}