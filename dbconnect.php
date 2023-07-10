//データベースとの接続

<?php 
try{

    //データベースとの接続(mini_bbs)
    $db = new PDO('mysql:dbname=mini_bbs;host=127.0.0.1;charset = utf8','root','');

}catch(PDOException $e){
    echo 'db接続エラー:' , $e -> getMessage();
} 
?>