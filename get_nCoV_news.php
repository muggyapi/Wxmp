<?php
@date_default_timezone_set('Asia/Chongqing');

// 获取最新播报信息
if ($newdata = get_nCoV_news()) {


    //开始循环获取省份信息 33省级
    /**
     * 变量说明：
     * confirmedCount   确认病例
     * curedCount       治愈人数
     * deadCount		死亡人数
     */

    foreach ($newdata as $item) {

        $provinceShortName = $item['provinceShortName'];
        $confirmedCount = $item['confirmedCount'];
        $deadCount = $item['deadCount'];
        $curedCount = $item['curedCount'];

        # push 省级数据到数据库

        if (count($item['cities']) != 0) {
            foreach ($item['cities'] as $city) {
                $cityName = $city['cityName'];
                $confirmedCount = $city['confirmedCount'];
                $suspectedCount = $city['suspectedCount'];
                $curedCount = $city['curedCount'];
                $deadCount = $city['deadCount'];

                # push 市级数据到数据库
                
            }
        }
        
    }

    mysqli_close($conn);
    echo "省级数据更新完成<br>";
    echo "市级数据更新完成";
}



//}
// ========================
// 以下用到的函数

function get_nCoV_news()
{
    $reg = '/<script id="getAreaStat">.+?window.getAreaStat\s=\s(\[{.+?\])}catch\(e\){}<\/script>/im';
    if (preg_match($reg, $content = file_get_contents('https://3g.dxy.cn/newh5/view/pneumonia'), $out)) {
        return @json_decode($out[1], 1);
    } else {
        echo "页面内容" . $content;
    }
    return false;
}
