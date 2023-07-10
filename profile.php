

<?php

//セッション準備
session_start();

//データベースとの接続
require('./dbconnect.php');


if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    //ログインしている
    $_SESSION['time'] = time();

    //$memberに登録中のidの行を代入している
    $member = $db->prepare('SELECT * FROM members WHERE id=?');
    $member->execute(array($_SESSION['id']));
    $member = $member->fetch();
}



if (!empty($_POST)) {
    // pointをデータベースに入力する準備
    $pointInsert = $db->prepare('INSERT INTO profile SET point=?, member_id=?, created=NOW()');

    //既存のpointを取得する
    $existingPoint = $db->prepare('SELECT point FROM profile WHERE member_id = ? ORDER BY created DESC LIMIT 1');
    $existingPoint->execute(array($member['id']));
    $existingPoint = $existingPoint->fetch();

    //既存のpointとPOSTから取得しpointを比較
    if($_POST['point'] > $existingPoint['point']){

        //POSTから取得したpointの方が大きかったらpointをデータベースに入力
        $pointInsert->execute(array($_POST['point'], $member['id']));
    }

    
}

// プロフィールの取得
$profile = $db->prepare('SELECT * FROM profile WHERE member_id = ? ORDER BY point DESC LIMIT 1');
$profile->execute(array($member['id']));
$point = $profile->fetch();
?>

<html>

<head>
    <style>
        th , td,
        table{
            border: solid 1px green;
        }

        .form{
            text-align: center;
        }

        .table{
            margin: 0 auto;
            
        }

    </style>
</head>

<body>
    <header>
        <h2>
            <?php echo htmlspecialchars($member['name'], ENT_QUOTES) ?>さんのプロフィール
        </h2>
        <div><a href="index.php">戻る</a></div>
    </header>
    <main>
        <form action="" method="post" class="form">
            <p>あなたの点数を入力して下さい</p>
            <p><input type="text" name="point"
                    value="<?php echo isset($_POST['point']) ? htmlspecialchars($_POST['point'], ENT_QUOTES) : ''; ?>">
            </p>
            <p><input type="submit" value="結果を入力する"></p>
        </form>

        <table class="table">
            <caption>最高点数</caption>
            <th>
                <?php echo htmlspecialchars($member['name'], ENT_QUOTES) ?>
            </th>
            <td>
                <?php echo htmlspecialchars($point['point'], ENT_QUOTES) ?>
            </td>
        </table>
    </main>
</body>

</html>


