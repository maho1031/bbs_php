<?php
session_start();
require('library.php');

// セッションの中に情報が保存されていなければ、ログイン画面に戻る
if(isset($_SESSION['id']) && isset($_SESSION['name'])) {
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
} else {
    header('Location: login.php');
    exit();
}

// URLパラメータのチェック
$post_id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT);
if (!$post_id) {
    header('Location: index.php');
    exit();
}
// DB接続
$db = dbconnect();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ひとこと掲示板</title>

    <link rel="stylesheet" href="style.css"/>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>ひとこと掲示板</h1>
    </div>
    <div id="content">
        <p>&laquo;<a href="index.php">一覧にもどる</a></p>
            <?php
        $stmt = $db->prepare('select p.id, p.member_id, p.message, p.created_at, m.name, m.image from posts p, members m where p.id=? and m.id = p.member_id order by id desc');
        if(!$stmt) {
            die($db->error);
        }
        $stmt->bind_param('i' , $post_id);
        $success = $stmt->execute();
        if(!$success) {
            die($db->error);
        }
        $stmt->bind_result($id, $member_id, $message, $created_at, $name, $image);
        if ($stmt->fetch()):
        ?>
        <div class="msg">
            <?php if($image): ?>
            <img src="member_picture/<?php echo sanitize($image); ?>" width="48" height="48" alt=""/>
            <?php endif; ?>
            <p><span class="name"><?php echo sanitize($message); ?>(<?php echo sanitize($name); ?>)</span></p>
            <p class="day"><a href="view.php?id="><?php echo sanitize($created_at); ?></a>
            <?php if($_SESSION['id'] === $member_id): ?>
                [<a href="delete.php?id=<?php echo sanitize($p_id); ?>" style="color: #F33;">削除</a>]
            <?php endif; ?>
            </p>
        </div>
        <?php else: ?>
            <p>その投稿は削除されたか、URLが間違えています</p>
        <?php endif; ?>
        </div>
    </div>
</div>
</body>

</html>