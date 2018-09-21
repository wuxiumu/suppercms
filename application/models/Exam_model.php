<?php
/**
 * Description:考试模型
 * User: MeiYouFan
 * Date: 2018/09/11
 * Time: 08:19
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam_model extends CI_Model{
    /**
     * 构造函数 
     *  
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /*************************************************************** 
     * 考试模型 题型管理
     * 
     **************************************************************/ 
    /** 
     * 获取题型信息
     * 
     * @var   pid  栏目父亲id
     * @param string $Where
     * @var   status=1  正常状态
     * @return array 
     */
    public function reslutQuestype($Where)
    {
        $data['questype']= $this->db
        ->select('*')
        ->where($Where)
        ->from('questype')
        ->get()
        ->result_array();
        return $data;
    }
    public function getQuestype($id)
    {
        $data = $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('questype')
        ->get()
        ->row_array();
        return $data;
    }
    /** 
     * 添加题型信息
     * 
     * @return bool 
     */
    public function addQuestype($data)
    {
        return $this->db->insert("questype",$data);
    }
    /** 
     * 修改题型信息
     * 
     * @return bool 
     */
    public function updateQuestype($data,$id)
    {
    	if(!empty($data['display_'])){
            $d=$this->getQuestype($id);
            $display=$d['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
		$questype['id'] = $id;
        return $this->db->where($questype)->update("questype",$data);        
    }
    /** 
     * 删除题型信息
     * 
     * @return bool 
     */
    public function destroyNotice($id){
    	return $this->db->delete('questype', array('id' => $id));
    }
    /*************************************************************** 
     * 考试模型 科目管理
     * 
     **************************************************************/ 
    /** 
     * 获取科目信息
     * 
     * @param string $Where
     * @var   status=1  正常状态
     * @return array 
     */
    public function reslutSubject($Where)
    {
        $data['subject']= $this->db
        ->select('*')
        ->where($Where)
        ->from('subject')
        ->get()
        ->result_array();
        return $data;
    }
    public function getSubject($id)
    {
        $data = $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('subject')
        ->get()
        ->row_array();
        return $data;
    }
    /** 
     * 添加题型信息
     * 
     * @return bool 
     */
    public function addSubject($data)
    {
        return $this->db->insert("subject",$data);
    }
    /** 
     * 修改题型信息
     * 
     * @return bool 
     */
    public function updateSubject($data,$id)
    {
        if(!empty($data['display_'])){
            $d=$this->getSubject($id);
            $display=$d['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
        $subject['id'] = $id;
        return $this->db->where($subject)->update("subject",$data);        
    }
    /** 
     * 删除科目信息
     * 
     * @return bool 
     */
    public function destroySubject($id){
        return $this->db->delete('subject', array('id' => $id));
    }
    /*************************************************************** 
     * 考试模型 章节管理
     * 
     **************************************************************/ 
    /** 
     * 获取章节信息
     * 
     * @param string $Where
     * @var   status=1  正常状态
     * @return array 
     */
    public function reslutSections($Where)
    {
        $data['sections']= $this->db
        ->select('*')
        ->where($Where)
        ->from('sections')
        ->get()
        ->result_array();
        return $data;
    }
    public function getSection($id)
    {
        $data = $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('sections')
        ->get()
        ->row_array();
        return $data;
    }
    /** 
     * 添加章节信息
     * 
     * @return bool 
     */
    public function addSection($data)
    {
        return $this->db->insert("sections",$data);
    }
    /** 
     * 修改章节信息
     * 
     * @return bool 
     */
    public function updateSection($data,$id)
    {
        if(!empty($data['display_'])){
            $d=$this->getSection($id);
            $display=$d['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
        $section['id'] = $id;
        return $this->db->where($section)->update("sections",$data);        
    }
    /** 
     * 删除章节信息
     * 
     * @return bool 
     */
    public function destroySection($id){
        return $this->db->delete('sections', array('id' => $id));
    }
    /*************************************************************** 
     * 考试模型 知识点管理
     * 
     **************************************************************/ 
    /** 
     * 获取知识点信息
     * 
     * @param string $Where
     * @var   status=1  正常状态
     * @return array 
     */
    public function reslutKnows($Where)
    {
        $data['knows']= $this->db
        ->select('*')
        ->where($Where)
        ->from('knows')
        ->get()
        ->result_array();
        return $data;
    }
    public function getKnow($id)
    {
        $data = $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('knows')
        ->get()
        ->row_array();
        return $data;
    }
    /** 
     * 添加知识点信息
     * 
     * @return bool 
     */
    public function addKnow($data)
    {
        return $this->db->insert("knows",$data);
    }
    /** 
     * 修改知识点信息
     * 
     * @return bool 
     */
    public function updateKnow($data,$id)
    {
        if(!empty($data['display_'])){
            $d=$this->getSection($id);
            $display=$d['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
        $know['id'] = $id;
        return $this->db->where($know)->update("knows",$data);        
    }
    /** 
     * 删除知识点信息
     * 
     * @return bool 
     */
    public function destroyKnow($id){
        return $this->db->delete('knows', array('id' => $id));
    }
    /*************************************************************** 
     * 考试模型 普通试题管理
     * 
     **************************************************************/ 
    /** 
     * 获取普通试题信息
     * 
     * @param string $Where
     * @var   status=1  正常状态
     * @return array 
     */
    public function reslutQuestions($Where)
    {
        $data['questions']= $this->db
        ->select('*')
        ->where($Where)
        ->from('questions')
        ->get()
        ->result_array();
        return $data;
    }
    public function getQuestion($id)
    {
        $data = $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('questions')
        ->get()
        ->row_array();
        return $data;
    }
   /** 
     * 添加普通试题信息信息
     * 
     * @return bool 
     */
    public function addQuestion($data)
    {
        return $this->db->insert("questions",$data);
    }
    /** 
     * 修改普通试题信息信息
     * 
     * @return bool 
     */
    public function updateQuestion($data,$id)
    {
        if(!empty($data['display_'])){
            $d=$this->getQuestion($id);
            $display=$d['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
        $question['id'] = $id;
        return $this->db->where($question)->update("questions",$data);        
    }
    /** 
     * 删除普通试题信息
     * 
     * @return bool 
     */
    public function destroyQuestion($id){
        return $this->db->delete('questions', array('id' => $id));
    }
    /*************************************************************** 
     * 考试模型 试卷列表管理
     * 
     **************************************************************/ 
    /** 
     * 获取试卷信息
     * 
     * @param string $Where
     * @var   status=1  正常状态
     * @return array 
     */
    public function reslutExams($Where)
    {
        $data['exams']= $this->db
        ->select('*')
        ->where($Where)
        ->from('exams')
        ->get()
        ->result_array();
        return $data;
    }
    public function getExam($id)
    {
        $data = $this->db
        ->select('*')
        ->where(array("id"=>$id))
        ->from('exams')
        ->get()
        ->row_array();
        return $data;
    }
    /** 
     * 添加试卷信息
     * 
     * @return bool 
     */
    public function addExam($data)
    {
        return $this->db->insert("exams",$data);
    }
    /** 
     * 修改试卷信息
     * 
     * @return bool 
     */
    public function updateExam($data,$id)
    {
        if(!empty($data['display_'])){
            $d=$this->getExam($id);
            $display=$d['display'];
            if($display=='1'){
                $data['display']='2';
            }else{
                $data['display']='1';
            }
            unset($data['display_']);
        }
        $exam['id'] = $id;
        return $this->db->where($exam)->update("exams",$data);        
    }
    /** 
     * 删除试卷信息
     * 
     * @return bool 
     */
    public function destroyExam($id){
        return $this->db->delete('exams', array('id' => $id));
    }


    ##html
    /**
     * 数组进行无限极分类-数据转换html
     * @var $tree：数据；$typeid：父类型
     * @return html
     */
    public function selecthtml($tree,$typeid){
        $html='';
        foreach ($tree as $ke=>$row)
        {
           if($typeid==$row["id"]){
                $html.= "<option value='".$row['id']."'  title='".$row['id']."' selected>";
            }else{
                $html.= "<option value='".$row['id']."'  title='".$row['id']."'>";
            }  
            $title=$row['name'];
            $html.= $title;
            $html.= "</option>";
        }
        return $html;
    }
    public function checkhtml($tree,$arr){        
        $html='';
        foreach ($tree as $ke=>$row)
        {   
            if(empty($arr)){
                $html.="<input type='checkbox' name='setting[]' value='".$row['id']."'>";    
            }else{
                if(in_array($row['id'], $arr)){
                    $html.="<input type='checkbox' name='setting[]' value='".$row['id']."' checked=''>";
                }else{
                    $html.="<input type='checkbox' name='setting[]' value='".$row['id']."'>";    
                }
            }            
            $title=$row['name'];
            $html.= $title;
            $html.= "</option>&nbsp;&nbsp;&nbsp;";
        }
        return $html;
    }
}   