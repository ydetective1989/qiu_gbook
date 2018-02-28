<?php
/**
+------------------------------------------------------------------------------
* Spring Framework 常用 
+------------------------------------------------------------------------------
* @Date		2008-02-20
* @QQ		28683
* @Author	Jeffy (darqiu@gmail.com)
* @version	2.0
+------------------------------------------------------------------------------
*/
class plugin
{	

	Public Function Service()
	{
	}

	Public Function ClearHtml($html)
	{
		$html = strip_tags($html);
		$html = str_replace("&nbsp;"," ",$html);
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得域名
	Public Function getDomain()
	{
		$S = plugin::getURL();
		$S = parse_url($S);
		$S = strtolower($S['host']) ; //取域名部分
		//echo $S;
		$domain = array('com','cn','name','org','net'); //域名后缀 有新的就扩展这吧
		$SS = $S;
		$dd = implode('|',$domain);
		$SS = preg_replace('/(.('.$dd.'))*.('.$dd.')$/iU','',$SS); 
		$SS = explode('.',$SS);
		$SS = array_pop($SS);  //取最后的主域名
		$SS = substr($S,strrpos($S,$SS));  //加上后缀拼成完成的主域名
		return $SS;
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得域名
	Public Function getHost()
	{
		return $_SERVER['HTTP_HOST'];
	}

	Public Function getUrlDomain($url){
		$pattern = "/[\w-]+\.(com|net|org|gov|cc|biz|info|cn|hk)(\.(cn|hk))*/";
		preg_match($pattern, $url, $matches);
		if(count($matches) > 0) {
			return $matches[0];
		}else{
			$rs = parse_url($url);
			$main_url = $rs["host"];
			if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url)) {
				return $main_url;
			}else{
				$arr = explode(".",$main_url);
				$count=count($arr);
				$endArr = array("com","net","org");//com.cn  net.cn 等情况
				if (in_array($arr[$count-2],$endArr)){
					$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
				}else{
					$domain =  $arr[$count-2].".".$arr[$count-1];
				}
				return $domain;
			}// end if(!strcmp...)
		}// end if(count...)
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得当前主机头
	Public Function subDomain()
	{
		$serverhost = explode('.',$_SERVER["HTTP_HOST"]);$subdomain = $serverhost[0];  
		if (isset($serverhost[2]) and ($serverhost[2] != "") ? $sub = $subdomain : $sub = "" );  
		return $sub;  
	}
	
	//+----------------------------------------------------------------------------------------------------------
	  //Desc:格式化参数为数组
	Public Function extstr($str) //格式如 brandid=1&name=1&id=1
	{
		parse_str($str,$arr);
		return $arr;
		
		$str=explode('&',$str);
		$newstr = array();
		foreach($str as $item)
		{
			$item = explode('=',$item);
			$newstr[$item[0]] = $item[1];
		}
		return $newstr;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:URL的Rewrite拼合
	Public Function get2html($urlget,$html='html')
	{
		$arr = array();
		ksort($urlget);
		foreach($urlget as $n => $s)
		{
			$arr[]=$n;
		}
		ksort($_GET);

		foreach($_GET as $k => $v)
		{
			if($k=="mod")	continue;
			if($k=="ac")	continue;
			if($k=="page")	continue;
			if(in_array($k,$arr)) continue;
			if(isset($_COOKIE[$k])&&$_COOKIE[$k]==$v)continue;
			if($k=="PHPSESSID")	continue;
			if($k=="URLARR")	continue;
			if($v<>''){ $urlarray[]=$k.'='.urlencode($v);}
		}
		$url = @implode("&",$urlarray);
		$arr = array();
		foreach($urlget as $n => $s){
			if($n=="page"){
				if($s==""){
					$page = "";
				}else{
					$page = "_".$s;
				}
			}else{
				if($s<>""){
					$arr[]=urlencode($n).'='.urlencode($s);
				}
			}
		}
		$getarr = @implode("&",$arr);
		$urlac	= ($_GET["ac"])?"/".$_GET["ac"]:"";
		if($url&&$getarr){
			$getarr = $url."&".$getarr;
		}elseif($url&&$getarr==""){
			$getarr = $url;
		}
		//$getarr	= ($url)?$url."-".$getarr:$getarr;
		$getarr = ($getarr)?"?".$getarr:"";
		$mod = ($_GET["mod"])?$_GET["mod"]:"list";
		$ext = ($html)?".".$html:"";
		$urlto = S_ROOT.$mod.$urlac.$getarr.$page.$ext;

		return $urlto;

	}

	//关键字处理
	Public Function getKeyword($str,$dot=" ",$join="")
	{
		$str = str_replace("　"," ",$str);
		if($str){
			$str = explode(" ",$str);
			$arr = array();
			foreach($str AS $rs){
				if($rs!=""){
					$arr[] = $join."".$rs;
				}
			}
			$str = implode("".$dot."",$arr);
		}
		return $str;
	}
	
	//+----------------------------------------------------------------------------------------------------------
	//Desc:数组处理成数字符
	Public Function arrtostr($str)
	{
		if(is_array($str)){
			$arr = "";
			foreach($str AS $n=>$v){
				$arr.= $n."：".$v."\n";
			}
			$str = $arr."-------------------------\n";
		}
		return $str;
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:格式化URL
	Public Function urlformat($url)
	{
		$url = trim($url);
		if($url){
			$str = parse_url($url);		//分解URL
			$scheme = $str["scheme"];	//协议类型
			$user	= $str["user"];		//登陆帐号
			$pass	= $str["pass"];		//登陆密码
			$port	= $str["port"];		//端口号码
			$path	= $str["path"];
			$query	= $str["query"];
			$fragment = $str["fragment"];
			if($port){
				if($scheme=="http"){
					if($port!="80"){
						$ports = ":".$port;
					}
				}
				if($scheme=="https"){
					if($port!="443"){
						$ports = ":".$port;
					}
				}
				if($scheme=="ftp"){
					if($port!="21"){
						$ports = ":".$port;
					}
				}
			}
			if($user){$accout = $user."@";}
			if($pass){$accout = $user.":".$pass."@";}
			if($fragment){$fragment="#".$fragment;}
			if($query){
				$strget = $path."?".$query.$frament;
			}else{
				if($path!="/"){
					if($fragment){$fragment = $fragment;}
					$strget = $path.$fragment;
				}
			}
			$url = $scheme."://".$accout.strtolower($str["host"]).$ports.$strget;
		}
		return $url;
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得来源页面
	Public Function retURL()
	{
		$referer = $_SERVER['HTTP_REFERER'];
		$urlstr = str_replace("http://","",$referer);
		$urlstr = str_replace("https://","",$urlstr);
		$str_array = explode("/",$urlstr);
		if($str_array[0] == plugin::getHost())
		{
			return str_replace(plugin::getHost(),"",$urlstr);
		}else{
			return $referer;
		}
	}
	
	//+----------------------------------------------------------------------------------------------------------
		//Desc:显示当前文件页面位置绝对路径
	Public Function getURL()
	{
		//return "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
		return "http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
	}

	//+----------------------------------------------------------------------------------------------------------
		//Desc:显示当前文件页面位置路径
	Public Function getPatch()
	{
		//return "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
		$urlto = $_SERVER['REQUEST_URI'];
		return $urlto;
	}

	//+----------------------------------------------------------------------------------------------------------
		//Desc:取得当前文件的物理路径
	Public Function getFilePatch()
	{
		$urlto = str_replace(strtolower(trim(substr(strrchr($_SERVER["SCRIPT_FILENAME"], '/'),1))),"",$_SERVER["SCRIPT_FILENAME"]);
		return $urlto;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:获得浏览器信息及版本号
	Public Function getAgent()
	{
		//MSIE //Chrome //Safari //Firefox //Opera
		$agent = explode(" ",$_SERVER['HTTP_USER_AGENT']);
		$nums  = (int)COUNT($agent)-1;
		$str = explode("/",$agent[$nums]);
		return $str;
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:获取用户IP地址
	Public Function getIP()
	{
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
		{
			$onlineip = getenv('HTTP_CLIENT_IP');
		}
		elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
		{
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
		{
			$onlineip = getenv('REMOTE_ADDR');
		}
		elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
		{
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		return $onlineip; 
	}


	//生成随机数
	Public Function random($length){ //(生成长度)
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		mt_srand((double)microtime() * 1000000);
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
		return $hash;
	}

	//针对数组排序
	Public Function sortArr($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR")
	{
		if(!is_array($ArrayData)) return $ArrayData;
	
		// Get args number.
		$ArgCount = func_num_args();
		// Get keys to sort by and put them to SortRule array.
		for($I = 1;$I < $ArgCount;$I ++)
		{
		$Arg = func_get_arg($I);
		if(!eregi("SORT",$Arg))
		{
		$KeyNameList[] = $Arg;
		$SortRule[]    = '$'.$Arg;
		}
		else $SortRule[]   = $Arg;
		}
		// Get the values according to the keys and put them to array.
		foreach($ArrayData AS $Key => $Info)
		{
		foreach($KeyNameList AS $KeyName) ${
		$KeyName}[$Key] = strtolower($Info[$KeyName]);
		}
	
		// Create the eval string and eval it.
		$EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
		eval ($EvalString);
		return $ArrayData;
	}
	
	Public Function getTheMonth($date){//获取指定日期所在月的第一天和最后一天
		$firstday = date("Y-m-01",strtotime($date));
		$lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
		return array($firstday,$lastday);
	}
	
	Public Function getPurMonth($date){//获取指定日期上个月的第一天和最后一天
		$time=strtotime($date);
		$firstday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)-1).'-01'));
		$lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
		return array($firstday,$lastday);
	}
	

	Public function getTime($val="0"){
		if($val>0){
			$arr = array();
			$arr['d']  = (int)($val/(3600*24));
			$arr['h']  = (int)($val%(3600*24)/3600);
			$arr['i']  = (int)($val%(3600*24)%3600/60);
			$arr['s']  = (int)($val%(3600*24)%3600%60);
		}
		return $arr ;
	}
	
	Public Function datediff($StartTime,$EndTime=""){
		if(empty($StartTime)){
			return false;
		}
		if(empty($EndTime)){
			$EndTime=date("Y-m-d");
		}
		 
		$StartStamp=strtotime($StartTime);	//把开始字符串时间转换成时间戳
		$EndStamp=strtotime($EndTime);		//把结束字符串时间转换成时间戳
		if($StartStamp>$EndStamp){			//如果开始时间大于结束时间 则交换两个时间
			$temp=$StartStamp;
			$StartStamp=$EndStamp;
			$EndStamp=$temp;
		}
		$ReturnYears	=	0;//相差的年数
		$ReturnMonth	=	0;//相差的月数
		$ReturnDays		=	0;//相差的天数
		for($i=1;strtotime("+$i years",$StartStamp)<=$EndStamp;	$i++) $ReturnYears++;						//求相差的年数
		for($i=1;strtotime("+$ReturnYears years $i month",$StartStamp)<=$EndStamp;$i++) $ReturnMonth++;		//求相差的月数
		for($i=1;strtotime("+$ReturnYears years $ReturnMonth month $i days",$StartStamp)<=$EndStamp;$i++) $ReturnDays++;//求相差的天数
		$retarr=array("y"=>$ReturnYears,"m"=>$ReturnMonth,"d"=>$ReturnDays);								//封装成数组
		return $retarr;
	}
	
	Public Function cutstr($sourcestr,$cutlength=10,$dot='..')
	{
		$returnstr='';
		$i=0;
		$n=0;
		$sourcestr = trim($sourcestr);
		$str_length = strlen($sourcestr);//字符串的字节数
		while (($n<$cutlength) and ($i<=$str_length))
		{
			$temp_str=substr($sourcestr,$i,1);
			$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
			if ($ascnum>=224)    //如果ASCII位高与224，
			{
				$returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
				$i=$i+3;            //实际Byte计为3
				$n++;            //字串长度计1
	
			}elseif ($ascnum>=192){ //如果ASCII位高与192，
	
				$returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
				$i=$i+2;            //实际Byte计为2
				$n++;            //字串长度计1
	
			}elseif($ascnum>=65 && $ascnum<=90 && $ascnum>=97 && $ascnum<=122){ //如果是大写字母或者是小写字母
					
				$returnstr=$returnstr.substr($sourcestr,$i,2);
				$i=$i+2;            //实际的Byte数仍计1个
				$n++;				//但考虑整体美观，大写字母计成一个高位字符
	
			}else{					//其他情况下，半角标点符号，
	
				$returnstr=$returnstr.substr($sourcestr,$i,1);
				$i=$i+1;            //实际的Byte数计1个
				$n=$n+0.5;        //半角标点等与半个高位字符宽...
			}
		}
		if($str_length>$i){
			$dotlen = strlen($dot);
			if($dotlen){
				$dotlen = ceil($dotlen/2);
				$strnum = $cutlength-$dotlen;
				$returnstr = plugin::cutstr($returnstr,$strnum,"");
			}
			$returnstr = $returnstr.$dot;//超过长度时在尾处加上省略号
		}
		return $returnstr;
	}

	Public Function tagsTo($tags){
		//转换全角空格为小空格
		$tags = str_replace("　"," ",$tags);
		//关键字处理
		$tags = @explode(" ", $tags);
		if($tags){
			$arr = array();
			foreach($tags AS $r){
				if($r<>''){
					$arr[] = $r;
				}
			}
			return @implode(",",$arr);
		}
		return;
	}

	Public function hidenums($str="")
	{
		$IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$str); //固定电话
		if($IsWhat == 1)
		{
			return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$str);
	
		}
		else
		{
			return  preg_replace('/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$str);
		}
	}
	
	//处理广告
	Public Function showad($file,$width='100',$height='100',$alt="links",$id="links")
	{
		//获取扩展名
		$ext = explode(".", $file);
		$ext = $ext[count($ext)- 1];
		if(strtolower($ext)=='swf'){
			$str = "<script type=\"text/javascript\">swfobject.embedSWF(\"".$file."\",\"".$id."\",\"".$width."\",\"".$height."\",\"9.0.0\",\"expressInstall.swf\");</script><div id=\"".$divid."\"></div>";
		}else{
			$str = "<img src=\"".upfile.$file."\" width=\"".$width."\" height=\"".$height."\" id=\"".$id."\" alt=\"".$alt."\">";
		}
		return $str;
	}

	//数据安全组合
	Public Function buildsafe($paramArr,$sid)
	{
		$keys = "a709f87e038f431503467657944a2b4b";
		$sign = "";
		ksort($paramArr);
		foreach($paramArr AS $key=>$val){
			if($key!=""&&$val!=""){
				$sign.=$key.$val;
			}
		}
		$sign = strtoupper(md5($keys.$sid.$keys.$sign.$keys.$sid.$keys));
		return $sign;
	}

	//处理textareaHTML
	Public Function textarea($string)
	{
		return str_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\">\\0</a>",str_replace(" ","&nbsp;",str_replace(chr(10), "<br>",strip_tags($string))));
	}

	//判断邮件格式
	Public Function isEmail($email){
		return strlen($email)>6&&preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/",$email);
	}

	//发送邮件
	Public Function sendMail($to="test@test.com", $subject="subject", $content="content")
	{
		static $import = false;
		if($import){
			require_once(LIB.'/sendmail.php');
			$import              = false;
			$mail                = new sendmail; 
			$mail->server        = "smtp.163.com"; 
			$mail->port          = "25"; 
			$mail->auth          = "1";
			$mail->auth_username = "lkf5_303"; 
			$mail->auth_password = ""; 
			$mail->mail_from     = "lkf5_303@163.com"; 
			$mail->email_from    = "lkf5_303@163.com"; 
			$mail->email_to      = $to;     //多个用逗号隔开 
			$mail->maildelimiter = "1";     //1使用 CRLF 作为分隔符(通常为 Windows 主机),0使用 LF 作为分隔符(通常为 Unix/Linux 主机),2使用 CR 作为分隔符(通常为 Mac 主机) 
			$mail->email_subject = $subject; 
			$mail->email_message = $content; 
			$mail->mailusername  = "1";   //收件人地址中包含用户名 
			$mail->charset       = "utf-8"; 
			$mail->bbname        = "成都易贷";    //网站名称 
			$mail->ishtml        = true; 
			$mail->ContentType   = "text/plain"; 
			$mail->send();
		}else{
		}
	}


}
?>