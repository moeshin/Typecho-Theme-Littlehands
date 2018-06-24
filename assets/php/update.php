<?php
/**
 * 检测更新
 * 
 * @author 小さな手は
 * @link https://www.littlehands.site/
 */
header("Content-type: text/javascript; charset=utf-8");

$ver = @file_get_contents('http://api.littlehands.site/Typecho/Theme/Littlehands/version.txt') or exit;

require '..\..\..\..\..\var\Typecho\Plugin.php';

echo version_compare($ver, Typecho_Plugin::parseInfo('..\..\index.php')['version']);
?>