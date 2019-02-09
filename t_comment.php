<?php
  session_start();

  require_once "class_html.php";
  require_once "class_main.php";

  header("Content-type: text/html; charset=utf-8");

  login_check($_SESSION['account']);


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
          <p><input type="text" name="name" size="40" value="<?php echo $name = (isset($name)) ? '$name' : ''; ?>"</p>
          <p>本文</p>
          <p><textarea name="content" rows="8" cols="40"><?php echo $content = (isset($content)) ? '$content' : ''; ?></textarea></p>
          <p>
            <input type="hidden" name="post_id" value="<?php $post_id = $_GET['id']; print h($post_id);?>">
            <input name="submit_add_comment" type="submit" value="投稿">
          </p>
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
