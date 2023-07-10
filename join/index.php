//会員登録する画面

<?php

error_reporting(0);

//セッションを開始するための関数
session_start();

require('../dbconnect.php');

//$_POST は method='POST'で格納された変数
if(!empty($_POST)){
    //エラーの項目の確認

    if($_POST['name'] == ''){
        $error['name'] = "blank";
    }

    if($_POST['email'] == ''){
        $error['email'] = "blank";
    }

    if(strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }   

    if($_POST['password'] == ''){
        $error['password'] = "blank";
    }

    $fileName = $_FILES['image']['name'];
    if(!empty($fileName)){
        $ext =substr($fileName , -3);
        if($ext != 'jpg' && $ext != 'gif'){
            $error['image'] = 'type';
        }
    }

    //アカウントの重複をチェックする
    if(empty($error)){
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if($record['cnt'] > 0){
            $error['email'] = 'duplicate';
        }
    }

    //error変数に何も入っていなかった時の処理
    if(empty($error)){

        //画像をアップロードする
        $image =date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'] , '../member_picture/' . $image);

        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;

        //header('Location:○○')
        header('Location: check.php');
        exit();
    }

}
?>


   <html> 
    <head>
        <style>
            p{
                text-align: center;
            }
            form{
                
                text-align: center;
            }

            
        </style>
    </head>
    <body>
        
    
       <p>次のフォームに必要事項を記入ください</p>
    <form action="" method="post" enctype="multipart/form-data">
        <dl>
            <!-- ニックネーム -->
            <dt>ニックネーム <span class="required">必須</span></dt>
            <dd><input type="text" name="name" size="35" maxlength="225" >
            
            <?php if($error['name'] == 'blank'): ?>
            <p class="error">* ニックネームを入力してください</p>
            <?php endif ; ?>
            </dd>

            <!-- メールアドレス -->
            <dt>メールドレス <span class="required">必須 </span></dt>
            <dd><input type="text" name="email" size="35" maxlength="225">
            <?php if($error['email'] == 'blank'): ?>
            <p class="error">* メールアドレスを入力してください</p>
            <?php endif ; ?>
            <?php if($error['email'] == 'duplicate'): ?>
                <p class="error">*指定したメールアドレスは既に登録されています</p>
            <?php endif; ?>    
            </dd>

            <!-- パスワード -->
            <dt>パスワード <span class="required">必須</span></dt>
            <dd><input type="password" name="password" size="10" maxlength="20">
            <?php if($error['password'] == 'blank'): ?>
            <p class="error">* パスワードを入力してください</p>
            <?php endif ; ?>
            <?php if($error['password'] == 'length'): ?>
            <p class="error">* パスワードは4文字以上で入力した下さい</p>
            <?php endif; ?>
        
            </dd>
            <dt>写真など</dt>
            <dd><input type="file" name="image" size="35">
            <?php  if($error['image'] == 'type'): ?>
                <p class="error">*写真などはgif jpgの画像指定してください</p>
            <?php endif; ?>
            <?php if(!empty($error)): ?>
                <p class="error">*恐れ入りますが画像を改めて選んでください</p>
            <?php endif;?>    
            </dd>
        </dl>
        <div><input type="submit" value="入力を確認する" id="button"></div>

    </form>

    </body>
    </html>


