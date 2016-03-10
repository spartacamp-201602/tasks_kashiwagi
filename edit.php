<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDb();

$sql = 'select * from tasks where id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

$post = $stmt->fetch(PDO::FETCH_ASSOC);

//var_dump($post);
// タイトルだけ取りたい場合
// $post['title']

// 編集ボタンが押されたとき
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $title = $_POST['title'];

    // バリデーション
    $errors = array(); // エラーの情報を複数持たせることが可能

    if ($title == "")
    {
        $errors['title'] = 'タスク名を入力してください。';
    }

    if ($title == $post['title'])
    {
        $errors['title'] = 'タスク名が変更されていません。';
    }

    if (empty($errors))
    {
        $sql = 'update tasks set title = :title, updated_at = now() ';
        $sql.= 'where id = :id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>編集画面 | タスク管理アプリ</title>
</head>
<body>
<h2>タスクの編集</h2>
<p>
    <form action="" method="post">
        <input type="text" name="title" value="<?php echo h($post['title']) ?>">
        <input type="submit" value="編集">
        <p>
            <span style="color:red;">
            <?php echo h($errors['title']) ?>
            </span>
        </p>
    </form>
</p>
</body>
</html>