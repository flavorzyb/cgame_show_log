<?php
header("content-type:text/html; charset=utf-8");
header('Cache-Control:must-revalidate, max-age=1');
date_default_timezone_set('Asia/Shanghai');
$v = (isset($_GET['v']) ? $_GET['v'] : "dev");
$path = (isset($_GET['path']) ? $_GET['path'] : "");
if ($v != "master")
{
    $v = "dev";
}
$configArray = include_once 'config_log.php';
if (!isset($configArray[$v]))
{
    die("出错了!");
}

$rootPath = $configArray[$v]['root_path'];
$fileName = substr($path, strlen($rootPath) + 1);
if (!is_file($path))
{
    die("出错了!");
}
$content = file_get_contents($path);
$content = str_replace("\n", "<br/>", $content);
$explodeStr = "----------------------------------------------------------------------------------------------------------------------------------------<br/>";
$contentArray = explode($explodeStr, $content);
$len = sizeof($contentArray);
$content = '';
for ($i = $len - 1; $i>=0; $i--)
{
    if (strlen($contentArray[$i]) > 1)
    {
        $content .= $contentArray[$i];
        $content .= $explodeStr;
    }
}
$url = "showlog.php?v={$v}&path=".urlencode($path);

if (strlen($content) > 30)
{
    header("Refresh:90; url={$url}");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>错误日志---<?php echo $v; ?></title>
</head>
<body>
<p align="center">错误日志--<?php echo $fileName; ?></p>
<p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;时间:&nbsp;&nbsp;<?php echo date("Y-m-d H:i:s");?></p>
<p align="right"><a href="index.php?v=<?php echo $v; ?>">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
<p><br/><br/><br/></p>
<p align="left"><?php echo $content; ?></p>
<p align="center"><br/><br/><br/></p>
</body>
</html>
