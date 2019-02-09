<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
  $token = $_SESSION['token'];

  header('X-FRAME-OPTIONS: SAMEORIGIN');

  require_once "db.php";
  $dbh = db_connect();

  $errors = array();

  if (empty($_GET)) {
    header("Location: registration_mail_form.php");
    exit();
  }
  else {
    $urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;

    if ($urltoken == '') {
      $errors['urltoken'] = "もう一度登録をやり直してください。";
    }
    else {
      try {
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //flagが0の未登録者・仮登録日から24時間以内
        $statement = $dbh->prepare("SELECT email FROM pre_user WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
        $statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
        $statement->execute();

        //レコード件数取得
        $row_count = $statement->rowCount();

        //24時間以内に仮登録され、本登録されていないトークンの場合
        if ($row_count == 1) {
          $mail_array = $statement->fetch();
          $mail = $mail_array['email'];
          $_SESSION['mail'] = $mail;
        }
        else {
          $errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやり直してください。";
        }

        $dbh = null;
      }
      catch (PDOException $e) {
        print('Error:'.$e->getMessage());
        die();
      }
    }
  }


  $html = new HTML;

  $html->title = "会員登録画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <div class="registration_form">
        <h1>会員登録画面</h1>

        <?php if (count($errors) === 0): ?>

        <form action="registration_check.php" method="post">

          <p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p>
          <p>アカウント名：<input type="text" name="account"></p>
          <p>パスワード：<input type="text" name="password"></p>

          <input type="hidden" name="token" value="<?=$token?>">
          <input type="submit" value="確認する">

        </form>

      <?php elseif (count($errors) > 0): ?>
    <?php
              foreach ($errors as $value) {
                echo "<p>".$value."</p>";
              }
    ?>

    <?php endif; ?>
      </div>
    </div>
  </div>
  <?php
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

  ?>
