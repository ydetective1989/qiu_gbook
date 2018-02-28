<?php
//************************ 系统函数设置 开始 ************************
//开启页面缓冲
ob_start('ob_gzhandler');				//开启页面缓冲
error_reporting( E_ERROR | E_WARNING | E_PARSE );//屏蔽错误提示
header('Content-Type:text/html;charset=utf-8');//网页编码避免输出乱码
date_default_timezone_set('Asia/Shanghai');
ini_set('session.gc_probability',5);
ini_set('session.gc_divisor',100);
ini_set('session.gc_maxlifetime',10800);
//************************ 系统函数设置 结束 ************************
define('DEBUG',true);					//是否启用DEBUG
define('S_ROOT',str_replace(strtolower(trim(substr(strrchr($_SERVER["PHP_SELF"], '/'),1))),"",$_SERVER["PHP_SELF"]));
define('CONFIG', './config/');			//配置文件路径
include(CONFIG."plugin.php");
include(CONFIG."dbpdo.php");
$db = new dbpdo();
$db->dbType='mysql';
$db->connectNum='logs';
$db->configFile = CONFIG."dbconfig.php";	//数据库配置
include(CONFIG."cookie.php");
$cookie = new cookie();
function dialog($msgbox)
{
    $msgbox = $msgbox;
    include("msgbox.dialog.php");
    exit;
}

function msgbox($urlto="",$msgbox="",$timeout="3000")
{
    $urlto = $urlto;
    $msgbox= $msgbox;
    $timeout = $timeout;
    include("msgbox.php");
    exit;
}
?>