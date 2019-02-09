<?php
class HTML {

  public $title = '';

  public function HtmlHead() {
    ob_start();
    ?>
      <!DOCTYPE html>
      <html lang="ja">
        <head>
          <meta charset="utf-8">
          <title>{ . $this->title . }</title>
          <link rel ="stylesheet" href="making_blog.css">
        </head>
    <?php
    $head = ob_get_contents();
    ob_end_clean();

    return $head;
  }

  public function HtmlHeader() {
    ob_start();
    ?>
      <header>
        <div id="header">
          <div id="logo">
            <a href="./index.php">making blog</a>
          </div>
          <nav>
            <ul>
              <li><a href="./t_post.php">記事投稿</a></li>
              <li>login user:<?php $account = $_SESSION['account']; print h($account); ?></li>
              <li><a href='./logout.php'>ログアウトする</a></li>
            </ul>
          </nav>
        </div>
      </header>
    <?php
    $header = ob_get_contents();
    ob_end_clean();

    return $header;
  }


  public function Htmlfooter() {
    ob_start();
    ?>
          <footer>
            <small>© 2019 making blog.</small>
          </footer>
        </body>
      </html>
    <?php
    $footer = ob_get_contents();
    ob_end_clean();
    return $footer;
  }

}



?>
