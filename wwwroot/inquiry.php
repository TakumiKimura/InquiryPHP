<?php
// inquiry.php
//
ob_start();
session_start();

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


//XSS対策用関数
function h($s)
{
    return htmlspecialchars($s,ENT_QUOTES);
}

?>
<html>

<style>
body {
margin: 0;
padding: 0;
line-height:1.4;
color:#333;
font-family:Arial, sans-serif;
font-size:0.9em;
}
</style>

<body>
<?php
 if(0 < count($error_detail))
 {
   echo '<div style="color:red;">エラーがあります</div>';
 }
?>

<?php
//error_must_email
if(isset($error_detail['error_must_email']))
{
  echo '<div style="color:red;">メアドは必須です。</div>';
}

if(isset($error_detail['error_csrf_token']))
{
  echo '<div style="color:red;">CSRFトークンエラー。</div>';
}

//error_must_name
//error_must_body
//error_must_birthday
?>

	<form action="./inquiry_fin.php" method="post">
		emailアドレス(*):<input type="text" name="email" value="<?php echo h((string)@$input['email']);?>"><br><br>
		名前<input type="text" name="name" value="<?php echo h((string)@$input['name']);?>"><br><br>
		誕生日<input type="text" name="birthday" value="<?php echo h((string)@$input['birthday']);?>"><br><br>
		問い合わせ内容<textarea name="body" value="<?php echo h((string)@$input['body']);?>"></textarea><br><br>
         <input type="hidden" name="csrf_token" value="<?php echo h($csrf_token); ?>">

		<button>問い合わせる</button>
	</form>
</body>
</html>
