<?php
session_start();

header("Content-type: text/html; charset=utf-8");

if (!isset($_SESSION["account"])) {
  header("Location: login_form.php");
  exit();
}

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
      require_once("db.php");
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
    require 't_comment.php';
  }

?>
