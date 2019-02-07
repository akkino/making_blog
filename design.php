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

  //記事とコメントの取得
  $sql = 'SELECT post.id as post_id,
    title,
    post.content as post_content,
    post.created_at as post_created_at,
    comment.id as comment_id,
    name,
    comment.content as comment_content,
    comment.created_at as comment_created_at
    FROM post
    LEFT JOIN comment
    ON post.id = comment.post_id
    WHERE post.id = ' . $blog_id . '
    ORDER BY comment.id DESC';
  $result = mysqli_query($database, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $blog_post[] = $row;
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
          <h2><?php print h($blog_post[0]['title']) ?></h2>
          <p><?php print h($blog_post[0]['post_content']) ?></p>
          <?php foreach ($blog_post as $comment) {?>
            <div class="comment">
              <h3><?php print h($comment['name']) ?></h3>
              <p><?php print h($comment['comment_content']) ?></p>
              <p><?php print h($comment['comment_created_at']) ?></p>
            </div>
          <?php } ?>
          <p class="commment_link">
            投稿日：<?php print h($blog_post[0]['post_created_at']) ?>
            <a href="comment.php?id=<?php print ($blog_post[0]['post_id']) ?>">コメント</a>
          </p>
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
