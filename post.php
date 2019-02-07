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


  $error = $title = $content = $image_path = '';
  if (@$_POST['submit_add_blog']) {
    $title = $_POST['add_blog_title'];
    $content = $_POST['content'];
    if (!$title) $error .= 'タイトルがありません。<br>';
    if (mb_strlen($title) > 80) $error .= 'タイトルが長すぎます。<br>';
    if (!$content) $error .= '本文がありません。<br>';
    if (!$error) {
      //画像データの登録
      if ($_FILES['add_blog_image']) {
        $file_name = $_FILES['add_blog_image']['name'];
        $image_path = './uploads/' . $file_name;
        move_uploaded_file($_FILES['add_blog_image']['tmp_name'], $image_path);
      }
      //ブログを新規登録する
      $sql = 'INSERT INTO post (title, content, blog_image) VALUES (?, ?, ?)';
      $statement = mysqli_prepare($database, $sql);
      mysqli_stmt_bind_param($statement, 'sss', $title, $content, $image_path);
      mysqli_stmt_execute($statement);
      mysqli_stmt_close($statement);
      header('Location: index.php');
      }
    }
    require 't_post.php';

    mysqli_close($database);
  ?>
