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
    <title>コメント投稿 | 自分のブログを作ってみよう！</title>
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
        <form method="post" action="comment.php">
          <div class="post">
            <h2>コメント投稿</h2>
            <p>お名前</p>
            <p><input type="text" name="name" size="40" value="<?php echo $name ?>"</p>
            <p>本文</p>
            <p><textarea name="content" rows="8" cols="40"><?php echo $content ?></textarea></p>
            <p>
              <input type="hidden" name="post_id" value="<?php $post_id = $_GET['id']; print h($post_id);?>">
              <input name="submit_add_comment" type="submit" value="投稿">
            </p>
            <?php
              foreach($erros as $value) {
                echo "<p>".$value."</p>";
              }
            ?>
          </div>
        </form>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
