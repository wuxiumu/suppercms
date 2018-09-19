<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>瑞普设计 欢迎你</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>欢迎来到瑞普设计</h1>

	<div id="body">
		<p>您正在查看的页面为动态页面：</p>

		<p>如果您想编辑此页面，您会发现它位于：</p>
		<code>application/views/welcome_message.php</code>

		<p>此页面的相应控制器位于：</p>
		<code>application/controllers/Welcome.php</code>

		<p>这里有开发手册 <a href="https://codeigniter.org.cn/user_guide/">用户指南 </a>.
		<a href="http://www.w3school.com.cn/index.html">W3cSchool </a>.
		<a href="http://www.runoob.com/">菜鸟教程 </a>.
		
		</p>
		<a href="/yhtz.php">服务器配置信息</a>
		<p>管理员<a href="/index.php/admin/login/index/">登录</a>.</p>
		<p>调试<a href="/tests/index.php?rp=2">【开始】</a></p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>