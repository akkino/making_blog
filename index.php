<?php

  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);


  require_once "db.php";
  $dbh = db_connect();

  $errors = $blogs = array();
  $post_id = 0;

  if (count($errors) === 0) {
    //記事の削除
    if (isset($_POST['submit_blog_delete'])) {
      try {
        $post_id = $_POST['post_id'];

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $dbh->prepare("DELETE FROM post WHERE id=(:post_id)");
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $statement->execute();

        $statement = null;
      }
      catch (PDOException $e) {
        print('Error:' . $e->getMessage());
        die();
      }
    }

    //全ての記事の取得
    try {
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $statement = $dbh->prepare("SELECT * FROM post ORDER BY created_at DESC");
      $statement->execute();

      $blogs = $statement->fetchALL();

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
  <div id="cover">
    <h1 id="cover_title">自分のブログを作ってみよう！</h1>

  </div>
  <div class="wrapper">
    <div id="main">
      <div id="blog_list" class="clearfix">
<?php     foreach ($blogs as $blog_item) { ?>
          <div class="blog_item">
            <div class="blog_image">
              <?php
              if(isset($_SESSION['blog_image'])) {
                $thumbnail = $_SESSION['blog_image'];
              } else {
                $thumbnail = 'uploads/Noimage.png';
              }
              ?>
              <img src="<?php print h($thumbnail); ?>" alt="">
            </div>
            <div class="blog_detail">
              <div class="blog_title">
                <form method="get" name="go_design" action="./design.php">
                  <input type="hidden" name="psot_id" value="<?php print h($blog_item['id']); ?>">
                  <a href="./design.php?post_id=<?php print h($blog_item['id']); ?>"><?php print h($blog_item['title']); ?></a>
                </form>
                <div class="blog_created_at">
                  <?php print h($blog_item['created_at']); ?>
                </div>
              </div>
            </div>
          </div>
      <?php } ?>
      </div>
    </div>
  </div>
  <?php
  $main = ob_get_contents();
  ob_end_clean();

  $htmlpage = $head . $header . $main . $footer;

  print $htmlpage;

 ?>
