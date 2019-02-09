<?php

  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);


  require_once "db.php";
  $dbh = db_connect();

  //blog_idの引継ぎ
  $post_id = $user_id = $comment_id = '';
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
    //コメントの削除
    if (isset($_POST['submit_comment_delete'])) {
      try {
        $comment_id = $_POST['comment_id'];

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $dbh->prepare("DELETE FROM comment WHERE id=(:comment_id)");
        $statement->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
        $statement->execute();

        $statement = null;
      }
      catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
      }
    }

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

      $dbh = null;
    }
    catch (PDOException $e) {
      print('Error:' . $e->getMessage());
      die();
    }
  }

  $html = new HTML;

  $html->title = "making_blog | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <div class="post">
        <h2><?php print h($blog_post['title']); ?></h2>
        <p><?php print h($blog_post['content']); ?></p>
        <img src="<?php if(isset($blog_post['blog_imag'])): print h($blog_post['blog_image']); endif;?>" alt="">
        <p>投稿日：<?php print h($blog_post['created_at']); ?></p>

        <?php if($blog_post['user_id'] == $_SESSION['user_id']) { ?>
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

            <?php if ($comment['user_id'] == $_SESSION['user_id']) { ?>
              <form action="./design.php?post_id=<?php print h($blog_post['id']); ?>" method="post">
                <input type="hidden" name="comment_id" value="<?=$comment['id']?>">
                <div class="comment_delete">
                  <input type="submit" name="submit_comment_delete" value="削除する"
                </div>
              </form>
            <?php } ?>
          </div>
        <?php } ?>
        <p class="commment_link">
          <form action="t_comment.php" mrthod="get" name="go_comment">
            <input type="hidden" name="post_id" value="<?php print h($blog_post['id']); ?>">
            <a href="t_comment.php?id=<?php print h($blog_post['id']); ?>">コメント</a>
          </form>
        </p>
      </div>
    </div>
  </div>
  <?php
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

 ?>
