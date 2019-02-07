<!DOCTYPE html>
<?php
  function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }

  $host = 'localhost';
  $username = 'root';
  $password = '';
  $db_name = 'making_blog';

  $database = mysqli_connect($host, $username, $password, $db_name);

  if ($database == false) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
  }

  $charset = 'utf8';
  mysqli_set_charset($database, $charset);

  //ここにMysqlを使った処理を書く
  //blog_idの引継ぎ
  $blog_id = $error = '';
  if (@$_GET['post_id']) {
    $blog_id = strip_tags($_GET['post_id']);
  }

  //記事の取得
  $sql = 'SELECT * FROM post
    WHERE id = ' . $blog_id . '';
  $result = mysqli_query($database, $sql);
  $blog_post = mysqli_fetch_assoc($result);

  //コメントの取得
  $sql = 'SELECT * FROM comment
    WHERE post_id = ' . $blog_id . '
    ORDER BY id ';
  $result = mysqli_query($database, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $blog_comment[] = $row;
  }

  mysqli_close($database);
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
          ここに他のページリンク
        </nav>
      </div>
    </header>
    <div class="wrapper">
      <div id="main">
        <div class="post">
          <h2><?php print h($blog_post['title']) ?></h2>
          <p><?php print h($blog_post['content']) ?></p>
          <?php foreach ($blog_comment as $comment) {?>
            <div class="comment">
              <h3><?php print h($comment['name']) ?></h3>
              <p><?php print h($comment['content']) ?></p>
              <p><?php print h($comment['created_at']) ?></p>
            </div>
          <?php } ?>
          <p class="commment_link">
            投稿日：<?php print h($blog_post['created_at']) ?>
            <a href="comment.php?id=<?php print ($blog_post['id']) ?>">コメント</a>
          </p>
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
