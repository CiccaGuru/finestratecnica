<?php
include 'funzioni.php';
$db = database_connect();
$level = $_POST["level"];
$result = $db->query("SELECT * FROM utenti WHERE level='".secure($level)."' ORDER BY cognome, nome ASC") or  die('ERRORE: ' . $db->error);

if($level == 1){
while($row = $result->fetch_assoc())
{
  ?>

  <li class="collection-item row valign-wrapper">
    <div class="input-field col s3 valign">
      <input id="nome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" required>
    </div>
    <div class="input-field col s3 valign">
      <input id="cognome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" required>
    </div>
    <div class="input-field col s2 valign">
      <input id="username<?php echo $row['id']; ?>" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" required>
    </div>
    <div class="col s2 cente valign">
      <p><a onclick='modificaDocente(<?php echo $row['id'];?>)' class="waves-effect waves-light btn red valign" style="width:98%">Modifica</a></p>
      <p><a class="waves-effect waves-light btn red valign" onclick="eliminaDocente(<?php echo $row['id'];?>)" style="width:98%">Elimina</a></p>
    </div>
    <div class="col s2 center valign">
      <a onclick="passwordReset(<?php echo $row['id'];?>)" class="waves-effect waves-light btn-large valign red">Reset</a>
    </div>
  </li>
  <?php
}
}
else{
  while($row = $result->fetch_assoc())
  {
    ?>

    <li class="collection-item row valign-wrapper">
      <div class="input-field col s2 valign">
        <input id="nome<?php echo $row['id']; ?>Studente" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" required>
      </div>
      <div class="input-field col s3 valign">
        <input id="cognome<?php echo $row['id']; ?>Studente" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" required>
      </div>
      <div class="input-field col s2 valign">
        <input id="username<?php echo $row['id']; ?>Studente" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" required>
      </div>
      <div class="input-field col s1">
        <select id="classeStudente<?php echo $row['id'];?>">
          <option value="1" <?php if($row["classe"]==1) echo "selected";?>>1</option>
          <option value="2" <?php if($row["classe"]==2) echo "selected";?>>2</option>
          <option value="3" <?php if($row["classe"]==3) echo "selected";?>>3</option>
          <option value="4" <?php if($row["classe"]==4) echo "selected";?>>4</option>
          <option value="5" <?php if($row["classe"]==5) echo "selected";?>>5</option>
        </select>
        <label>Classe</label>
      </div>
      <div class="col s2 cente valign">
        <p><a onclick='modificaStudente(<?php echo $row['id'];?>)' class="waves-effect waves-light btn red valign" style="width:98%">Modifica</a></p>
        <p><a class="waves-effect waves-light btn red valign" onclick="mostraOrario(<?php echo $row['id'];?>)" style="width:98%">Orario</a></p>
      </div>
      <div class="col s2 center valign">
        <a onclick="passwordReset(<?php echo $row['id'];?>)" class="waves-effect waves-light btn-large valign red">Reset</a>
      </div>
    </li>
    <?php
  }
}
$db->close();
?>
