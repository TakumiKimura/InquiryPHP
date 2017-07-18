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
{if 0 < {$error_detail_count}}
    <div style="color:red;">エラーがあります</div>
{/if}

{if true == {$error_detail.error_must_email}}
    <div style="color:red;">メアドは必須です。</div>
{/if}

{if true == {$error_detail.error_csrf_token}}
    <div style="color:red;">CSRFトークンエラー。</div>
{/if}
{*
error_must_name
error_must_body
error_must_birthday
error_csrf_timeover
*}
<br>
	<form action="./inquiry_fin.php" method="post">
		emailアドレス(*):<input type="text" name="email" value="{$input.email}"><br><br>
		名前<input type="text" name="name" value="{$input.name}"><br><br>
		誕生日<input type="text" name="birthday" value="{$input.birthday}"><br><br>
		問い合わせ内容<textarea name="body" value="{$input.body}"></textarea><br><br>
         <input type="hidden" name="csrf_token" value="{$csrf_token}">

		<button>問い合わせる</button>
	</form>
</body>
</html>
