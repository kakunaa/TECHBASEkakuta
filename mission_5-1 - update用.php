<?php

//データベースへの接続
$dsn='mysql:dbname='データベース名';host='ホスト名'';
$user='ユーザ名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
$sql="CREATE TABLE IF NOT EXISTS mission5"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."time char(32),"
."password TEXT"
.");";
$stmt=$pdo->query($sql);

//編集機能
if(isset($_POST['編集番号'])&&$_POST['編集番号']){
	$editbangou=$_POST['編集番号'];
	$sql='SELECT * FROM mission5';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){            //編集番号と同じ番号を探して名前とコメントを変数に格納
		$toukou[0]=$row['id'];
		$toukou[1]=$row['name'];
		$toukou[2]=$row['comment'];
		if($toukou[0]==$editbangou){
			$editkakunin=$toukou[0];
			$name=$toukou[1];
			$comment=$toukou[2];
		}
	}
}else{
	$editkakunin="";
	$name="";
	$comment="";
}
?>

<html>
 <form action="mission_5-1.php" method="post">
  <div>
   <label for="名前">名前</label>
   <input type="text" name="名前" size="15" value="<?php echo $name; ?>">
  </div>
  <div>
   <label for="コメント">コメント</label>
   <input type="text" name="コメント" size="100" value="<?php echo $comment; ?>">
  </div>
  <div>
   <label for="パスワード">パスワード</label>
   <input type="text" name="パスワード" size="30">
  </div>
  <input type="submit" value="送信する">
  <div>
   <input type="text" name="削除番号" size="15" placeholder="削除番号を入力">
   <input type="submit" value="削除">
  </div>
  <div>
   <input type="text" name="編集番号" size="15" placeholder="編集番号を入力">
   <input type="submit" value="編集">
   <input type="hidden" name="編集確認番号" size="15" value="<?php echo $editkakunin; ?>">
  </div>
 </form>
<html>

<?php
$nitiji=date("Y/m/d H:i:s");
//新規投稿機能か編集機能化の分岐
if(isset($_POST['名前'])&&$_POST['名前']and isset($_POST['コメント'])&&$_POST['コメント']){

//編集機能
if(isset($_POST['編集確認番号'])&&$_POST['編集確認番号']){
	if(isset($_POST['パスワード'])&&$_POST['パスワード']){
		$editbangou=$_POST['編集確認番号'];
		$password=$_POST['パスワード'];
		$sql='SELECT * FROM mission5';
		$stmt=$pdo->query($sql);
		$results=$stmt->fetchAll();
		foreach($results as $row){            //削除番号と同じ番号を探して名前とコメントを変数に格納
			$toukou[0]=$row['id'];
			$toukou[1]=$row['password'];
			if($toukou[0]==$editbangou){
				$toukoupass=$toukou[1];
			}
		}
		if(strcmp($toukoupass,$password)==0){
			$name=$_POST['名前'];
			$comment=$_POST['コメント'];
			$sql='update mission5 set name=:name,comment=:comment,time=:time where id=:id';
			$stmt=$pdo->prepare($sql);
			$stmt->bindParam(':name',$name,PDO::PARAM_STR);
			$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
			$stmt->bindParam(':time',$nitiji,PDO::PARAM_STR);
			$stmt->bindParam(':id',$editbangou,PDO::PARAM_INT);
			$stmt->execute();
		}else{
			echo "パスワードが違います"."<br>"."<br>";
		}
	}else{
		echo "パスワードを入力してください"."<br>"."<br>";
	}
//掲示板の表示
	$sql='SELECT * FROM mission5';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
		echo "<hr>";
	}
}

//ここから新規投稿機能
else{
	$sql=$pdo->prepare("INSERT INTO mission5(name,comment,time,password) VALUES (:name,:comment,:time,:password)");
	$name=$_POST['名前'];
	$comment=$_POST['コメント'];
	$password=$_POST['パスワード'];
	$sql->bindParam(':name',$name,PDO::PARAM_STR);
	$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql->bindParam(':time',$nitiji,PDO::PARAM_STR);
	$sql->bindParam(':password',$password,PDO::PARAM_STR);
	$sql->execute();
//掲示板の表示
	$sql='SELECT * FROM mission5';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
		echo "<hr>";
	}
}
}

//削除機能
elseif(isset($_POST['削除番号'])&&$_POST['削除番号']){
	if(isset($_POST['パスワード'])&&$_POST['パスワード']){
		$password=$_POST['パスワード'];
		$deletebangou=$_POST['削除番号'];
		$sql='SELECT * FROM mission5';
		$stmt=$pdo->query($sql);
		$results=$stmt->fetchAll();
		foreach($results as $row){            //削除番号と同じ番号を探して名前とコメントを変数に格納
			$toukou[0]=$row['id'];
			$toukou[1]=$row['password'];
			if($toukou[0]==$deletebangou){
				$toukoupass=$toukou[1];
			}
		}
		if(strcmp($toukoupass,$password)==0){
			$sql='delete from mission5 where id=:id';
			$stmt=$pdo->prepare($sql);
			$stmt->bindParam(':id',$deletebangou,PDO::PARAM_INT);
			$stmt->execute();
		}else{
			echo "パスワードが違います"."<br>"."<br>";
		}
	}else{
		echo "パスワードを入力してください"."<br>"."<br>";
	}
//掲示板の表示
	$sql='SELECT * FROM mission5';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
		echo "<hr>";
	}
}

//無操作時の掲示板の表示
else{
	$sql='SELECT * FROM mission5';
	$stmt=$pdo->query($sql);
	$results=$stmt->fetchAll();
	foreach($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
		echo "<hr>";
	}
}
?>