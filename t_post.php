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
          ここに他のページリンク
        </nav>
      </div>
    </header>
    <div class="wrapper">
      <div id="main">
        <form method="post" action="post.php">
          <div class="post">
            <h2>記事投稿</h2>
            <p>題名</p>
            <p><input type="text" name="title" size="40" value="<?php echo $title ?>"</p>
            <p>本文</p>
            <p><textarea name="content" rows="8" cols="40"><?php echo $content ?></textarea></p>
            <p><input name="submit" type="submit" value="投稿"></p>
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
