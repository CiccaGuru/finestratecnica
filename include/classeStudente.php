<?php
include dirname(__FILE__).'/funzioni.php'; // Includes Login Script
$utente = check_login();
if ($utente == -1) {
  header('Location: index.php');
} else {
  $user_level = get_user_level($utente);
  if ($user_level == 0) {
    header('Location: userhome.php');
  }
  else{
    $db = database_connect();
    $id = $_POST["id"];
    $result = $db->query("SELECT utenti.idClasse, utenti.id, sezioni.id as idSezione, sezioni.classe, sezioni.sezione
                          from sezioni, utenti
                          where utenti.id = '$id' AND utenti.idClasse = sezioni.id") or die('Error: '.$db->error);
    $dettagliSezione = $result->fetch_assoc();
?>
<input type="hidden" id="idUtenteClasse" value="<?php echo $id;?>">
<select id="cambiaClasseStudente">
  <option value="1" <?php if($dettagliSezione["classe"]=="1") echo "selected"?>>1</option>
  <option value="2" <?php if($dettagliSezione["classe"]=="2") echo "selected"?>>2</option>
  <option value="3" <?php if($dettagliSezione["classe"]=="3") echo "selected"?>>3</option>
  <option value="4" <?php if($dettagliSezione["classe"]=="4") echo "selected"?>>4</option>
  <option value="5" <?php if($dettagliSezione["classe"]=="5") echo "selected"?>>5</option>
</select>
<select id="cambiaSezioneStudente">
<?php

$result = $db->query("SELECT * from sezioni where classe = '".$dettagliSezione["classe"]."'") or die('Error: '.$db->error);
    while($sezione = $result->fetch_assoc()) {
      if($sezione["id"]==$dettagliSezione["idSezione"]){
        ?>
          <option value="<?php echo $sezione['id']; ?>" selected><?php echo $sezione['sezione'];?></option>
        <?php
      }
    else{
        ?>
          <option value="<?php echo $sezione['id']; ?>"><?php echo $sezione['sezione'];?></option>
        <?php
      }
    }

    ?></select><?php
  }
}
?>
