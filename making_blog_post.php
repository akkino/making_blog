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


  $error = $title = $content = '';
  if (@$_POST['submit']) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    if (!$title) $error .= 'タイトルがありません。<br>';
    if (mb_strlen($title) > 80) $error .= 'タイトルが長すぎます。<br>';
    if (!$content) $error .= '本文がありません。<br>';
    if (!$error) {
      $sql = 'INSERT INTO post (title, content) VALUES (?, ?)';
      $statement = mysqli_prepare($database, $sql);
      mysqli_stmt_bind_param($statement, 'sss', $title, $content);
      mysqli_stmt_execute($statement);
      mysqli_stmt_close($statement);
      header('Location: index.php');
      }
    }
    require 't_post.php';

    mysqli_close($database);
  ?>
