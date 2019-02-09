<!DOCTYPE html>
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

  require_once("db.php");
  $dbh = db_connect();


  $errors = $blogs = array();
  $post_id = 0;

  if (count($errors) === 0) {
    //記事の削除
    if (isset($_POST['submit_blog_delete'])) {
      try {
        $post_id = $_POST['post_id'];

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $dbh->prepare("DELETE FROM post WHERE id=(:post_id)");
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        $statement = null;
      }
      catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
      }
    }

    //全ての記事の取得
    try {
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $statement = $dbh->prepare("SELECT * FROM post ORDER BY created_at DESC");
      $statement->execute();

      $blogs = $statement->fetchALL();

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
    <div id="cover">
      <h1 id="cover_title">自分のブログを作ってみよう！</h1>

    </div>
    <div class="wrapper">
      <div id="main">
        <div id="blog_list" class="clearfix">
<?php     foreach ($blogs as $blog_item) { ?>
            <div class="blog_item">
              <div class="blog_image">
                <!-- ここにサムネ表示 -->
              </div>
              <div class="blog_detail">
                <div class="blog_title">
                  <form method="get" name="go_design" action="./design.php">
                    <input type="hidden" name="psot_id" value="<?php print h($blog_item['id']); ?>">
                    <a href="./design.php?post_id=<?php print h($blog_item['id']); ?>"><?php print h($blog_item['title']); ?></a>
                  </form>
                  <div class="blog_created_at">
                    <?php print h($blog_item['created_at']); ?>
                  </div>
                </div>
              </div>
            </div>
        <?php } ?>
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
