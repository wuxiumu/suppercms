<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader{

    protected $_theme = 'default/';

    //前台HTML模板位置
    public function home_html_path(){
        $this->_ci_view_paths = array(FCPATH . THEMES_HOME_DIR . $this->_theme	=> TRUE);
    }
    //前台商户HTML模板位置
    public function shop_html_path(){
        $this->_ci_view_paths = array(FCPATH . THEMES_SHOP_DIR . $this->_theme	=> TRUE);
    }

    //后台HTML模板位置
    public function admin_html_path(){
        //默认位置，不做设置
    }
}