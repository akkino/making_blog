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
  //記事の削除
  if ($_POST['submit_blog_delete']) {
    $sql = 'DELETE FROM post WHERE id=?';
    $statement = mysqli_prepare($database, $sql);
    mysqli_stmt_bind_param($statement, 'i', $_POST['post_id']);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
  }

  //全ての記事の取得
  $sql = 'SELECT * FROM post ORDER BY created_at DESC';
  $result = mysqli_query($database, $sql);

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
          <a href="./t_post.php">記事投稿</a>
        </nav>
      </div>
    </header>
    <div id="cover">
      <h1 id="cover_title">自分のブログを作ってみよう！</h1>

    </div>
    <div class="wrapper">
      <div id="main">
        <div id="blog_list" class="clearfix">
<?php
          if ($result) {
            while ($record = mysqli_fetch_assoc($result)) {
              $id = $record['id'];
              $title = $record['title'];
              $created_at = $record['created_at'];
?>
              <div class="blog_item">
                <div class="blog_image">

                </div>
                <div class="blog_detail">
                  <div class="blog_title">
                    <form method="get" name="go_design" action="./design.php">
                      <input type="hidden" name="post_id" value="<?php print h($id); ?>">
                      <a href="./design.php?post_id=<?php print h($id); ?>"><?php print h($title); ?></a>
                    </form>
                  </div>
                  <form action="index.php" method="post">
                    <input type="hidden" name="post_id" value="<?php print h($id); ?>">
                    <div class="blog_delete">
                      <input type="submit" name="submit_blog_delete" value="削除する">
                    </div>
                  </form>
                  <div class="blog_created_at">
                    <?php print h($created_at); ?>
                  </div>
                </div>
              </div>
<?php
            }
            mysqli_free_result($result);
          }
?>
          </form>
        </div>
      </div>
    </div>
    <footer>
      <small>© 2019 making blog.</small>
    </footer>
  </body>
</html>
