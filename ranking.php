<?php

//セッション準備
session_start();

//データベースとの接続
require('./dbconnect.php');



// ランキングを取得するクエリ
$rank = 'SELECT DISTINCT  members.name, max(profile.point) as point, (SELECT COUNT(*) + 1
FROM profile p2 WHERE p2.point > point) AS ranking 
FROM members INNER JOIN profile ON members.id = profile.member_id
GROUP BY members.id
ORDER BY point DESC';
$ranking = $db->query($rank);

// //nameの取得
// $name = $db->prepare('SELECT DISTINCT members.name FROM members, profile WHERE members.id=profile.member_id ');
// $member_name = $name->query($name);

?>


<html>

<head>
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 80%;
        }

        table tr {
            border-bottom: solid 1px #eee;
            cursor: pointer;
        }

        table tr:hover {
            background-color: gainsboro;
        }

        table th,
        table td {
            text-align: center;
            width: 25%;
            padding: 15px 0;
        }




        .table {
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <h1>ランキング順位</h1>
    <div><a href="index.php">戻る</a></div>
    <main>
        <div>
            <table class="table">
                <caption>ランキング</caption>
                <tr>
                    <th>順位</th>
                    <th>名前</th>
                    <th>ポイント</th>
                </tr>

                <?php
                $rank = 1;
                while ($row = $ranking->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <tr>
                        <td>
                            <?php echo $rank; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>

                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['point'], ENT_QUOTES); ?>
                        </td>
                    </tr>
                    <?php
                    $rank++;
                endwhile;
                ?>

            </table>
        </div>

    </main>

</body>

</html>