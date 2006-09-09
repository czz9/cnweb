<?php
	require("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>cn.bbs.*</title>
<link href="default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="float:right;padding-top:30px;padding-right:5px">
<?php if(isset($_GET["c"])) { ?><a href="index.php">English Version</a><?php } else { ?><a href="index.php?c">中文版</a><?php } ?>
</div>
<div id="divLogo"><a href="index.php"><img src="logo.gif" width="369" height="50" border="0" /></a></div>
<div id="divMenu">
	<br />
<?php
	if(isset($_GET["c"])) {
?>
	<div class="menutitle"><a href="index.php?c">首页</a></div>
	<div class="menutitle"><a href="index.php?c#Documentation_and_FAQs">文档</a></div>
	<ul>
		<li><a href="index.php?c&amp;file=charter">管理章程</a></li>
		<li><a href="index.php?c&amp;file=howto">技术手册</a></li>
		<li><a href="index.php?c&amp;file=manual">版主手册</a></li>
		<li><a href="index.php?c&amp;file=newgroup">开组须知</a></li>
		<li><a href="index.php?c&amp;file=faq">FAQ for news admin</a></li>
		<li><a href="index.php">English Version</a></li>
	</ul>
	<div class="menutitle">新闻组数据</div>
	<ul>
		<li><a href="index.php?c&amp;display=serverlist">服务器列表</a></li>
		<li><a href="index.php?c&amp;display=newsgroup">新闻组列表</a></li>
	</ul>
	<div class="menutitle"><a href="http://panel.cn-bbs.org/">转信申请面板</a></div>
	<div align="center">
		<form action="http://panel.cn-bbs.org/loginout.php" method="post" id="frmPanel">
			登录名称：<input type="text" name="name" maxlength="16" class="loginbox" /><br />
			登录密码：<input type="password" name="password" maxlength="40" class="loginbox" /><br />
			<div style="margin-top:10px"><input type="submit" value="登录" class="loginbtn" />
			<input type="button" value="注册" class="loginbtn" onclick="location.href='http://panel.cn-bbs.org/register.php';" />
			<input type="button" value="取回密码" class="loginbtn" onclick="location.href='http://panel.cn-bbs.org/lostpw.php';" /></div>
		</form>
	</div>
<?php
	}
	else {
?>
	<div class="menutitle"><a href="index.php">Home</a></div>
	<ul>
		<li><a href="index.php#Welcome">Welcome</a></li>
		<li><a href="index.php#Overview">Overview</a></li>
		<li><a href="index.php#Current_Policy">Current Policy</a></li>
		<li><a href="index.php#Documentation_and_FAQs">Documentation and FAQs</a></li>
		<li><a href="index.php#Current_Activities">Current Activities</a></li>
		<li><a href="index.php#Newsgroup_Creation_Schedule">Newsgroup Creation Schedule</a></li>
		<li><a href="index.php#Newsgroup_Deletion_Schedule">Newsgroup Deletion Schedule</a></li>
		<li><a href="index.php#Thanks_and_Credits">Thanks and Credits</a></li>
		<li><a href="index.php?c">简体中文版主页</a></li>
	</ul>
	<div class="menutitle"><a href="index.php?c"><a href="http://panel.cn-bbs.org/">Login</a></div>
	<div align="center">
		<form action="http://panel.cn-bbs.org/loginout.php" method="post" id="frmPanel">
			Username：<input type="text" name="name" maxlength="16" class="loginbox" /><br />
			Password：<input type="password" name="password" maxlength="40" class="loginbox" /><br />
			<div style="margin-top:10px"><input type="submit" value="Login" class="loginbtn" />
			<input type="button" value="Register" class="loginbtn" onclick="location.href='http://panel.cn-bbs.org/register.php';" />
			<input type="button" value="Forget Password?" class="loginbtn" onclick="location.href='http://panel.cn-bbs.org/lostpw.php';" /></div>
		</form>
	</div>
<?php
	}
?>
</div>
<div id="divMain">
