<?php
include __DIR__ . '/../../arranger.php';
$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;

if (empty($courseID)) { //如果不是點編輯紐進來的就跳回
  header('Location:./view.php');
}
$r = connect()->query("SELECT * FROM Course WHERE SN=$courseID")->fetch();
if (empty($r)) { //如果亂給參數進來的就跳回
  header('Location:./view.php');
  exit;
}
connect()->query("UPDATE `Course` SET ApproverSN = 2 where SN=$courseID");

// 後用$_SERVER['HTTP_REFERER']回到原本的頁面
$backTo = './view.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $backTo = $_SERVER['HTTP_REFERER'];
}
header("Location: $backTo");//跳轉回清單頁
