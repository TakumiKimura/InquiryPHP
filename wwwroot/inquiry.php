<?php
// inquiry.php
//
require_once( __DIR__.'/init.php');

//var_dump($_SESSION);

//入力内容を取得
//$input = $_SESSION['buffer']['input'] ?? [];//PHP 7.0以降ならこっち
if(true === isset($_SESSION['buffer']['input']))
{
   $input = $_SESSION['buffer']['input'];
}else{
   $input = array();
}
//エラー内容を取得
//$error_detail = $_SESSION['buffer']['error_detail']
if(true === isset($_SESSION['buffer']['error_detail']))
{
   $error_detail = $_SESSION['buffer']['error_detail'];
}else{
   $error_detail = array();
}

// セッションのバッファを消す
unset($_SESSION['buffer']);

//CSRFトークンを作成
//XXXPHP7前提
$csrf_token = hash('sha512',random_bytes(128));
//var_dump($csrf_token);

//CSRFトークンは10個まで(で後で追加するので、ここで
while (10 <= count(@$_SESSION['csrf_token']))
{
    array_shift($_SESSION['csrf_token']);
}

//CSRFトークンをSESSIONに入れておく:時間付き
$_SESSION['csrf_token'][$csrf_token] = time();

$smarty_obj->assign('input', $input);
$smarty_obj->assign('csrf_token', $csrf_token);
$smarty_obj->assign('error_detail_count', count($error_detail));
$smarty_obj->assign('error_detail', $error_detail);

// テンプレートを指定して出力
error_reporting(E_ALL & ~E_NOTICE);
$smarty_obj->display('inquiry.tpl');

//XSS対策用関数
function h($s)
{
    return htmlspecialchars($s,ENT_QUOTES);
}
