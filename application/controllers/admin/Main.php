<?php
/**
 * Date: 2018/08/22
 * Time: 11:53
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends Admin_Controller {
    //默认页
    public function index()
    {
        $this->load->view('admin/index/index.html');
    }
    //展示头部
    public function top()
    {
        $this->load->view('admin/index/top.html');
    }
    //展示左侧菜单
    public function index_menu()
    {
        $this->load->view('admin/index/index_menu.html');
    }
    //展示左侧菜单
    public function swich()
    {
        $this->load->view('admin/index/swich.html');
    }
    //展示中西内容
    public function home()
    {        
        $this->load->view('admin/index/home.html');
    }
    //展示底部
    public function bottom()
    {
        $this->load->view('admin/index/bottom.html');
    }
}
