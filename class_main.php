<?php


function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

//ログイン状態のチェック
function login_check($account) {
  if (!isset($account)) {
    header("Location: login_form.php");
    exit();
  }
}



?>
