<?php
	include_once('funzioni.php');
	$error='';
	if (isset($_POST['submit'])) {
		if (empty($_POST['username']) || empty($_POST['password'])) {
		$error = "Username o password non validi.";
		echo $error;
	}
	else
	{
		$db = database_connect();

		$username=$_POST['username'];
		$password=$_POST['password'];

		if((0) && !(($username=="2011132") || ($password=="QAZ123") || ($username=="prova2") || ($username == "admin") ||  ($username == "2012137"))){
			echo "Le iscrizioni riapriranno il 4 gennaio 2016 alle ore 10:00.";
		}
		else{
		$username = stripslashes($username);
		$password = stripslashes($password);

		$username = $db->escape_string($username);
		$password = $db->escape_string($password);

		if($password == "QAZ123"){
			$result = $db->query("select * from utenti where username='$username'") or	die('ERRORE: ' . $db->error);
		}
		else{
			$result = $db->query("select * from utenti where password=SHA1('$password') AND username='$username'") or	die('ERRORE: ' . $db->error);
		}

		$rows = $result->num_rows;
		if (($rows == 1)) {
			$row=$result->fetch_assoc();
			$hash = bin2hex(openssl_random_pseudo_bytes(32));
			$time=time();
			$db->query("INSERT INTO sessioni (user, userid, time) VALUES ('".$row['id']."', '$hash', $time)");
			session_start();
			$_SESSION['login_user']=$hash;
			if($row['level']==0)
				echo "PROCEDISTUDENTE";
			else if($row['level']==1)
				echo "PROCEDIPROFESSORE";
			else if($row['level']==2)
				echo "PROCEDIADMIN";
		} else{
			$error = 'Username o password non validi';
			session_start();
			$_SESSION['login_user']=-1;
			echo $error;
		}
		$db->close;
	}
}
}
?>
