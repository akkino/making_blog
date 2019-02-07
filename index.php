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
    die('Connect Error (' . mysqli_connect_errno() . ')' .mysqli_connect_error());
  }

  $charset = '$utf8';
  mysqli_set_charset($database, $charset);

  //ここにMysqlを使った処理を書く

  //記事の取得
  $sql = 'SELECT * FROM post ORDER BY no DESC';
  $result = mysqli_query($database, $sql);
  $blog_list = mysqli_fetch_assoc($result);

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
          <a href="#">ここに他のページリンク</a>
        </nav>
      </div>
    </header>
    <div id="cover">
      <h1 id="cover_title">自分のブログを作ってみよう！</h1>

    </div>
    <div class="wrapper">
      <div id="main">
        <div id="blog_list" class="clearfix">
          ここにブログの記事一覧
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
