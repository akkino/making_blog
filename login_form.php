<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  //クロスサイトリクエストフォージュリ (CSRF)対策
  $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
  $token = $_SESSION['token'];

  //クリックジャッキング対策
  header('X-FRAME-OPTIONS: SAMEORIGIN');


  $html = new HTML;

  $html->title = "ログイン画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader_notlogin();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <div class="login_form">
      <h1>ログイン画面</h1>

      <form action="login_check.php" method="post">

        <p>アカウント：<input type="text" name="account" size="50"></p>
        <p>パスワード：<input type="text" name="password" size="50"></p>

        <input type="hidden" name="token" value="<?=$token?>">
        <input type="submit" value="ログインする">

      </form>
    </div>
    <div class="account_making">
      <p><a href="registration_mail_form.php">アカウント登録はこちらから</a></p>
    </div>
  <?php
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

?>
