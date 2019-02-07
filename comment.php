<?php
  $host = 'localhost';
  $username = 'root';
  $password = '';
  $db_name = 'making_blog';

  $database = mysqli_connect($host, $username, $password, $db_name);

  if ($database == false) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
  }

  $charset = 'utf8';
  mysqli_set_charset($database, $charset);

  $post_id = $error = $name = $content = '';
  if (@$_POST['submit']) {
    $post_id = strip_tags($_POST['post_id']);
    $name = strip_tags($_POST['name']);
    $content = strip_tags($_POST['content']);
    if (!$name) $error .= '名前がありません。<br>';
    if (!$content) $error .= 'コメントがありません。<br>';
    if (!$error) {
      $sql = 'INSERT INTO comment (post_id, name , content) VALUES (?, ?, ?)';
      $statement = mysqli_prepare($database, $sql);
      mysqli_stmt_bind_param($statement, 'sss', $post_id, $name, $content);
      mysqli_stmt_execute($statement);
      mysqli_stmt_close($statement);
      header('Location: index.php');
      exit();
    }
  }
  else {
    $post_id = strip_tags($_GET['id']);
  }
require 't_comment.php';

mysqli_close($database);
?>
