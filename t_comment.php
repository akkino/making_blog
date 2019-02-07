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
          ここに他のページリンク
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
              <input type="hidden" name="post_id" value="<?php echo $post_id ?>">
              <input name="submit" type="submit" value="投稿">
            </p>
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
