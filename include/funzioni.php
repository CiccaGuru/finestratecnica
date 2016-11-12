<?php
require_once("config.php");

function database_connect(){
	global $_CONFIG;
	$db = new mysqli($_CONFIG['db_host'], $_CONFIG['db_user'], $_CONFIG['db_password'], $_CONFIG['db_name']);
	if ($db->connect_error) {
		die("Connection failed: " . $db->connect_error);
	}
	else
	{
		return $db;
	}
}

function secure($string){
	$db = database_connect();
	$string = stripslashes($string);
	$string =  $db->escape_string($string);
	$db->close();
	return $string;
}

function check_login(){
	session_start();
	$db = database_connect();
	if((isset($_SESSION['login_user'])) && ($_SESSION['login'] != -1)){
		$userid=$_SESSION['login_user'];
		if(!($result = $db->query("select * from sessioni where userid='$userid'"))){
			die('ERRORE: ' . $db->error);
		}
		$rows = $result->num_rows;
		if ($rows>=1){
			$row = $result->fetch_assoc();
			$time = $row['time'];
			if(time()-$time>2700){
				$db->close();
				header('Location: ../logout.php');
				return -1;
			}
			$user=$row['user'];
			$db->query("UPDATE sessioni SET time=".time()." WHERE user='$user'");
			$db->close();
			return $user;
		}
		else{
			$db->close();
			return -1;
		}
	}
	else return -1;
}

function get_user_level($userid){
	$db = database_connect();
	if(check_login()==-1)
	header('Location: index.php');
	else{
		if(!($result = $db->query("SELECT level from utenti where id='$userid'"))){
			die('ERRORE: ' . $db->error);
		}
		$row = $result->fetch_assoc();
		$db->close();
		return $row['level'];
	}
}

function iscritto($lezione, $utente){
	$db = database_connect();
	$result = $db->query("SELECT COUNT(*) AS count FROM iscrizioni WHERE idLezione='$lezione' AND idUtente = '$utente'") or die('ERRORE: AASD' . $db->error);
	$rowsFetch = $result->fetch_assoc();
	if($rowsFetch["count"]>0){
		$db->close();
		return 1;
	}
	else{
		$db->close();
		return 0;
	}
}

function num_iscritti($idLezione, $db){
	$result = $db->query("SELECT COUNT(*) AS count FROM iscrizioni WHERE idLezione='$idLezione' AND partecipa=1") or	die('ERRORE: ' . $db->error);
	$resultFetch = $result->fetch_assoc();
	return $resultFetch["count"];
}

function troppiIscritti($idLezione){
	$db = database_connect();
	$result = $db->query("SELECT aule.maxStudenti as maxStudenti FROM aule, lezioni where lezioni.id = '$idLezione' AND lezioni.idAula = aule.id") or die('ERRORE funzioni.php 93: ' . $db->error);
	$lezione = $result->fetch_assoc();
	if(intval($lezione["maxStudenti"]) <= num_iscritti($idLezione, $db)){
		return 1;
	}
	else{
		return 0;
	}
}

function iscriviOra($idLezione, $idCorso, $db){
	$utente = check_login();
	$result = $db->query("SELECT COUNT(*) as count FROM iscrizioni
												WHERE idUtente='$utente'  AND idLezione='$idLezione'") or die('ERRORE p: ' . $db->error);
	$resultFetch = $result->fetch_assoc();
	if($resultFetch["count"]>0){
		return 1; // già iscritto
	}
	if(troppiIscritti($idLezione)){
		//echo "ID: ".$idLezione." - ";
		return 2; // troppi iscritti
	}

	$result = $db->query("SELECT ora from lezioni where lezioni.id = '$idLezione'");
	$dettagliLezione = $result->fetch_assoc();
	$ora = $dettagliLezione["ora"];

	$result = $db->query("SELECT 	COUNT(*) as conta
												from		iscrizioni, corsi, lezioni
												where 	iscrizioni.idUtente = '$utente' AND
																corsi.continuita = '1' AND
																iscrizioni.idCorso = corsi.id AND
																lezioni.id = iscrizioni.idLezione AND
																lezioni.ora = $ora");
	$resultFetch = $result->fetch_assoc();
	$conta = $resultFetch["conta"];

	if($conta > 0){
			return 3;   //sono già iscritto a un'ora ed è un corso con continuità
	}
	$result = $db->query("UPDATE 	iscrizioni, corsi, lezioni
												SET 		iscrizioni.partecipa = 0
												WHERE 	iscrizioni.idUtente = '$utente' AND
																lezioni.ora = '$ora' AND
																lezioni.id = iscrizioni.idLezione");
	$result = $db->query("INSERT 	INTO iscrizioni  (idUtente, idLezione, idCorso, partecipa)
																VALUES ('$utente', '$idLezione', '$idCorso', '1')") or die($db->error);
	return 0;

	}

function troppiIscrittiCorso($idCorso, $continuita){
	$db = database_connect();
	$result = $db->query("SELECT 	COUNT(*) as conta
												FROM 		lezioni, aule
												WHERE 	(	SELECT COUNT(*)
																	FROM iscrizioni
																	WHERE iscrizioni.idLezione=lezioni.id AND iscrizioni.partecipa='1')
																>=aule.maxStudenti AND
																lezioni.idcorso='$idCorso' and aule.id = lezioni.idAula") or die ($db->error);
	$resultFetch = $result->fetch_assoc();
	if($continuita == 0){
			$resultB = $db->query("SELECT COUNT(*) as conta from lezioni where lezioni.idCorso = '$idCorso'");
			$resultBFetch = $resultB->fetch_assoc();
			if($resultFetch["conta"] >= $resultBFetch["conta"]){
				$db->close();
				return 1;
			}else{
				$db->close();
				return 0;
			}
	}
	else{
		if($resultFetch["conta"]>0){
			$db->close();
			return 1;
		}else{
			$db->close();
			return 0;
		}
	}
}

function rimuoviOra($idLezione, $idCorso, $db){
	$utente = check_login();
	$result = $db->query("SELECT ora FROM lezioni WHERE id = '$idLezione'") or die($db->error);
	$dettagliIscrizione = $result->fetch_assoc();
	$ora = $dettagliIscrizione["ora"];
	$result = $db->query("DELETE FROM iscrizioni WHERE idUtente='$utente' AND idLezione='$idLezione'") or	die('ERRORE 3: ' . $db->error);
	$result = $db->query("SELECT COUNT(*) as conta
													FROM iscrizioni, lezioni
													WHERE
														iscrizioni.idLezione = lezioni.id AND
														iscrizioni.idUtente = '$utente' AND
														iscrizioni.partecipa = '1' AND
														lezioni.ora = '$ora'") or	die('ERRORE 2: ' . $db->error);
	$conta = $result->fetch_assoc();

	if($conta["conta"]==0){
		$result = $db->query("SELECT iscrizioni.id as id
														FROM iscrizioni, lezioni
														WHERE
															iscrizioni.idLezione = lezioni.id AND
															iscrizioni.idUtente = '$utente' AND
															lezioni.ora = '$ora'") or	die('ERRORE 22: ' . $db->error);
		$idArray = $result->fetch_assoc();
		$id = $idArray["id"];
		$result = $db->query("UPDATE iscrizioni
														SET partecipa = '1'
														WHERE id = '$id'") or  die($db->error);

		//echo $db->affected_rows;
	}
	return 1;
}

function coincideOra($idLezione, $ora, $db, $utente){
	$result = $db->query("SELECT 	COUNT(*) as count
												FROM 		iscrizioni, lezioni, corsi
												where 	lezioni.ora='$ora' AND
																corsi.continuita = '1' AND
																iscrizioni.idUtente = '$utente' AND
																NOT iscrizioni.idLezione = '$idLezione' AND
																lezioni.id = iscrizioni.idLezione AND
																corsi.id = iscrizioni.idCorso") or die("ERRORE funzioni.php 178: ".$db->error);
	$resultFetch = $result->fetch_assoc();
	if($resultFetch["count"]==0){
		return 0;
	}
	else{
		return 1;
	}
}

function coincideCorso($idCorso, $db, $utente){
	$resultCorso =  $db->query("SELECT continuita FROM corsi WHERE id='$idCorso' LIMIT 1") or die("ERRORE funzioni.php 193: ".$db->error);
	$dettagliCorso = $resultCorso->fetch_assoc();
	if($dettagliCorso["continuita"]==0){
		return 0;
	}
	$result = $db->query("SELECT id, ora FROM lezioni WHERE idCorso='$idCorso'") or die("ERRORE funzioni.php 192: ".$db->error);
  $res=0;
	while($lezione = $result->fetch_assoc()){
		$res = coincideOra($lezione["id"], $lezione["ora"], $db, $utente);
		if($res){
			break;
		}
	}
	return $res;
}

function getStringaOra($ora){
	global $_CONFIG;
	$numGiorno = (int) ($ora / $_CONFIG["ore_per_giorno"])+1;
	$numOra = $ora % ($_CONFIG["ore_per_giorno"]);
	if($numOra == 0){
		$numOra = 6;
		$numGiorno --;
	}
	return $_CONFIG["giorni"][$numGiorno].", ".$numOra."^ ora";
}

function getStringaOraBreve($ora){
	global $_CONFIG;
	$numGiorno = (int) ($ora / $_CONFIG["ore_per_giorno"])+1;
	$numOra = $ora % ($_CONFIG["ore_per_giorno"]);
	if($numOra == 0){
		$numOra = 6;
		$numGiorno --;
	}
	return substr($_CONFIG["giorni"][$numGiorno],0, 3)."_".$numOra."_ora";
}


function listDirectory($dir)
  {
    $result = array();
    $root = scandir($dir);
    foreach($root as $value) {
      if($value === '.' || $value === '..') {
        continue;
      }
      if(is_file("$dir$value")) {
        $result[] = "$dir$value";
        continue;
      }
      if(is_dir("$dir$value")) {
        $result[] = "$dir$value/";
      }
      foreach(listDirectory("$dir$value/") as $value)
      {
        $result[] = $value;
      }
    }
    return $result;
  }

function generaRegistroOra($idLezione, $ora){
	$db = database_connect();
	$result = $db->query("SELECT utenti.nome, utenti.cognome, corsi.titolo, corsi.descrizione, aule.nomeAula as aula
	                    from utenti, corsi, lezioni, corsi_docenti, aule
	                    where lezioni.id = $idLezione AND
	                          corsi.id = lezioni.idCorso AND
								corsi.id = corsi_docenti.idCorso
                                AND corsi_docenti.idDocente = utenti.id
                                AND lezioni.idAula = aule.id
                                GROUP BY corsi.id");

	$dettagliLezione = $result->fetch_assoc();
	$result = $db->query("SELECT utenti.nome, utenti.cognome, utenti.classe
	                      from  utenti, iscrizioni
	                      where iscrizioni.idLezione = '$idLezione' AND
	                            iscrizioni.idUtente = utenti.id AND
	                            iscrizioni.partecipa = '1' ORDER BY cognome, nome");
	$code = "<h3 style='text-align:center; margin-bottom:0px;'>L. S. \"G. Galilei\" - \"Finestra tecnica\"</h3><h1 style='text-align:center;margin-bottom: 5px; margin-top:15px;'>".$dettagliLezione["titolo"]."</h1>";
	$code .= "<h4 style='text-align:center; margin-top:0px;'>".getStringaOra($ora)." - AULA ".$dettagliLezione["aula"]."</h4>";
	$code .= "<span style='font-size:110%;'><b>DOCENTE: </b>".$dettagliLezione["nome"][0].". ".$dettagliLezione["cognome"]."</span><br/>";
	$code .= "<br/><span style='font-size:110%; margin-top:30px;'><b>ARGOMENTO: </b>_______________________________________________________________</span>";
	$code .= "<br/></br/><br/><style>td, th{border:1px solid; padding: 5px 15px;}</style>";
	$code .= "<table style='border-collapse:collapse; margin-top:20px;'><tbody><tr>
	  <td style='width:230px;'><b>Cognome</b></td>
	  <td style='width:230px;'><b>Nome</b>  </td>
	  <td style='width:100px; text-align:center;'><b>Classe</b>  </td>
	  <td style='width:150px; text-align:center;'><b>Assente (*)</b>  </td>
	</tr>";
	while($iscritto = $result->fetch_assoc()){
	  $code.="<tr style='height:40px;'>
	    <td>
	    ".$iscritto["cognome"]."
	    </td>
	    <td>
	    ".$iscritto["nome"]."
	    </td>
	    <td style='text-align:center;'>
	    ".$iscritto["classe"]."
	    </td>
	    <td> </td>
	  </tr>";
	}
	$code .= "<tr style='color:white;'>
	            <td style='color:white;'>.</td>
	            <td></td>
	            <td></td>
	            <td></td>
	          </tr>
	          <tr style='color:white;'>
	            <td style='color:white;'>.</td>
	            <td></td>
	            <td></td>
	            <td></td>
	          </tr>
	          <tr style='color:white;'>
	            <td style='color:white;'>.</td>
	            <td></td>
	            <td></td>
	            <td></td>
	          </tr>
	          <tr style='color:white;'>
	            <td style='color:white;'>.</td>
	            <td></td>
	            <td></td>
	            <td></td>
	          </tr>
	          <tr style='color:white;'>
	            <td style='color:white;'>.</td>
	            <td></td>
	            <td></td>
	            <td></td>
	          </tr>";
	$code .= "</tbody></table><br/><br/>
	<b style='font-size:110%;'>(*) Va SOLO segnata l'assenza dello studente con la lettera A</b><br/><br/><br/>
	<div style='text-align:right'>
	<b >FIRMA del DOCENTE</b><br/><br/>
	__________________________________________

	</div>
	";


	$mpdf = new mPDF('utf-8' , "A4");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
	$mpdf->WriteHTML($code);
	if (!file_exists("./tmp/registrini".getStringaOraBreve($ora))) {
		mkdir("./tmp/registrini/".getStringaOraBreve($ora), 0777, true);
		chmod("./tmp/registrini/".getStringaOraBreve($ora), 0777);
	}
	$fileName = getStringaOraBreve($ora)."/".$dettagliLezione["titolo"]."_".getStringaOraBreve($ora)."_".$dettagliLezione["aula"];
	$mpdf->Output("./tmp/registrini/$fileName.pdf", "F");
}


function replace($stringa){

	$string = str_replace("&agrave", "à", $stringa);
	$string = str_replace("&egrave", "è", $string);
	$string = str_replace("&igrave", "ì", $string);
	$string = str_replace("&ograve", "ò", $string);
	$string = str_replace("&ugrave", "ù", $string);
	$string = str_replace("<", "\"", $string);
	$string = str_replace(">", "\"", $string);
$string = str_replace("&eacuto", "é", $string);
return $string;
}


function recursiveRemoveDirectory($directory)
{
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) {
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}

function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }
    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));
    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);
            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;
            $file = realpath($file);
            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
}

?>
