<!DOCTYPE html>
<?php

session_start();

header("Content-type: text/html; charset=utf-8");

if (!isset($_SESSION["account"])) {
  header("Location: login_form.php");
  exit();
}

  function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }

  require_once("db.php");
  $dbh = db_connect();

  //blog_idの引継ぎ
  $post_id = $user_id = '';
  $errors = array();

  if (empty($_GET)) {
    header("Location: index.php");
    exit;
  }
  else {
    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : NULL;

    //エラー処理はここに追加
  }

  if (count($errors) === 0) {
    //記事の取得
    try {
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $statement = $dbh->prepare("SELECT * FROM post WHERE id=(:post_id)");
      $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);
      $statement->execute();

      $blog_post = $statement->fetch();

      $statement = null;
    }
    catch (PDOException $e) {
      print('Error:' . $e->getMessage());
      die();
    }

    //コメントの取得
    try {
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $statement = $dbh->prepare("SELECT * FROM comment WHERE post_id=(:post_id) ORDER BY id");
      $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);
      $statement->execute();

      $blog_comment = $statement->fetchALL();

      $statement = null;
    }
    catch (PDOException $e) {
      print('Error:' . $e->getMessage());
      die();
    }

    //投稿者の取得
    try {
      $user_id = $blog_post['user_id'];

      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $statement = $dbh->prepare("SELECT id, account FROM user WHERE id=(:user_id)");
      $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);
      $statement->execute();

      $blog_user = $statement->fetch();

      $dbh = null;
    }
    catch (PDOException $e) {
      print('Error:' . $e->getMessage());
      die();
    }
  }

 ?>

<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>making_blog | 自分のブログを作ってみよう！</title>
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
        <div class="post">
          <h2><?php print h($blog_post['title']); ?></h2>
          <p><?php print h($blog_post['content']); ?></p>
          <img src="<?php print h($blog_post['blog_image']); ?>" alt="">

          <?php if($account == $blog_user['account']) { ?>
            <form action="index.php" method="post">
              <input type="hidden" name="post_id" value="<?=$post_id?>">
              <div class="blog_delete">
                <input type="submit" name="submit_blog_delete" value="削除する"
              </div>
            </form>
          <?php } ?>

          <?php foreach ($blog_comment as $comment) {?>
            <div class="comment">
              <h3><?php print h($comment['name']); ?></h3>
              <p><?php print h($comment['content']); ?></p>
              <p><?php print h($comment['created_at']); ?></p>
            </div>
          <?php } ?>
          <p class="commment_link">
            投稿日：<?php print h($blog_post['created_at']); ?>
            <a href="comment.php?id=<?php print h($blog_post['id']); ?>">コメント</a>
          </p>
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
