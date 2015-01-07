<?php
header("content-type:text/html; charset=utf-8");
header('Cache-Control:must-revalidate, max-age=1');
date_default_timezone_set('Asia/Shanghai');
$v = (isset($_GET['v']) ? $_GET['v'] : "master");
if ($v != "dev")
{
    $v = "master";
}
$configArray = include_once 'config_log.php';
if (!isset($configArray[$v]))
{
    die("出错了!");
}

$serverno = $configArray[$v]['server_no'];
$rootPath = $configArray[$v]['root_path'];

$dateArray = array();
$date = time();

$dateErrorArray = array();

$dataFightArray = array();

for($i = 0; $i < 30; $i++)
{
    $time = strtotime("- " . $i ." days");
    $year = date("Y", $time);
    $month = date("m", $time);
    $day = date("d", $time);
    
    $filename = sprintf("%s/%d/%d/%d/djsg_%s.log", $rootPath, $serverno, $year, $month, $day);
    if (is_file($filename))
    {
        $dateArray[] = array('path'=>urlencode($filename), 'content'=> sprintf("%d年%d月%d日", $year, $month, $day));
    }
    
    $filename = sprintf("%s/%d/%d/%d/djsg_%s.error_log", $rootPath, $serverno, $year, $month, $day);
    if (is_file($filename))
    {
    	$dateErrorArray[] = array('path'=>urlencode($filename), 'content'=> sprintf("%d年%d月%d日", $year, $month, $day));
    }
    
    $filename = sprintf("%s/%d/%d/%d/djsg_%s.data_log", $rootPath, $serverno, $year, $month, $day);
    if (is_file($filename))
    {
    	$dataFightArray[] = array('path'=>urlencode($filename), 'content'=> sprintf("%d年%d月%d日", $year, $month, $day));
    }
}

$maxLen = (sizeof($dateArray) > sizeof($dateErrorArray) ? sizeof($dateArray) : sizeof($dateErrorArray));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>日志列表---<?php echo $v; ?></title>
</head>
<body>
<p align="center">日志列表(列出最近20天以内)--<?php echo $v; ?></p>
<p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;时间:&nbsp;&nbsp;<?php echo date("Y-m-d H:i:s");?></p>
<p><br/><br/><br/></p>
<table width="80%" border="1" align="center">
  <tr>
    <td align="center" bgcolor="#CC9999">崩溃日志</td>
    <td align="center" bgcolor="#FFCC66">一般lua错误日志</td>
    <td align="center" bgcolor="#FF0066">战斗数据验证失败日志</td>
  </tr>
<?php for ($i = 0; $i < $maxLen; $i++) {  ?>
  <tr>
    <td align="center" ><?php if (isset($dateArray[$i])) { echo "<a href=\"showlog.php?v={$v}&path={$dateArray[$i]['path']}\">{$dateArray[$i]['content']}</a>";} else { echo "&nbsp;";} ?></td>
    <td align="center" ><?php if (isset($dateErrorArray[$i])) { echo "<a href=\"showlog.php?v={$v}&path={$dateErrorArray[$i]['path']}\">{$dateErrorArray[$i]['content']}</a>";} else { echo "&nbsp;";} ?></td>
    <td align="center" ><?php if (isset($dataFightArray[$i])) { echo "<a href=\"showlog.php?v={$v}&path={$dataFightArray[$i]['path']}\">{$dataFightArray[$i]['content']}</a>";} else { echo "&nbsp;";} ?></td>
  </tr>
  <?php } ?>
</table>
<p align="center"><br/><br/><br/></p>
</body>
</html>
