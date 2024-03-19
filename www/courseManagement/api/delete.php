<?php
include __DIR__ . '/../parts/PDOconnect.php';

$courseID = isset($_GET['courseID']) ? intval($_GET['courseID']) : 0;

$pdo->query('DELETE FROM `course` WHERE courseID = '.$courseID);

$backTo = './html-sort.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $backTo = $_SERVER['HTTP_REFERER'];
}
header("Location: $backTo");//跳轉回編輯頁