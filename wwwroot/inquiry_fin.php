<?php
// inquiry_fin.php
//
ob_start();
session_start();

//var_dump($_POST);
//$email = @$_POST['email'];
//$email = filter_input(INPUT_POST,'email');
//$params = array(); //php5.3
//$params = []; //php5.4
//foreach($pms as $key => $p) //これはダメ
//入力された情報を取得
$params = array('email','name','birthday','body');
$input_data = array();
foreach($params as $p)
{
	$input_data[$p] = (string)@$_POST[$p];
}
//var_dump($input_data);

//validate(情報は正しいか?)
$error_detail = array();//エラー情報格納用変数

//CSRFチェック
//tokenの存在確認(check exist)
$posted_token = $_POST['csrf_token'];
//var_dump($psted_token);
//var_dump($_SESSION['csrf_token']);
//var_dump($_SESSION['csrf_token'][$posted_token]);
//var_dump(isset($_SESSION['csrf_token'][$posted_token]));
//exit;

if(false === isset($_SESSION['csrf_token'][$posted_token]))
{
    //tokenが無いのでエラー
    $error_detail['error_csrf_token'] = true;
}else{
    //tokenの寿命確認(check life)
    $ttl = $_SESSION['csrf_token'][$posted_token];
    if(time() >= $ttl + 60)
    {
        //token作成から60秒以上経過しているのでNG
        $error_detail['error_csrf_timeover'] = true;
    }
    //いずれにしてもtokenは一回しか使えないので、消す。
    unset($_SESSION['csrf_token'][$posted_token]);
}

//tokenの寿命確認(check life)



// 必須チェック
$must_params = array('email','body');
foreach($must_params as $p)
{
	if('' === $input_data[$p])
	{
		//エラー処理
		$error_detail["error_must_{$p}"] = true;
	}
}
//型チェック:email
//XXX RFC非準拠のめあどしらない
if(false === filter_var($input_data['email'],FILTER_VALIDATE_EMAIL))
{
	//エラー処理
	$error_detail["error_format_email"] = true;
}

//型チェック:日付
if('' !== $input_data['birthday'])
{
	if(false === strtotime($input_data['birthday']))
	{
		//エラー処理
		$error_detail["error_format_birthday"] = true;
	}
}


//エラー判定
if(array() != $error_detail)
{
	//エラー内容をセッションに保持する
	$_SESSION['buffer']['error_detail']=$error_detail;
	//入力情報をセッションに保持する
	$_SESSION['buffer']['input']=$input_data;
	//var_dump($error_detail);
	//echo 'エラーかもー';
	header('Location: ./inquiry.php');
	exit;
}
//ダミー
echo 'データのばりでーとはオッケーでしたー';

//入力された情報をDBにinsert


//「ありがとう」Pageの出力


