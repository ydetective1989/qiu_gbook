<?php if($msgbox){?>
<div class="page">
	<div class="weui_msg">
		<div class="weui_icon_area"><i class="weui_icon_success weui_icon_msg"></i></div>
		<div class="weui_text_area">
			<h2 class="weui_msg_title"><?php echo $msgbox?></h2>
			<p class="weui_msg_desc"></p>
		</div>
		<div class="weui_opr_area">
			<p class="weui_btn_area">
				<a href="javascript:void(0);" onclick="<?php echo ($urlto)?"location.href='".$urlto."'":"javascript:history.back(-1);"; ?>" class="weui_btn weui_btn_primary">确定</a>
			</p>
		</div>
<!--		<div class="weui_extra_area">-->
<!--			<a href="">查看详情</a>-->
<!--		</div>-->
	</div>
</div>
<?php echo $script?>

<?php }else{?>

<?php echo "正在进入页面... ".$script?>

<?php //header("location:$urlto");?>

<?php }?>

<script type="text/javascript">
	//<!--
	//function isIFrameSelf(){try{if(window.top ==window){return false;}else{return true;}}catch(e){return true;}}
	function toHome(){
		<?php if($urlto){?>
		location.href="<?php echo $urlto?>";
		<?php }else{?>
		history.back(-1);
		<?php }?>
	}
	window.setTimeout("toHome()",<?php echo (int)$timeout;?>);
	//-->
</script>