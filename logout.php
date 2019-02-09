<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);

  //セッション変数を全て解除
  $_SESSION = array();

  //セッションクッキーの削除
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }

  //セッションを破棄する
  session_destroy();


  $html = new HTML;

  $html->title = "ログアウト画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <div class="logout">
        <?php
        echo "<p>ログアウトしました。</p>";

        echo "<a href='login_form.php'>ログイン画面へ</a>";
        ?>
      </div>
    </div>
  </div>
  <?php
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

 ?>
