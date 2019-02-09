<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);


  $errors = array();
  $post_id = $user_id = $name = $content = '';
  if (@$_POST['submit_add_comment']) {
    $post_id = strip_tags($_POST['post_id']);
    $name = strip_tags($_POST['name']);
    $content = strip_tags($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (!$name) $errors['name'] .= '名前がありません。<br>';
    if (!$content) $errors['comment'] .= 'コメントがありません。<br>';
    if (!$errors) {
      require_once "db.php";
      $dbh = db_connect();

      try {
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $dbh->prepare("INSERT INTO comment (post_id, user_id, name, content) VALUES (:post_id, :user_id, :name, :content)");
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->execute();

        $dbh = null;

        header('Location: design.php?post_id='.$post_id);
        exit();
      }
      catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
      }
    }
    else {
      $post_id = strip_tags($_GET['id']);
    }
  }


  $html = new HTML;

  $html->title = "コメント投稿 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <form method="post" action="comment.php">
        <div class="post">
          <h2>コメント投稿</h2>
          <p>お名前</p>
          <p><input type="text" name="name" size="40" value="<?php echo $name = isset($name) ? $name : NULL; ?>"</p>
          <p>本文</p>
          <p><textarea name="content" rows="8" cols="40"><?php echo $content = isset($content) ? $content : NULL; ?></textarea></p>
          <p>
            <input type="hidden" name="post_id" value="<?php $post_id = $_GET['id']; print h($post_id);?>">
            <input name="submit_add_comment" type="submit" value="投稿">
          </p>
          <?php
          if (isset($errors)) {
            foreach($errors as $value) {
              echo "<p>".$value."</p>";
            }
          }
          ?>
        </div>
      </form>
    </div>
  </div>
  <?php
  $wrapper = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $wrapper . $footer;

  print $htmlpage;

?>
