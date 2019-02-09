<?php
session_start();

require_once "class_html.php";
require_once "class_main.php";

header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);


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
          <p><input type="text" name="add_blog_title" size="40" value="<?php echo $title = (isset($title)) ? '$tatle' : ''; ?>" placeholder="ブログタイトルを入力"></p>
        </div>
        <div class="blog_content">
          <p>本文</p>
          <p><textarea name="content" rows="8" cols="40"><?php echo $content = (isset($content)) ? '$content' : ''; ?></textarea></p>
        </div>
        <div class="blog_image">
          <input type="file" name="add_blog_image">
        </div>
        <div class="blog_submit">
          <p><input name="submit_add_blog" type="submit" value="投稿"></p>
        </div>
        <?php
        if (isset($errors)) {
           foreach($erros as $value) {
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
