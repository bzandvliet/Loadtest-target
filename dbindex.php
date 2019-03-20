<html>
<h1>Loadtesting Database...</h1>

<body>
<?php
/**
 * Created by PhpStorm.
 * User: zanbas
 * Date: 20-3-2019
 * Time: 13:32
 */


$db = "mysql:host=10.34.4.35;dbname=Loadtest;port=3306";
$user = "root";
$pass = "HEKlrc83142";
$pdo = new PDO($db, $user, $pass);

$sStart = microtime(true);

function random_str(){

    $result="";
    for ($i = 1; $i <= 16; $i++) {
        $base10Rand = mt_rand(0, 15);
        $newRand = base_convert($base10Rand, 10, 36);
        $result.=$newRand;
    }
    return $result;

}

echo number_format(microtime(true) - $sStart, 2) ."s connect done\n";flush();
//mysqli_select_db ("mysqli_connect()", "Loadtest") || die (print 'no select_db');
$tbl_number=rand(1000, 9999);

$query = "CREATE TABLE IF NOT EXISTS test_tbl_$tbl_number (test1 VARCHAR(255) NOT NULL, test2 VARCHAR(255) NOT NULL, test3 VARCHAR(255) NOT NULL, test4 VARCHAR(255) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
$stmt = $pdo->prepare($query);
$stmt->execute() || die (print 'no query');
echo number_format(microtime(true) - $sStart, 2) ."s CREATE done\n";flush();
$aa=array();
for ($i=1; $i <= 1000; $i++) {
    $a=random_str();
    $aa[]=$a;
    $b=random_str();
    $c=random_str();
    $d=random_str();
    $query2="INSERT INTO test_tbl_$tbl_number (test1, test2, test3, test4) VALUES ('$a','$b','$c','$d')";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute()|| die (print 'no insert');
}
echo number_format(microtime(true) - $sStart, 2) ."s INSERT (1.000) 
done\n";flush();

$query="SELECT SQL_NO_CACHE * FROM test_tbl_$tbl_number";
$stmt = $pdo->prepare($query);
$stmt->execute() || die (print 'no select_db');
echo number_format(microtime(true) - $sStart, 2) ."s SELECT (1) done\n";flush();

foreach ($aa as $value) {
    $query="DELETE FROM test_tbl_$tbl_number WHERE test1='$value'";
    $stmt = $pdo->prepare($query);
    $stmt->execute()|| die (print 'no delete');
}
echo number_format(microtime(true) - $sStart, 2) ."s DELETE (1.000) 
done\n";flush();

$query="DROP TABLE test_tbl_$tbl_number";
$stmt = $pdo->prepare($query);
$stmt->execute() || die (print 'no drop');
echo number_format(microtime(true) - $sStart, 2) ."s DROP (1.000) done\n";flush();

?>
</body>
</html>
