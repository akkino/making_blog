<?php
session_start();

header("Content-type: text/hetml; charset=utf-8");

if ($_POST['token'] != $_SESSION['token']) {
  echo "不正アクセスの可能性あり";
  exit();
}

header('X-FRAME-OPTIONS: SAMEOTIGIN');

require_once("db.php");
$dbh = db_connect();

$errors = array();

if(empty($_POST)) {
  header("Location: registration_mail_form.php");
  exit();
}

$mail = $_SESSION['mail'];
$account = $_SESSION['account'];

//パスワードのハッシュ化
$password_hash = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

//ここでデータベースに登録する
try {
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //トランザクション開始
  $dbh->beginTransaction();

  //userテーブルに本登録する
  $statement = $dbh->prepare("INSERT INTO user (account,email,password) VALUES (:account,:mail, :password_hash)");
  //プレースホルダへ実際の値を設定する
  $statement->bindValue(':account', $account, PDO::PATAM_STR);
  $statement->bindValue(':mail', $mail, PDO::PATAM_STR);
  $statement->bindValue(':password_hash', $password_hash, PDO::PATAM_STR);
  $statement->execute();

  //pre_userのflagを1にする
  $statement = $dbh->prepare("UPDATE pre_user SET flag=1 WHERE email=(:mail)");
  //プレースホルダへ実際の値を設定する
  $statement->bindValue(':mail', $mail, PDO::PARAM_STR;
  $statement->execute();

  //トランザクション完了（コミット）
  $dbh->commit();

  $dbh = null;

  $_SESSION = array();

  //セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsessionidを名前でやる
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }

  session_destroy();

  /*
  登録完了メールを送信
  */

}
catch(PDOException $e) {
  //トランザクション取り消し（ロールバック）
  $dbh->rollBack();
  $errors['error'] = "もう一度やり直してください。";
  print('Error:'.$e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <title>会員登録完了画面</title>
    <meta charset="utf-8">
  </head>
  <body>

    <?php if(count($errors) === 0): ?>
      <h1>会員登録完了画面</h1>

      <p>登録完了いたしました。ログイン画面からどうぞ。</p>
      <p><a href="login_form.php">ログイン画面</a></p>

    <?php elseif(count($errors) > 0): ?>

<?php
      foreach ($errors as $value) {
        echo "<p>".$value."</p>";
      }
?>

<?php endif; ?>

  </body>
</html>
