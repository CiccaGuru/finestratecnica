<?php
include dirname(__FILE__).'/funzioni.php';
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

$result = $db->query("CREATE TABLE `impostazioni` (
                        `id` int(11) NOT NULL,
                        `prop` varchar(64) NOT NULL,
                        `value` varchar(512) NOT NULL
                      )");

$result = $db->query("INSERT INTO utenti (nome, cognome, username, password, classe, level,
                                          idClasse, email, primoAccesso, passwordOriginale)
                                  VALUES ('$username', '$username', '$username', '$password', '6', '2',
                                          '0','','0','0')
    ") or die($db->error);

$result = $db->query("INSERT INTO `impostazioni` (`id`, `prop`, `value`) VALUES
(1, 'primaryColor', '#2196F3'),
(2, 'primaryColorDark', '#1E88E5'),
(3, 'primaryColorDarkest', '#1976D2'),
(4, 'primaryColorLight', '#64B5F6'),
(5, 'accentColor', '#F44336'),
(6, 'accentColorLight', '#EF5350'),
(7, 'primaryText', '#fafafa'),
(8, 'primaryDarkText', '#fafafa'),
(9, 'primaryLightText', '#263238'),
(10, 'accentText', '#fafafa'),
(11, 'numero_giorni', '4'),
(12, 'ore_per_giorno', '6'),
(13, 'soglia_minima', '17'),
(14, 'chiusura_iscrizioni', '111111111111'),
(15, 'apertura_iscrizioni', '0000000000000000'),
(16, 'colori', 'a:17:{i:0;s:7:\"#03a9f4\";i:1;s:7:\"#F44336\";i:2;s:7:\"#FFEB3B\";i:3;s:7:\"#0d47a1\";i:4;s:7:\"#4CAF50\";i:5;s:7:\"#FF5722\";i:6;s:7:\"#9C27B0\";i:7;s:7:\"#311b92\";i:8;s:7:\"#212121\";i:9;s:7:\"#795548\";i:10;s:7:\"#607D8B\";i:11;s:7:\"#E91E63\";i:12;s:7:\"#00695c\";i:13;s:7:\"#01579b\";i:14;s:7:\"#1b5e20\";i:15;s:7:\"#aeea00\";i:16;s:7:\"#FFFFFF\";}'),
(17, 'colore_testo', 'a:20:{i:0;s:5:\"black\";i:1;s:5:\"white\";i:2;s:5:\"black\";i:3;s:5:\"white\";i:4;s:5:\"black\";i:5;s:5:\"white\";i:6;s:5:\"white\";i:7;s:5:\"white\";i:8;s:5:\"white\";i:9;s:5:\"white\";i:10;s:5:\"black\";i:11;s:5:\"black\";i:12;s:5:\"white\";i:13;s:5:\"white\";i:14;s:5:\"white\";i:15;s:5:\"white\";i:16;s:5:\"white\";i:17;s:5:\"black\";i:18;s:5:\"white\";i:19;s:5:\"white\";}'),
(18, 'giorni', 'a:4:{i:1;s:10:\"Mercoledì\";i:2;s:8:\"Giovedì\";i:3;s:8:\"Venerdì\";i:4;s:6:\"Sabato\";}')
") or die("ERROR".$db->error);

echo "SUCCESS";
?>
