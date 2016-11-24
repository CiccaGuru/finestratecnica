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

		$username = secure(stripslashes($username));
		$password = secure(stripslashes($password));

		$username = $db->escape_string($username);
		$password = hash("sha512", $db->escape_string($password));

		$result = $db->query("SELECT * from utenti where password='$password' AND username='$username'") or	die('ERRORE: 3' . $db->error);
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
			$result = $db->query("SELECT * from utenti where level='2' AND password='$password'") or	die('ERRORE 1: ' . $db->error);
			if($result->num_rows>0){
				$result = $db->query("SELECT * from utenti where username='$username'") or	die('ERRORE 2: ' . $db->error);
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
			}
			else{
				$error = 'Username o password non validi';
				session_start();
				$_SESSION['login_user']=-1;
				echo $error;
			}
		}
		$db->close;
	}
}

?>
