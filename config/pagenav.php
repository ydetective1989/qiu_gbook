<?php
class pagenav
{

	Public $pageRows = 20;

	Public Function show($rowCount='0',$pageRows='0',$type='0',$maxpages='0')//总条数，每页记录数，分页类型，最大页数
    {
		//print_r($_GET);
		if($_GET)
		{
			$urlarray = array();
			ksort($_GET);
			foreach($_GET as $k => $v)
			{
				if($k=='page')continue;
				if($_GET["mod"]=="search"){
					if($k=='mod')continue;
					if($k=='ac')continue;
				}
				if(isset($_COOKIE[$k])&&$_COOKIE[$k]==$v)continue;
				if($k=="PHPSESSID")	continue;
				if($k=="URLARR")	continue;
				if($v<>""){
					$urlarray[]=urlencode($k).'='.urlencode($v);
				}
			}
			$url='?'.@implode("&",$urlarray);
		}
		else
		{
			$url='?';
		}

		$pageNums = ($_GET["page"])?$_GET["page"]:'1';

		if($type=='1'){
			return $this->numPageHtml($rowCount,$pageRows,$pageNums,$maxpages);		//静态页码分页
		}elseif($type=='2'){
			return $this->numsPage($rowCount,$pageRows,$pageNums,$url,$maxpages);	//普通页面分页
		}elseif($type=='3'){
			return $this->morePage($rowCount,$pageRows,$pageNums,$maxpages);		//AJAX加载页面
		}elseif($type=='4'){
			return $this->txtPageHtml($rowCount,$pageRows,$pageNums,$maxpages);		//静态前后分页
		}elseif($type=='5'){
			return $this->numPageAjax($rowCount,$pageRows,$pageNums,$maxpages);		//AJAX页码分页
		}elseif($type=='6'){
			return $this->txtPageAjax($rowCount,$pageRows,$pageNums,$maxpages);		//AJAX普通分页
		}else{
			return $this->txtPage($rowCount,$pageRows,$pageNums,$url,$maxpages);	//普通前后分页
		}
	}


	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	Public Function txtPage($rowCount,$pageRows,$pageId,$url,$maxpages)
	{
		$pages = ceil($rowCount/$pageRows);
		if($pages==0){ $pages=1; }
		if($pages<=1){return false;}
		if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">首页</span>":"<a href=\"".$url."&page=1\">首页</a>";
			$up =	($pageId==1) ?"<span class=\"no\">上页</span>":"<a href=\"".$url."&page=".intval($pageId-1)."\">上页</a>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."&page=".intval($pageId+1)."\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">末页</span>":"<a href=\"".$url."&page=".$pages."\">末页</a>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">首页</span>";
			$up = "<span class=\"no\">上一页</span>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."&page=".intval($pageId+1)."\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">末页</span>":"<a href=\"".$url."&page=".$pages."\">末页</a>";
			$offset = ($pageId-1)* $pageRows;
		}
		//
		$pageNav = "".$head."".$up."".$down."".$foot."<span class=\"nums\">".$pageId." / ".$pages."</span>";
		return $pageNav;
	}

	// ?mod=article&ac=cates&id=x&page=&
	Public Function numPageHtml($rowCount,$pageRows,$pageId,$maxpages)//总条数，每页记录数，当前页, URL链接、最大分页数
	{
		$multipage = "";
		//if($rowCount > $pageRows){
			$page = 11;
			$offset= 5;
			$pages = ceil($rowCount/$pageRows);
			if($pages<=1){return false;}
			if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
			$from = $pageId - $offset;
			$to = $pageId + $page - $offset - 1;
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				if($from < 1) {
					$to = $pageId + 1 - $from;
					$from = 1;
					if(($to - $from) < $page && ($to - $from) < $pages)
					{
						$to = $page;
					}
				}elseif($to > $pages){
					$from = $pageId - $pages + $to;
					$to = $pages;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}

			$multipage.= $pageId>1?"<a href=\"".plugin::get2html(array('page'=>''))."\"><<</a>":"<span class=\"no\"><<</span>";
			for($i = $from; $i <= $to; $i++){
				if($i!=$pageId){
					$multipage.= "<a href=\"".plugin::get2html(array('page'=>$i))."\">$i</a>";
				}else{
					$multipage.= "<span class=\"page\">".$i."</span>";
				}
			}
			$multipage.= $pages>$pageId?"<a href=\"".plugin::get2html(array('page'=>$pages))."\">>></a>":"<span class=\"no\">>></span>";

		//}
		return $multipage;
	}

	Public Function numsPage($rowCount,$pageRows,$pageId,$url,$maxpages)//总条数，每页记录数，当前页, URL链接，最大页数
	{
		$multipage = "";
		//if($rowCount > $pageRows){
			$page=10;
			$offset= 4;
			$pages = ceil($rowCount/$pageRows);
			if($pages<=1){return false;}
			if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
			$from = $pageId - $offset;
			$to = $pageId + $page - $offset - 1;
			if($page > $pages) {
				$from = 1;
				$to = $pages;
			} else {
				if($from < 1) {
					$to = $pageId + 1 - $from;
					$from = 1;
					if(($to - $from) < $page && ($to - $from) < $pages)
					{
						$to = $page;
					}
				}elseif($to > $pages){
					$from = $pageId - $pages + $to;
					$to = $pages;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}
			$multipage.= $pageId>1?"<a href=\"$url&page=1\"><<</a>":"<span class=\"no\"><<</span>";
			for($i = $from; $i <= $to; $i++){
				if($i!=$pageId){
					$multipage.= "<a href=\"$url&page=$i\">$i</a>";
				}else{
					$multipage.= "<span class=\"page\">".$i."</span>";
				}
			}
			$multipage.= $pages>$pageId?"<a href=\"$url&page=$pages\">>></a>":"<span class=\"no\">>></span>";
		//}
		return $multipage;
	}

	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	Public Function txtPageHtml($rowCount,$pageRows,$pageId,$maxpages)
	{
		$pages = ceil($rowCount/$pageRows);
		if($pages<=1){return false;}
		if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">首页</span>":"<a href=\"".plugin::get2html(array('page'=>1))."\">首页</a>";
			$up =	($pageId==1) ?"<span class=\"no\">上页</span>":"<a  href=\"".plugin::get2html(array('page'=>intval($pageId-1)))."\">上页</a>";
			$down = ($pageId==$pages) ?"<span class=\"no\">下页</span>":"<a href=\"".plugin::get2html(array('page'=>intval($pageId+1)))."\">下页</a>";
			$foot = ($pageId==$pages) ?"<span class=\"no\">尾页</span>":"<a href=\"".plugin::get2html(array('page'=>intval($pages)))."\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">首页</span>";
			$up = "<span class=\"no\">上页</span>";
			$down = ($pageId==$pages) ?"<span class=\"no\">下页</span>":"<a href=\"".plugin::get2html(array('page'=>intval($pageId+1)))."\">下页</a>";
			$foot = ($pageId==$pages) ?"<span class=\"no\">下页</span>":"<a href=\"".plugin::get2html(array('page'=>intval($pages)))."\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}
		//."<span class=\"nums\">页码：[".$pageId."/".$pages."]</span>"
		$pageNav = "".$head."".$up."".$down."".$foot;
		return $pageNav;
	}

	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	Public Function morePage($rowCount,$pageRows,$pageId,$maxpages)
	{
		$pages = ceil($rowCount/$pageRows);
		if($pages<=1){return false;}
		if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
		if(isset($pageId))
		{
			$pageNav = ($pageId==$pages)?"":"<input type=\"hidden\" id=\"pageId\" value=\"".intval($pageId+1)."\"><a href=\"#dopage\" onclick=\"dopage()\">查看更多内容</a>";
		}else{
			$pageNav = "<input type=\"hidden\" id=\"pageId\" value=\"".intval($pageId+1)."\"><a href=\"#dopage\" onclick=\"dopage()\">查看更多内容</a>";
		}
		return $pageNav;
	}

	Public Function numPageAjax($rowCount,$pageRows,$pageId,$maxpages)//总条数，每页记录数，当前页, URL链接
	{
		$multipage = "";
		$page=10;
		$offset= 4;
		$pages = ceil($rowCount/$pageRows);
		if($pages<=1){return false;}
		if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
		$from = $pageId - $offset;
		$to = $pageId + $page - $offset - 1;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			if($from < 1) {
				$to = $pageId + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages)
				{
					$to = $page;
				}
			}elseif($to > $pages){
				$from = $pageId - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}

		$multipage.= $pageId>1?"<a href=\"#dopage\" onclick=\"dopage(1)\"><<</a>":"<span class=\"no\"><<</span>";
		for($i = $from; $i <= $to; $i++){
			if($i!=$pageId){
				$multipage.= "<a href=\"#dopage\" onclick=\"dopage($i)\">$i</a>";
			}else{
				$multipage.= "<span class=\"page\">".$i."</span>";
			}
		}
		$multipage.= $pages>$pageId?"<a href=\"#dopage\" onclick=\"dopage($pages)\">>></a>":"<span class=\"no\">>></span>";
		return $multipage;
	}

	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	Public Function txtPageAjax($rowCount,$pageRows,$pageId,$maxpages)
	{
		$pages = ceil($rowCount/$pageRows);
		if($pages==0){$pages=1;}
		if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
		if($pages<=1){return false;}
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">[首页]</span>":"<span><a href=\"javascript:void(0)\" onclick=\"dopage(1)\">[首页]</a></span>";
			$up =	($pageId==1) ?"<span class=\"no\">[上一页]</span>":"<span><a href=\"javascript:void(0)\" onclick=\"dopage(".intval($pageId-1).")\">[上一页]</a></span>";
			$down = ($pageId==$pages)?"<span class=\"no\">[下一页]</span>":"<span><a href=\"javascript:void(0)\" onclick=\"dopage(".intval($pageId+1).")\">[下一页]</a></span>";
			$foot = ($pageId==$pages)?"<span class=\"no\">[尾页]</span>":"<span><a href=\"javascript:void(0)\" onclick=\"dopage(".$pages.")\">[尾页]</a></span>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">[首页]</span>";
			$up = "<span class=\"no\">[上一页]</span>";
			$down = ($pageId==$pages)?"<span class=\"no\">[下一页]</span>":"<span><a href=\"javascript:void(0)\" onclick=\"dopage(".intval($pageId+1).")\">[下一页]</a></span>";
			$foot = ($pageId==$pages)?"<span class=\"no\">[下页]</span>":"<span><a href=\"javascript:void(0)\" onclick=\"dopage(".$pages.")\">[尾页]</a></span>";
			$offset = ($pageId-1)* $pageRows;
		}
		$pageNav = "".$head."".$up."".$down."".$foot."<span class=\"nums\">页码：[".$pageId."/".$pages."]</span>";
		return $pageNav;
	}

	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	Public Function txtPageSelect($rowCount,$pageRows,$pageId,$url,$maxpages)
	{
		$pages = ceil($rowCount/$pageRows);
		if($pages<=1){return false;}
		if($pages > $maxpages && $maxpages){ $pages = $maxpages; } //判断最大分页数
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">[首页]</span>":"<a href=\"".$url."&page=1\">[首页]</a>";
			$up =	($pageId==1) ?"<span class=\"no\">[上一页]</span>":"<a href=\"".$url."&page=".intval($pageId-1)."\">[上一页]</a>";
			$down = ($pageId==$pages) ?"<span class=\"no\">[下一页]</span>":"<a href=\"".$url."&page=".intval($pageId+1)."\">[下一页]</a>";
			$foot = ($pageId==$pages) ?"<span class=\"no\">[尾页]</span>":"<a href=\"".$url."&page=".$pages."\">[尾页]</a>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">[首页]</span>";
			$up = "<span class=\"no\">[上一页]</span>";
			$down = ($pageId==$pages) ?"<span class=\"no\">[下一页]</span>":"<a href=\"".$url."&page=".intval($pageId+1)."\">[下一页]</a>";
			$foot = ($pageId==$pages) ?"<span class=\"no\">[下页]</span>":"<a href=\"".$url."&page=".$pages."\">[尾页]</a>";
			$offset = ($pageId-1)* $pageRows;
		}
		$select.="<span class=\"no\"><select name=\"selectpage\"\" onchange=\"javascript:location.href=this.value;\">";
		for($i=1;$i<=$pages;$i++){
			if($pageId==$i){ $selected="selected"; }else{ $selected=""; }
			$select.="<option value=\"".$url."&page=".$i."\" ".$selected.">".$i."</option>";
		}
		$select.="</select></span>";
		$pageNav.= "".$head."".$up."".$down."".$foot."<span class=\"nums\">页码：[".$pageId."/".$pages."]</span>".$select."";
		return $pageNav;
	}

}
?>
