<?php
	session_start();

	require_once "class_html.php";
	require_once "class_main.php";

	header("Content-type: text/html; charset=utf-8");

	//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
	if ($_POST['token'] != $_SESSION['token']){
		echo "不正アクセスの可能性あり";
		exit();
	}

	//クリックジャッキング対策
	header('X-FRAME-OPTIONS: SAMEORIGIN');

	require_once "db.php";
	$dbh = db_connect();

	$errors = array();

	if(empty($_POST)) {
	  header("Location: registration_mail_form.php");
	  exit();
	}
	else {
	  $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

	  if ($mail == '') {
	    $errors['mail'] = "メールが入力されていません。";
	    }
	    else {
	      if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)) {
	        $errors['mail_check'] = "メールアドレスの形式が正しくありません。";
	    }
	    /*
			ここで本登録用のmemberテーブルにすでに登録されているmailかどうかをチェックする。
			$errors['member_check'] = "このメールアドレスはすでに利用されております。";
			*/
	  }
	}

	if (count($errors) == 0) {
	  $urltoken = hash('sha256',uniqid(rand(),1));
	  $url = "http://192.168.33.10:8000/registration_form.php"."?urltoken=".$urltoken;

	  try {
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $statement = $dbh->prepare("INSERT INTO pre_user (urltoken,email,date) VALUES (:urltoken,:mail,now())");

	    $statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
	    $statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	    $statement->execute();

	    $dbh = null;
	  }
	  catch (PDOException $e) {
	    print('Error:'.$e->getMessage());
	    die();
	  }

	  //メールの宛先
	  $mailTo = $mail;

	  //Return-Pathに指定するメールアドレス
	  $returnMail = "web@sample.com";

	  $name = "making_blog";
	  $mail = 'web@sample.com';
	  $subject = "【making_blog】会員登録用URLのお知らせ";

$body = <<< EOM
24時間以内に下記のURLからご登録下さい。
{url}
EOM;

	  mb_language('ja');
	  mb_internal_encoding('utf-8');

	  //Fromヘッダーを作成
	  $header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';

	  if (mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)) {

	    //セッション変数を全て解除
	    $_SESSION = array();

	    //クッキーの削除
	    if (isset($_COOKIE["PHPSESSION"])) {
	      setcookie("PHPSESSID", '', time() - 1800, '/');
	    }

	    //セッションを破棄する
	    session_destroy();

	    $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
	  }
	  else {
	    $errors['mail_error'] = "メールの送信に失敗しました。";
	  }
	}


	$html = new HTML;

	$html->title = "メール確認画面 | 自分のブログを作ってみよう！";

	$head = $html->HtmlHead();
	$header = $html->HtmlHeader();
	$footer = $html->Htmlfooter();

	ob_start();
	?>
	<div class="wrapper">
		<div id="main">
			<div class="registration_mail_check">
				<h1>メール確認画面</h1>

				<?php if (count($errors) === 0): ?>

					<p><?=$message?></p>

					<p>↓このURLが記載されたメールが届きます。</p>
					<a href="<?=$url?>"><?=$url?></a>

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
