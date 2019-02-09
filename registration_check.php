<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  if ($_POST['token'] != $_SESSION['token']){
  	echo "不正アクセスの可能性あり";
  	exit();
  }

  header('X-FRAME-OPTIONS: SAMEORIGIN');

  function spaceTrim ($str) {
    $str = preg_replace('/^[ 　]+/u', '', $str);
  	$str = preg_replace('/[ 　]+$/u', '', $str);
  	return $str;
  }

  $errors = array();

  if (empty($_POST)) {
    header("Location: registration_mail_form.php");
    exit();
  }
  else {
    $account = isset($_POST['account']) ? $_POST['account'] : NULL;
    $password = isset($_POST['password']) ? $_POST['password']: NULL;

    $account = spaceTrim($account);
    $password = spaceTrim($password);

    if($account == ''):
      $errors['account'] = "アカウントが入力されていません。";
    elseif(mb_strlen($account) > 10):
      $errors['account_length'] = "アカウントは10文字以内で入力してください。";
    endif;

    if($password == ''):
      $errros['password'] = "パスワードが入力されていません。";
    elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
      $errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力してください。";
    else:
      $password_hide = str_repeat('*', strlen($password));
    endif;

  }

  if(count($errors) === 0) {
    $_SESSION['account'] = $account;
    $_SESSION['password'] = $password;
  }


  $html = new HTML;

  $html->title = "会員登録確認画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader_notlogin();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <div class="registration_check">
        <h1>会員登録確認画面</h1>

        <?php if(count($errors) === 0): ?>

        <form action="registration_insert.php" method="post">

          <p>メールアドレス：<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p>
          <p>アカウント名：<?=htmlspecialchars($account, ENT_QUOTES)?></p>
          <p>パスワード：<?=$password_hide?></p>

          <input type="button" value="戻る" onClick="history.back()">
          <input type="hidden" name="token" value="<?=$_POST['token']?>">
          <input type="submit" value="登録する">

        </form>

      <?php elseif(count($errors) > 0): ?>
      <?php
              foreach($errors as $value) {
                echo "<p>".$value."</p>";
              }
      ?>
                <input type="button" value="戻る" onClick="history.back()">
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
