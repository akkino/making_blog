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

  //ユーザーの投稿した記事の取得
  try {
    $user_id = $_SESSION['user_id'];

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statement = $dbh->prepare("SELECT * FROM post WHERE user_id=(:user_id) ORDER BY created_at DESC");
    $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
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

$html->title = "マイページ | 自分のブログを作ってみよう！";

$head = $html->HtmlHead();
$header = $html->HtmlHeader();
$footer = $html->Htmlfooter();

ob_start();
?>
<?php if (isset($blogs)) { ?>
  <div class="wrapper">
    <div id="main">
      <div id="blog_list" class="clearfix">
    <?php foreach ($blogs as $blog_item) { ?>
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

          <?php if($blog_item['user_id'] == $_SESSION['user_id']) { ?>
            <form action="index.php" method="post">
              <input type="hidden" name="post_id" value="<?=$post_id?>">
              <div class="blog_delete">
                <input type="submit" name="submit_blog_delete" value="削除する">
              </div>
            </form>
          <?php } ?>
        </div>
    <?php }
    }
    else { ?>
      <div>
        <p>まだ記事が投稿されていません。</p>
        <p><a href="./post.php">こちら</a>から記事を投稿してみましょう</p>
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
