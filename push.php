<?php

$conn = mysqli_connect($mysql_server_name, $mysql_username, $mysql_password, $mysql_database);
mysqli_set_charset($conn, "utf8");


  $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //获取POST数据
  //用SimpleXML解析POST过来的XML数据
  $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
  $fromUsername = $postObj->FromUserName; //获取发送方帐号（OpenID）
  $toUsername = $postObj->ToUserName; //获取接收方账号
  $keyword = trim($postObj->Content); //获取消息内容
  $masType = $postObj->MsgType;//获取消息类型，可以作分类判断。本例默认是文本消息，不做判断
  $time = time(); //获取当前时间戳
  $sub = $postObj->Event;
  $clock = date("H"); 
   //---------- 返 回 数 据 ---------- //
  //返回消息模板
   $textTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[%s]]></MsgType>
    <Content><![CDATA[%s]]></Content>
    <FuncFlag>0</FuncFlag>
    </xml>";
     
     //疫情推送代码片段
     if($keyword == '使用教程' or $keyword == '教程' or $keyword == '方法' or $keyword == '使用方法')
     {
		$msgType = "text"; //消息类型
	    $contentStr = "回复【统计】获取全国数据\n回复【广东】获取该广东省数据\n回复【广州】获取广州数据\n------------------\n💊数据来源-><a href='https://3g.dxy.cn/newh5/view/pneumonia'>丁香医生</a>\n每准点更新一次";
	    $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
	    echo $resultStr;
	 }
	 elseif ($keyword == '统计') {
	 	$get_all = "SELECT * FROM `china` where 1;";
	 	$get_all1 = mysqli_query($conn,$get_all);
	 	while($row1 = mysqli_fetch_array($get_all1))
		{
			$name = $row1['virus'];
			$confirmedCount = $row1['confirmedCount'];
			$suspectedCount = $row1['suspectedCount'];
			$deadCount = $row1['deadCount'];
			$curedCount = $row1['curedCount'];
		}
		$msgType = "text";
		$contentStr = "病毒：$name \n确诊 $confirmedCount 例\n疑似 $suspectedCount 例\n死亡 $deadCount 例\n治愈 $curedCount 例\n---------\n💊数据来源-><a href='https://3g.dxy.cn/newh5/view/pneumonia'>丁香医生</a>\n数据已于今日 $clock 点更新";
		$resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
		echo $resultStr;
		exit();
	 }
	 else
	 {
	 	$get = "SELECT * FROM `表名` where `provinceShortName`='$keyword';";
		$get_query = mysqli_query($conn,$get);
		$num = mysqli_num_rows($get_query);
		while($row = mysqli_fetch_array($get_query))
		{
			$cityname = $row['name'];
			$confirmedCount = $row['confirmedCount'];
			$deadCount = $row['deadCount'];
			$curedCount = $row['curedCount'];
		}
		if($num<=0)
		{
			$msgType = "text"; //消息类型
		    $contentStr = "⚠️疫情查询没有相关结果\n----------------------\n您可通过<a href='https://3g.dxy.cn/newh5/view/pneumonia'>丁香园</a>进行查询";
		    $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
		    echo $resultStr;
		    exit();
		}
		else
		{
			$msgType = "text"; //消息类型
		    $contentStr = "查询地区：$cityname\n确认病例：$confirmedCount 例\n死亡人数：$deadCount 人\n治愈人数：$curedCount 人\n---------\n💊数据来源-><a href='https://3g.dxy.cn/newh5/view/pneumonia'>丁香医生</a>\n数据已于今日 $clock 点更新";
		    $resultStr = sprintf($textTpl,$fromUsername,$toUsername,$time,$msgType,$contentStr);
		    echo $resultStr;
		    exit();
		}
		
	 }
     

?>
