<?php
include 'funzioni.php';
$db = database_connect();

$username = $_POST["admin-username"];
$password = hash("sha512", secure($_POST["admin-password"]));

$result = $db->query("CREATE TABLE `aule` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nomeAula` varchar(32) NOT NULL,
      `maxStudenti` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("1 ".$db->error);

$result = $db->query("CREATE TABLE `corsi` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `titolo` varchar(256) NOT NULL,
      `descrizione` text NOT NULL,
      `tipo` int(11) NOT NULL,
      `continuita` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("2 ".$db->error);

$result = $db->query("CREATE TABLE `corsi_classi` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `idCorso` int(11) NOT NULL,
      `classe` int(11) NOT NULL,
      PRIMARY KEY (`id`, `idCorso`)
)") or die("3 ".$db->error);

$result = $db->query("CREATE TABLE `corsi_docenti` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `idCorso` int(11) NOT NULL,
      `idDocente` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("4 ".$db->error);

$result = $db->query("CREATE TABLE `corsi_incompatibili` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `idCorso1` int(11) NOT NULL,
      `idCorso2` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("5 ".$db->error);

$result = $db->query("CREATE TABLE `corsi_obbligatori` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `idClasse` int(11) NOT NULL,
      `idCorso` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("6 ".$db->error);

$result = $db->query("CREATE TABLE `iscrizioni` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `idUtente` int(11) NOT NULL,
      `idCorso` int(11) NOT NULL,
      `idLezione` int(11) NOT NULL,
      `partecipa` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("7 ".$db->error);

$result = $db->query("CREATE TABLE `lezioni` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `idCorso` int(11) NOT NULL,
      `idAula` int(11) NOT NULL,
      `ora` int(11) NOT NULL,
      `titolo` text NOT NULL,
PRIMARY KEY (`id`,`idCorso`)
)") or die("8 ".$db->error);

$result = $db->query(" CREATE TABLE `sessioni` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(11) NOT NULL,
      `userid` varchar(256) NOT NULL,
      `time` int(11) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("9 ".$db->error);

$result = $db->query("CREATE TABLE `sezioni` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `classe` int(11) NOT NULL,
      `sezione` varchar(5) NOT NULL,
      PRIMARY KEY (`id`)
)") or die("10 ".$db->error);

$result = $db->query("CREATE TABLE `utenti` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(32) NOT NULL,
      `cognome` varchar(32) NOT NULL,
      `username` varchar(32) NOT NULL,
      `password` varchar(256) NOT NULL,
      `classe` int(11) NOT NULL,
      `level` int(11) NOT NULL,
      `idClasse` int(5) NOT NULL,
      `email` varchar(32) NOT NULL,
      `primoAccesso` int(11) NOT NULL DEFAULT '1',
      `passwordOriginale` int(11) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`,`username`)
)") or die("11 ".$db->error);

$result = $db->query("INSERT INTO utenti (nome, cognome, username, password, classe, level,
                                          idClasse, email, primoAccesso, passwordOriginale)
                                  VALUES ('$username', '$username', '$username', '$password', '6', '2',
                                          '0','','0','0')
    ") or die($db->error);
echo "SUCCESSSSSSSSO";
?>
