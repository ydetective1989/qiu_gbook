<?php

include("config.php");
?>
<html>
<header>
<script type="text/javascript" src="jquery.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</header>
<body>
  <?php
  $query = " SELECT * FROM gbook WHERE hide = 1 ORDER BY id DESC ";
  $db->keyid = "id";
  $rows  = $db->getPageRows($query,20,4);
  $list  = $rows["record"];
  $page  = $rows["pages"];
  $cookie->set("backurl",plugin::getURL());
  ?>


  <div class="forms">
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
</body>
</html>
