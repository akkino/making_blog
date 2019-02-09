<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  //クロスサイトリクエストフォージュリ(CSRF)対策
  $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
  $token = $_SESSION['token'];

  //クリックジャッキング対策
  header('X-FRAME-OPTIONS: SAMEORIGIN');


  $html = new HTML;

  $html->title = "メール登録画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader_notlogin();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <div class="registration_mail_form">
        <h1>メール登録画面</h1>

        <form action="registration_mail_check.php" method="post">

          <p>メールアドレス：<input type="text" name="mail" size="50"></p>

          <input type="hidden" name="token" value="<?=$token?>">
          <input type="submit" value="登録する">

        </form>
      </div>
    </div>
  </div>
  <?php
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

  ?>
