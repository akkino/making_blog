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

	$mail = $_SESSION['mail'];
	$account = $_SESSION['account'];

	//パスワードのハッシュ化
	$password_hash =  password_hash($_SESSION['password'], PASSWORD_DEFAULT);

	//ここでデータベースに登録する
	try{
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//トランザクション開始
		$dbh->beginTransaction();

		$statement = $dbh->prepare("INSERT INTO user (account,email,password) VALUES (:account,:mail,:password_hash)");
		$statement->bindValue(':account', $account, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
		$statement->execute();

		//pre_memberのflagを1にする
		$statement = $dbh->prepare("UPDATE pre_user SET flag=1 WHERE email=(:mail)");
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->execute();

		// トランザクション完了（コミット）
		$dbh->commit();

		$dbh = null;

		//セッション変数を全て解除
		$_SESSION = array();

		//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
		if (isset($_COOKIE["PHPSESSID"])) {
	    		setcookie("PHPSESSID", '', time() - 1800, '/');
		}

	 	//セッションを破棄する
	 	session_destroy();

	 	/*
	 	登録完了のメールを送信
	 	*/

	}catch (PDOException $e){
		//トランザクション取り消し（ロールバック）
		$dbh->rollBack();
		$errors['error'] = "もう一度やりなおして下さい。";
		print('Error:'.$e->getMessage());
	}


	$html = new HTML;

  $html->title = "会員登録完了画面 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
			<div class="registration_insert">
				<?php if (count($errors) === 0): ?>
				    <h1>会員登録完了画面</h1>

				    <p>登録完了いたしました。ログイン画面からどうぞ。</p>
				    <p><a href="login_form.php">ログイン画面</a></p>

				<?php elseif(count($errors) > 0): ?>

				<?php
				foreach($errors as $value){
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
