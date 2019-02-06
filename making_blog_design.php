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

  //記事の取得
  $blog_id = 1;
  $sql = 'SELECT * FROM post
    WHERE id = ' . $blog_id . '';
  $result = mysqli_query($database, $sql);
  $blog_data = mysqli_fetch_assoc($result);

  //コメントの取得
  $sql = 'SELECT * FROM comment
    WHERE post_id = ' . $blog_id . '
    ORDER BY id DESC';
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
          <a href="./making_blog_index.php">making blog</a>
        </div>
        <nav>
          ここに他のページリンク
        </nav>
      </div>
    </header>
    <div class="wrapper">
      <div id="main">
        <div class="post">
          <h2><?php echo $blog_data['title'] ?></h2>
          <p><?php echo ($blog_data['content']) ?></p>
          <?php foreach ($blog_comment as $comment) {?>
            <div class="comment">
              <h3><?php echo ($comment['name']) ?></h3>
              <p><?php echo ($comment['content']) ?></p>
            </div>
          <?php } ?>
          <p class="commment_link">
            投稿日：<?php echo $blog_data['created_at'] ?>
            <a href="comment.php?id=<?php echo $blog_data['id'] ?>">コメント</a>
          </p>
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
