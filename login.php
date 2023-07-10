//ログインする画面

<?php

error_reporting(0);

require('./dbconnect.php');

session_start();

$error = array();

if (!empty($_POST)) {
    //ログインの処理
    if ($_POST['email'] != '' && $_POST['password'] != '') {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(
            array(
                $_POST['email'],
                sha1($_POST['password'])
            )
        );

        //データベースクエリの結果から1行のデータを取得する
        $member = $login->fetch();

        if ($member) {
            //ログイン成功
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();
            header('Location: index.php');
        } else {
            //ログインできなかったら
            //errorのloginにfailedが入る
            $error['login'] = 'failed';
        }
    } else {
        $error['login'] = 'blank';
    }
}
?>

<html>

<head>
    <style>
        #lead{
            text-align: center;
        }

        .login{
            text-align: center;
        }


    </style>
</head>

<body>



    <div id="lead">
        <p>メールアドレスとパスワードを記入してログインしてください</p>
        <p>入会手続きがまだの方はこちら</p>
        <p>&raquo; <a href="join/">入会手続きをする</a></p>
    </div>
    <form action="" method="post" class="login">
        <dl>
            <dt>メールドレス</dt>
            <dd>
                <input type="text" name="email" size="35" maxlength="225"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>" />

                <?php if ($error['login'] == 'blank'): ?>
                    <p class="error">*メールアドレスとパスワードを記入してください</p>
                <?php endif; ?>
                <?php if ($error['login'] == 'failed'): ?>
                    <p class="error">*ログインに失敗しました。正しく入力して下さい</p>
                <?php endif; ?>

            </dd>

            <dt>パスワード</dt>
            <dd>
                <input type="password" name="password" size="35" maxlength="225"
                    value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>" />
            </dd>

            <dt>ログイン情報の記録</dt>
            <dd><input id="save" type="checkbox" name="save" value='on'><label for="save">次回から自動でログインする</label></dd>
        </dl>
        <div><input type="submit" value="ログインする"></div>
    </form>

</body>

</html>