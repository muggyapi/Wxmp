<?php

/**
 *先获取asscess，本案例为错误写法。
 *正确写法应该是要对asscess做缓存处理，避免使用上限
 **/
  $appid = '';
  $ask = '';
 
  $result = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$ask");
  
  $json = json_decode($result,true);
  
  $key =  $json['access_token'];

?>
<?php
header("Content-type: text/html; charset=utf-8");
define("ACCESS_TOKEN", "$key");
//创建菜单
function createMenu($data){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tmpInfo = curl_exec($ch);
if (curl_errno($ch)) {
 return curl_error($ch);
}
curl_close($ch);
return $tmpInfo;
}
//获取菜单
function getMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
}
//删除菜单
function deleteMenu(){
return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
}
$data = '{
   "button":[
   {
     "type":"view",
     "name":"疫情查询",
     "url":"https://3g.dxy.cn/newh5/view/pneumonia"
   },
   {
      "type":"click",
      "name":"使用教程",
      "key":"use"
   },
   {
      "name":"关于Muggle",
      "sub_button":[
      {
        "type":"view",
        "name":"微博@加肥猫肥加",
        "url":"https://weibo.com/u/5975117263"
      },
      {
        "type":"view",
        "name":"作品集",
        "url":"https://api.qaqwq.com"
      }]
    }]
}';
echo createMenu($data);
?>