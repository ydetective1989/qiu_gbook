<?php
include("config.php");
if($_GET["do"]!="authimg"){?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php if($_GET["do"]=='send'){ echo "发布留言"; }else{ echo "瀛海威大黑板"; }?></title>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<link href="<?php echo $S_ROOT;?>images/style.css?<?php echo date("Ymd");?>" rel="stylesheet" type="text/css" />
<script type="text/javascript">var S_ROOT="<?php echo $S_ROOT;?>"</script>
<script type="text/javascript" src="<?php echo $S_ROOT;?>js/jquery.js?<?php echo date("Ymd");?>"></script>
<script>var S_ROOT = '<?php echo S_ROOT;?>';</script>
<script>
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideOptionMenu');
});
</script>
<script>
function dopage(pagenav)
{
	$("#comment").load(S_ROOT+"product/comment?page="+pagenav+"&");
}
</script>
<link rel="stylesheet" href="weui/weui.css"/>
<script src="weui/zepto.min.js"></script>
<script src="weui/example.js"></script>
<script src="js/plugin.js"></script>
</head>
<body>

<div class="weui_dialog_alert" id="msgshow" style="display: none;">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd"><strong class="weui_dialog_title">提示信息</strong></div>
        <div class="weui_dialog_bd">content</div>
        <div class="weui_dialog_ft">
            <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
</div>
<?php }?>
<?php if($_GET["do"]=="send"){?>
<?php if(isset($_POST)&&!empty($_POST)) {

include(CONFIG."verifyimg.php");
$img = new verifyimg();
$code   =   $_POST["book_code"];
if(!$img->checked(strtoupper($code))){
    msgbox("","验证码输入错误！");
    exit;
}

$name   =   $_POST["book_name"];
$email  =   $_POST["book_email"];
$url    =   $_POST["book_url"];
$says   =   $_POST["book_says"];
$getip  =   plugin::getIP();
$hash   =   MD5($name.$email.$url.$says.$getip);

$query  =   "SELECT id FROM gbook WHERE hash = '$hash' ";
$row    =   $db->getRow($query);
if($row){ msgbox(S_ROOT,"留言已提交成功！勿重复"); }

$arr = array(
    "name"      =>  $name,
    "email"     =>  $email,
    "url"       =>  $url,
    "says"      =>  $says,
    "datetime"  =>  time(),
    "ip"        =>  $getip,
    "hash"      =>  $hash
);
$db->insert("gbook",$arr);
msgbox(S_ROOT,"留言提交成功!");


}else{?>

<table width="100%">
    <tr class="pagetitle">
        <td width="60" class="tdcenter"><a href="<?php echo S_ROOT;?>" style="width:40px;font-size:18px;font-weight:bolder;" class="weui_btn weui_btn_mini weui_btn_default"></a></td>
        <td class="tdcenter size18 bold">发表留言</td>
        <td width="60"></td>
    </tr>
</table>

<div class="forms">
    <form method="post" id="doform">
        <table width="100%" class="table">
            <tr>
                <td width="50" class="tdright weui_cells_title">姓名：</td>
                <td><input name="book_name" id="book_name" class="weui_input"></td>
            </tr>
            <tr>
                <td width="50" class="tdright weui_cells_title">邮箱：</td>
                <td><input name="book_email" id="book_email" class="weui_input"></td>
            </tr>
            <tr>
                <td width="50" class="tdright weui_cells_title">URL：</td>
                <td><input name="book_url" id="book_url" class="weui_input"></td>
            </tr>
            <tr>
                <td class="tdright weui_cells_title">留言：</td>
                <td><textarea name="book_says" id="book_says" style="height:80px;" class="weui_textarea"></textarea><td>
            </tr>
            <tr>
                <td class="tdright weui_cells_title"></td>
                <td><input type="number" name="book_code" id="book_code" style="width:120px;" class="weui_input" placeholder="请输入验证码">
                <img src="<?php echo $S_ROOT?>?do=authimg" height="30" align="absmiddle" id="authnums" onclick="getAuth();">
                <td>
            </tr>
            <tr>
                <td colspan="2"><a href="javascript:;" id="btned" onclick="tofrom()" class="weui_btn weui_btn_primary">提交留言</a></td>
            </tr>
        </table>
    </form>
</div>
<?php }?>
<?php }elseif($_GET["do"]=="delete"){
$id = (int)base64_decode($_GET["id"]);
$query = " SELECT * FROM gbook WHERE id = $id AND hide = 1 ";
$row = $db->getRow($query);
if(!$row){ msgbox($cookie->get("backurl"),"留言不存在或已删除!"); }

if(isset($_POST)&&!empty($_POST)) {
$pass = $_POST["book_pass"];
if($pass!="lin1997"){ msgbox($cookie->get("backurl"),"操作密码错误"); }
$where = array("id"=>$id);
$arr   = array("hide"=>0);
$db->update("gbook",$arr,$where);
msgbox($cookie->get("backurl"),"删除操作成功");

}else{?>

<table width="100%">
    <tr class="pagetitle">
        <td width="60" class="tdcenter"><a href="<?php echo $cookie->get("backurl");?>" style="width:40px;font-size:18px;font-weight:bolder;" class="weui_btn weui_btn_mini weui_btn_default"><</a></td>
        <td class="tdcenter size18 bold">删除留言</td>
        <td width="60"></td>
    </tr>
</table>

<div class="forms">
    <form method="post" id="doform">
        <table width="100%" class="table">
            <tr>
                <td width="50" class="tdright weui_cells_title">姓名：</td>
                <td><?php echo $row['name'];?></td>
            </tr>
            <tr>
                <td width="50" class="tdright weui_cells_title">内容：</td>
                <td><?php echo $row['says'];?></td>
            </tr>
            <tr>
                <td width="50" class="tdright weui_cells_title">密码：</td>
                <td><input name="book_pass" id="book_pass" class="weui_input"></td>
            </tr>
            <tr>
                <td colspan="2"><a href="javascript:;" id="btned" onclick="todelete()" class="weui_btn weui_btn_primary">删除留言</a></td>
            </tr>
        </table>
    </form>
</div>

<?php }?>
<?php }elseif($_GET["do"]=="authimg"){
//echo "111";exit;
include(CONFIG."verifyimg.php");
$img = new verifyimg();
$img->text = $img->getRandNum();
$img->create();
$img->show();
?>

<?php }else{?>

<?php
$query = " SELECT * FROM gbook WHERE hide = 1 ORDER BY id DESC ";
$db->keyid = "id";
$rows  = $db->getPageRows($query,20,6);
$list  = $rows["record"];
$page  = $rows["pages"];
$cookie->set("backurl",plugin::getURL());
?>


<div id="main" class="forms">
    <table width="100%">
        <tr class="pagetitle">
            <td width="60"></td>
            <td class="tdcenter size18 bold">瀛海威大黑板</td>
            <td width="60" class="tdcenter"><a href="<?php echo S_ROOT;?>?do=send" style="width:40px;font-size:18px;font-weight:bolder;" class="weui_btn weui_btn_mini weui_btn_primary">+</a></td>
        </tr>
    </table>
    <?php if($list){?>
        <div class="weui_cells_access">
            <?php foreach($list AS $rs){?>
            <table width="100%" class="weui_cells">
                <tr style="font-size:14px;background-color:#e9e9e9;">
                    <td height="30">&nbsp;&nbsp;<span class="blue"><?php echo $rs["name"]?></span></td>
                    <td class="tdright"><span class="gray">发表于：<?php echo date("Y-m-d H/i/s",$rs["datetime"]);?></span><a href="<?php echo $S_ROOT;?>?do=delete&id=<?php echo base64_encode($rs["id"])?>">&nbsp;&nbsp;</a></td>
                </tr>
                <tr><td colspan="2" height="8"></td></tr>
                <tr>
                    <td colspan="2" class="warp"><?php echo nl2br($rs["says"]);?></td>
                </tr>
                <tr><td colspan="2" height="8"></td></tr>
            </table>
            <?php }?>
        </div>

        <div class="pagenav"><?php echo $page;?><br><br><br></div>

    <?php }else{?>
        <div class="weui_cells weui_cells_access red" style="line-height:50px;text-align:center;">暂无留言信息</div>
    <?php } ?>
</div>

<?php }?>

<?php if($_GET["do"]!="authimg"){?>
</body>
</html>
<?php }?>
