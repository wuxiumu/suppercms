<?php
/**
 * Description:管理员模型
 * User: MeiYouFan
 * Date: 2018/08/19
 * Time: 08:19
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model{
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
     * 后台管理员登录方法
     * 
     * @var  username  管理员账号名称
     * @var  userpwd  管理员密码（MD5加密）
     * @return array 
     */
    public function adminLogin($username,$userpwd)
    {
        $where=array("username"=>$username,"userpwd"=>$userpwd);
        $data['admin']= $this->db
        ->select('*')
        ->where($where)
        ->from('admin')
        ->get()
        ->row_array();
        $data["admin_group"]= $this->db
        ->select('*')
        ->where(array('id'=>$data['admin']['gid']))
        ->from('group')
        ->get()
        ->row_array();
        $data["admin_info"]= $this->db
        ->select('*')
        ->where(array('pid'=>$data['admin']['id']))
        ->from('admin_info')
        ->get()
        ->row_array();
        return $data;
    }

    ##get
    /**
     * 获取后台角色信息
     * get获取用户信息 
     * @var  id  管理员表id
     * @return array
     */         
    public function getAdminInfo($id)
    {
        $data['admin']= $this->db
        ->select('*')
        ->where(array('id'=>$id))
        ->from('admin')
        ->get()
        ->row_array();
        $data["admin_group"]= $this->db
        ->select('*')
        ->where(array('id'=>$data['admin']['gid']))
        ->from('group')
        ->get()
        ->row_array();
        $data["admin_info"]= $this->db
        ->select('*')
        ->where(array('pid'=>$data['admin']['id']))
        ->from('admin_info')
        ->get()
        ->row_array();
        return $data;
    }     
    /**
     * 获取后台角色列表
     * 通过 where get获取用户信息列表
     * @var  id  管理员表id
     * @return array
     */
    public function getAdminList($where="",$perPage=0,$offset=5,$like,$order_by="")
    {
        return $this->db->select("*")
            ->where($where)
            ->like('username', $like)
            ->from('admin')
            ->limit($perPage, $offset)
            ->get()
            ->result_array();
    }
    /**
     *  获取管理员组
     *  获取所有状态 正常 status=1
     * @var  admin 
     * @return array
     */ 
    public  function getAdmingroupInfo($where)
    {
        $data["admin_group"]= $this->db
        ->select('*')
        ->where(array("pid"=>"1","status"=>"1"))
        ->from('group')
        ->get()
        ->result_array();
        return $data;
    }
    /**
     *  获取管理员组
     *  通过id 获取
     * @var  admin 
     * @return array
     */     
    public  function AdmingroupInfo($id)
    {
        $data["admin_group"]= $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('group')
        ->get()
        ->result_array();
        return $data;
    } 
    
    ##add
    /**
     * 角色添加
     *  
     * @var  admin 
     * @return bool
     */
    public function addAdminInfo($admin){
        return $this->db->insert("admin",$admin);
    }
    /**
     *  组添加
     *  
     * @var  admin 
     * @return bool
     */
    public function addAdmingroup($admin){
        return $this->db->insert("group",$admin);
    }

    ##mod
    /**
     *  角色更新 
     *  
     * @var  admin 
     * @return bool
     */
    public function updatelAdminInfo($data,$id){
        if(!empty($data['disable_'])){
            $admin=$this->getAdminInfo($id);
            if($admin['admin']['disable']=='1'){
                $data['disable']='2';
            }else{
                $data['disable']='1';
            }
            unset($data['disable_']);
        }
        $user['id'] = $id;
        return $this->db->where($user)->update("admin",$data);
    }
    /**
     *  组更新 
     *  
     * @var  admin 
     * @return bool
     */    
    public function updatelAdmingroup($data,$id)
    {       
        $user['id'] = $id;
        return $this->db->where($user)->update("group",$data);
    } 
    
    ##del
    /**
     *  角色删除
     *  
     * @var  admin 
     * @return int
     */
    public function delAdminInfo($id)
    {
        $re = $this->db->delete('admin', array('id' => $id));
        if($re){
            return 1;
        }else{ 
            return 2;
        }
    }
   
    ##html
    /**
     * 数组进行无限极分类-数据转换html
     * @var $tree：数据；$typeid：父类型
     * @return html
     */
    public function GetTree_arr_html($tree,$typeid)
    {
        $html='';
        foreach ($tree as $ke=>$row)
        {
           if($typeid==$row["id"]){
                $html.= "<option value='".$row['id']."'  title='".$row['id']."' selected>";
            }else{
                $html.= "<option value='".$row['id']."'  title='".$row['id']."'>";
            }  
            $title=$row['groupname'];
            $html.= $title;
            $html.= "</option>";
        }
        return $html;
    }
}