<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  //クロスサイトリクエストフォージュリ(CSRF)対策トークン判定
  if ($_POST['token'] != $_SESSION['token']) {
    echo "不正アクセスの可能性あり";
    exit();
  }

  //クリックジャッキング対策
  header('X-FRAME-OPTIONS: SAMEORIGIN');


  require_once "db.php";
  $dbh = db_connect();

  //前後にある半角全角スペースを削除する関数
  function spaceTrim ($str) {
    //行頭
    $str = preg_replace('/^[ 　]+/u', '', $str);
    //末尾
    $str = preg_replace('/^[ 　]+/u', '', $str);
    return $str;
  }

  //エラーメッセージの初期化
  $errors = array();

  if (empty($_POST)) {
    header("Location: login_form.php");
    exit();
  }
  else {
    //POSTされたデータを各変数に入れる
    $account = isset($_POST['account']) ? $_POST['account'] : NULL;
    $password = isset($_POST['password']) ? $_POST['password'] : NULL;

  //前後にある半角全角スペースを削除
  $account = spaceTrim($account);
  $password = spaceTrim($password);

  //アカウント入力判定
  if ($account == ''):
    $errors['account'] = "アカウントが入力されていません。";
  elseif (mb_strlen($account) > 10):
    $errors['account_length'] = 'アカウントは10文字以内で入力してください。';
  endif;

  //パスワード入力判定
  if ($password == ''):
    $errors['password'] = "パスワードが入力されていません。";
  elseif (!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
    $errors['passwprd_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力してください。";
  else:
    $password_hide = str_repeat('*', strlen($password));
  endif;

  }

  //エラーが無ければ実行する
  if(count($errors) === 0) {
    try{
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $statement = $dbh->prepare("SELECT * FROM user WHERE account=(:account) AND flag =1");
      $statement->bindValue(':account', $account, PDO::PARAM_STR);
      $statement->execute();

      if($row = $statement->fetch()){

  			$password_hash = $row['password'];

        //パスワードが一致
        if (password_verify($password, $password_hash)) {

          //セッションハイジャック対策
          session_regenerate_id(true);

          $_SESSION['account'] = $account;
          $_SESSION['user_id'] = $row['id'];
          header("Location: index.php");
          exit();
        }
        else {
          $errors['password'] = "アカウント及びパスワードが一致しません。";
        }
      }
      else {
        $errors['account'] = "アカウント及びパスワードが一致しません。";
      }

      $dbh = null;

    }
    catch (PDOException $e) {
      print('Error:' . $e->getMessage());
      die();
    }
  }


  $html = new HTML;

  $html->title = "ログイン確認画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader_notlogin();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <h1>ログイン確認画面</h1>

  <?php
  if (count($errors) > 0):
    foreach ($errors as $value) {
      echo "<p>".$value."<p>";
    }
    ?>

  <input type="button" value="戻る" onClick="history.back()">

  <?php endif;
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

  ?>
