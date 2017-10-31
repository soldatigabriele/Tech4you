<?php

include_once 'inc/init.php';

include_once 'navigation.php';
//prendo il parametro get dall'url e lo divido in 2 parti. la prima parte contiene l'username dell'utente da attivare
//la seconda parte contiene un codice generato dall'hash della mail dell'utente e memorizzata nel database
$array = explode('_',Input::get('act'));
$username = $array[0];
$activationCode = substr($array[1],0,15);

//viene cercato l'utente nel database, se viene trovato viene aggiornato il campo relativo allo status e settato a 1
//questo indica che l'account è attivo
$user = new User();
$user->find($username);
if($user->data()->status == $activationCode) {
    $activation = DB::getInstance();
    $activation->update('utente', $user->data()->idUtente, ['status' => 1]);
    echo '<br><h2>ACCOUT ATTIVATO CORRETTAMENTE</h2>';
    echo '<div class="spacing">Vai al <a href="home.php">pannello di controllo</a></div>';
}else{
    echo 'errore di attivazione';
}

include_once 'footer';
