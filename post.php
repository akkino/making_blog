<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);


  $errors = array();
  $title = $content = $user_id = $image_path = '';
  if (@$_POST['submit_add_blog']) {
    $title = $_POST['add_blog_title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    if (!$title) $errors['title'] .= 'タイトルがありません。<br>';
    if (mb_strlen($title) > 80) $errors['title_langht'] .= 'タイトルが長すぎます。<br>';
    if (!$content) $errors['title_none'] .= '本文がありません。<br>';
    if (!$errors) {

      //画像データの登録
      if ($_FILES['add_blog_image']) {
        $file_name = $_FILES['add_blog_image']['name'];
        $image_path = './uploads/' . $file_name;
        move_uploaded_file($_FILES['add_blog_image']['tmp_name'], $image_path);
      }

      //ブログを新規登録する
      require_once "db.php";
      $dbh = db_connect();

      try {
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $dbh->prepare("INSERT INTO post (user_id, title, content, blog_image) VALUES (:user_id, :title, :content, :blog_image)");
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':content', $content, PDO::PARAM_STR);
        $statement->bindValue(':blog_image', $image_path, PDO::PARAM_STR);
        $statement->execute();

        $dbh = null;

        header('Location: index.php');
        exit();
      }
      catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
      }
    }
  }
  

  $html = new HTML;

  $html->title = "記事投稿 | 自分のブログを作ってみよう！";

  $head = $html->HtmlHead();
  $header = $html->HtmlHeader();
  $footer = $html->Htmlfooter();

  ob_start();
  ?>
  <div class="wrapper">
    <div id="main">
      <h2>記事投稿</h2>
      <form method="post" action="post.php" class="form_blog" enctype="multipart/form-data">
        <div class="blog_title">
          <p><input type="text" name="add_blog_title" size="40" value="<?php echo $title = isset($title) ? $title : NULL; ?>" placeholder="ブログタイトルを入力"></p>
        </div>
        <div class="blog_content">
          <p>本文</p>
          <p><textarea name="content" rows="8" cols="40"><?php echo $content = isset($content) ? $content : NULL; ?></textarea></p>
        </div>
        <div class="blog_image">
          <input type="file" name="add_blog_image">
        </div>
        <div class="blog_submit">
          <p><input name="submit_add_blog" type="submit" value="投稿"></p>
        </div>
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
