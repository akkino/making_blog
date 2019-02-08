<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//ログイン状態のチェック
if (!isset($_SESSION["account"])) {
  header("Location: login_form.php");
  exit();
}

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>記事投稿 | 自分のブログを作ってみよう！</title>
    <link rel ="stylesheet" href="making_blog.css">
  </head>
  <body>
    <header>
      <div id="header">
        <div id="logo">
          <a href="./index.php">making blog</a>
        </div>
        <nav>
          <ul>
            <li><a href="./t_post.php">記事投稿</a></li>
            <li>login user:<?php $account = $_SESSION['account']; print h($account); ?></li>
            <li><a href='logout.php'>ログアウトする</a></li>
          </ul>
        </nav>
      </div>
    </header>
    <div class="wrapper">
      <div id="main">
        <h2>記事投稿</h2>
        <form method="post" action="post.php" class="form_blog" enctype="multipart/form-data">
          <div class="blog_title">
            <p><input type="text" name="add_blog_title" size="40" value="<?php echo $title ?>" placeholder="ブログタイトルを入力"></p>
          </div>
          <div class="blog_content">
            <p>本文</p>
            <p><textarea name="content" rows="8" cols="40"><?php echo $content ?></textarea></p>
          </div>
          <div class="blog_image">
            <input type="file" name="add_blog_image">
          </div>
          <div class="blog_submit">
            <p><input name="submit_add_blog" type="submit" value="投稿"></p>
          </div>
            <p><?php echo $error ?></p>
          </div>
        </form>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
