<?php
// session_2.php
ob_start();
session_start();

// 乱数を表示
var_dump($_SESSION['rand']);
