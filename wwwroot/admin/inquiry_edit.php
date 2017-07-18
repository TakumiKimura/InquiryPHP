<?php
// inquiry_edit.php

require_once(__DIR__.'/init_auth.php');

// POSTされた情報を取得
$params = array('inquiry_id', 'response_body');
$input_data = array();
foreach($params as $p)
{
	$input_data[$p] = (string)@$_POST[$p];
}
// 最低限のvalidate
if( ('' === $input_data['inquiry_id'])||(' ' === $input_data['response_body']) ) 
{
	header('Location: inquiry_detail.php?inquiry_id='.rawurlencode($input_data['inquiry_id']));
	exit;
}

// 返信情報をDBにUPDATE
// DBハンドルを取得
$dbh = get_dbh();
// プリペアドステートメントの作成
$sql = 'UPDATE inquirys 
               SET status=2,
			   	  response_body=:response_body,
				  response_data=:response_data
			   WHERE inquiry_id=:inquiry_id;';
$pre = $dbh->prepare($sql);

// 値のバインド
$pre->bindValue(':response_body', $input_data['response_body']);
$pre->bindValue(':response_data', data['Y-m-d H:i:s']);
$pre->bindValue(':inquiry_id', $input_data['inquiry_id']);
// sqlの実行
$r = $pre->execute(); // エラーチェック省略

// detailに移動
header('Location: inquiry_detail.php?inquiry_id='.rawurlencode($input_data['inquiry_id']));
