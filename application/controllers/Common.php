<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Home_Controller {

	public function index()
	{
		$data=[];
		$this->layout($data);
	}

	public function layout($data)
	{
		P($data);
		$this->head();
		$this->load->view('home/header.html');
		$this->load->view('home/home.html');
		$this->footer();
	}
	public function head()
	{
		$this->load->view('public/public_head.html');
	}	
	public function footer()
	{
		$this->load->view('home/footer.html');
		$this->load->view('public/public_foot.html');
	}
}
