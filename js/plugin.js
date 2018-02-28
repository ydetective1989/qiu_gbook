
function getAuth()
{
	document.getElementById("authnums").src = S_ROOT+"?do=authimg&" + Math.random();
}

function tofrom()
{
	var name = $("#book_name").val();
	var email= $("#book_email").val();
	var says = $("#book_says").val();
	var code = $("#book_code").val();

	if(name==""){ showmsg("留言姓名不能为空");return; }
	if(email!="")
	{
		if(!isValidEmailAddress(email)){
			showmsg("电子邮箱格式不正确!");return;
		}
	}
	if(says==""){ showmsg("留言内容不能为空!");return; }

	if(code==""){ showmsg("验证码不能为空");return; }

	$("#doform").submit();
}

function todelete()
{
	var code = $("#book_pass").val();
	if(code==""){ showmsg("操作密码不能为空");return; }
	$("#doform").submit();
}

//email checked
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}

function showmsg(msg)
{
	var ele = $("#msgshow");
	ele.show();
	ele.find('.weui_dialog_bd').html(msg);
	ele.find('.weui_btn_dialog').on('click', function () {
		ele.hide();
	});
}

function pagedo()
{
	var maxpage	= $("#pagenav_maxpage").val();
	var url		= $("#pagenav_urlto").val();
	var page	= $("#pagenav_page").val();
	//alert(maxpage+"|"+page);return;
	var maxpage = maxpage-0;
	var page = page-0;
	if(maxpage < page){ msgshow("抱歉，不能超过超过最大页数！"); $("#pagenav_page").attr("value",maxpage); return; }
	var urlto = url+"page="+page;
	//alert(urlto);
	location.href = urlto;
}

function show_div(obid){
	if($("#"+obid).is(":hidden")){
		$("#"+obid).show();
	}else{
		$("#"+obid).hide();
	}
}

/*********检查长时间格式 2008-09-09 22:22:22********/
function strDateTime(str) 
{ 
	var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/; 
	var r = str.match(reg); 
	if(r==null)return false; 
	var d= new Date(r[1], r[3]-1,r[4],r[5],r[6],r[7]); 
	return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]&&d.getHours()==r[5]&&d.getMinutes()==r[6]&&d.getSeconds()==r[7]); 
}

/*********检查长时间 2008-09-09 ********/
function strDate(str) 
{ 
	var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/; 
	var r = str.match(reg); 
	if(r==null)return false; 
	var d= new Date(r[1],r[3]-1,r[4]); 
	return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]); 
}

//检查空格
function isWhiteWpace(s)
{
  var whitespace = " \t\n\r";
  var i;
  for (i = 0; i < s.length; i++){   
     var c = s.charAt(i);
     if (whitespace.indexOf(c) >= 0) {
		  return false;
	  }
   }
   return true;
}

//英文字符
function isSsnString (ssn)
{
	var re=/^[0-9a-z][\w-.]*[0-9a-z]$/i;
	if(re.test(ssn))
	return true;
	else
	return false;
}

function checkEmail(email)
{
	//var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	var reg = /^(?:\w+\.?)*\w+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	flag = reg.test(email);
	if(!flag) {
		return false;
	}
	return true
}

//检查价格格式
function isMoney(s){ 

	var regex = /^-?\d[\d,]*([.]\d+)?$/; // 123,456.0129 or -123456 or -1,23,,,45,6.0909 or 123456,,,,,.09
	if (!s.match(regex)) {
		return false;
	}
	return true;
	//var regu = "^[0-9]+[\.?][0-9?]{0,2}$";
	//var re = new RegExp(regu);
	//if (re.test(s)) {
	//	return true;
	//} else {
	//	return false;
	//}
}

//检查价格格式
function isMoneyDims(s){ 
	
	var regex = /^-\d[-\d,]*([.]\d+)?$/;  // 123,456.0129 or -123456 or -1,23,,,45,6.0909 or 123456,,,,,.09
	if (!s.match(regex)) {
		return false;
	}
	return true;
	//var regu = "^[0-9]+[\.?][0-9?]{0,2}$";
	//var re = new RegExp(regu);
	//if (re.test(s)) {
	//	return true;
	//} else {
	//	return false;
	//}
}

//检查价格格式
function isMoneyNums(s){ 

	var regex = /^\d[\d,]*([.]\d+)?$/; // 123,456.0129 or -123456 or -1,23,,,45,6.0909 or 123456,,,,,.09
	if (!s.match(regex)) {
		return false;
	}
	return true;
	//var regu = "^[0-9]+[\.?][0-9?]{0,2}$";
	//var re = new RegExp(regu);
	//if (re.test(s)) {
	//	return true;
	//} else {
	//	return false;
	//}
}

//检查是否数字
function isNumber(s){
	var s = s+"";
	var regu = "^[0-9]+$";
	var re = new RegExp(regu);
	if (s.search(re) != -1) {
		return true;
	} else {
		return false;
	}
}

//检查是否负数字
function isFNumber(s){ 
	var s = s+"";  
	var regu = "^-[0-9]+$";
	var re = new RegExp(regu);
	if (s.search(re) != -1) {
		return true;
	} else {
		return false;
	}
}

function isMobile(phone){
	var num = phone;
	var partten = /^1[3,4,7,5,8]\d{9}$/;
	if(partten.test(num)){
		return true;
	}else{
		return false;
    }
}

function itemShow(itemName,showId,num,bgItemName,clsName){ 
  var clsNameArr=new Array(2)
  if(clsName.indexOf("|")<=0){
    clsNameArr[0]=clsName
    clsNameArr[1]=""
  }else{
    clsNameArr[0]=clsName.split("|")[0]
    clsNameArr[1]=clsName.split("|")[1]
  }
  
  for(i=1;i<=num;i++){
    if(document.getElementById(itemName+i)!=null)
      document.getElementById(itemName+i).style.display="none"
    if(document.getElementById(bgItemName+i)!=null)
      document.getElementById(bgItemName+i).className=clsNameArr[1]
    if(i==showId){
      if(document.getElementById(itemName+i)!=null)
        document.getElementById(itemName+i).style.display=""
      else
        $.dialog.alert("未找到您请求的内容！")
      if(document.getElementById(bgItemName+i)!=null)
        document.getElementById(bgItemName+i).className=clsNameArr[0]
    }
  }
}

function allselect()
{
	$("input[name='selected']").attr("checked",true);
}


