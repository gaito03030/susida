
<?php

error_reporting(0);

session_start();

require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();

    $member = $db->prepare('SELECT * FROM members WHERE id=?');
    $member->execute(array($_SESSION['id']));
    $member = $member->fetch();
} else {
    //ログインしてない
    header('Location: login.php');
    exit();
}

//投稿を記録する
if (!empty($_POST)) {
    if ($_POST['message'] != '') {
        $message = $db->prepare('INSERT INTO post SET member_id=?,message=?,created=NOW()');
        $message->execute(
            array(
                $member['id'],
                $_POST['message']
            )
        );

        header('Location: index.php');
        exit();
    }
}

//投稿を取得する
$post = $db->query('SELECT m.id, m.name, m.picture, p.* FROM members m, post p WHERE m.id=p.member_id ORDER BY p.created DESC');


if (isset($_POST['button'])) {
    header('Location: ranking.php');
    exit();
}

?>

<html>

<head>
    <style>
        a {
            text-decoration: none;
        }

        ul {
            padding-left: 0;
        }

        li {
            list-style: none;
        }

        .logo {
            width: 50px;
            height: 50px;
        }

        .header {
            background-color: gray;
            height: 80px;
            display: flex;
        }

        .h2 {
            margin: auto;
        }

        .content {
            display: flex;
        }

        .nav {
            background-color: #ccc;
            display: flex;
            flex: 0 0 240px;
        }

        .box {
            padding: 15px;
        }

        .nav .box+.box {
            border-top: 1px solid black;

        }



        .main {
            flex: auto;
            min-width: 10px;
        }

        .main .msg+.msg {
            border-top: 1px solid gray;
        }

        .content .main{
            text-align: center;
        }

        .main .push-msg{
            position: relative;
            left: 165px;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1><img src="./photo/sushi.png" alt="寿司" class="logo"></h1>
        <h2 class="h2">寿司打の掲示板</h2>
    </header>


    <div class="content">
        <nav class="nav">
            <ul>
                <li class="box"><a href="ranking.php">ランキングへ</a></li>
                <li class="box"><a href="profile.php">プロフィール入力へ</a></li>
                <li class="box"><a href="logout.php">ログアウト</a></li>
                <li class="box"><a href="https://sushida.net/" target="_blank">寿司打をPLAY</a></li>
            </ul>
        </nav>

        <main class="main">


            <form action="" method="post">
                <dl>

                    <dt>
                        <?php echo htmlspecialchars($member['name'], ENT_QUOTES) ?>さんメッセージどうぞ
                    </dt>
                    <dd>
                        <textarea name="message" cols="50" rows="5"></textarea>
                    </dd>
                </dl>

                <div class="push-msg">
                    <input type="submit" value="投稿する">
                </div>
            </form>

            <?php foreach ($post as $message): ?>

                <div class="msg">
                    <img src="member_picture/me.jpg" width="48"
                        alt="<?php echo htmlspecialchars($message['name'], ENT_QUOTES) ?>">
                    <p>
                        <?php echo htmlspecialchars($message['message'], ENT_QUOTES) ?>
                        (
                        <?php echo htmlspecialchars($message['name'], ENT_QUOTES) ?>)
                    </p>
                    <p class="day">
                        <?php echo htmlspecialchars($message['created'], ENT_QUOTES) ?>
                    </p>
                </div>


            <?php endforeach; ?>

    </div>
    </main>


</body>

</html>