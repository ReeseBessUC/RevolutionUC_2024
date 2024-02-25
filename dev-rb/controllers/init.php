<?php
$dir = $_SERVER['DOCUMENT_ROOT'] . "wp-content/themes/dev-rb/controllers";

foreach (glob("{$dir}/*.php") as $filename)
{
    if (str_contains($filename, "init.php")) continue;
    include $filename;
}