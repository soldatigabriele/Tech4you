<?php
// questo file viene incluso in tutti gli altri file e contiene i valori dei parametri di configurazione di accesso al
// database e altri parametri come il nome che assumono le sessioni, il nome dei cookie, la loro durata predefinita.

session_start();

//disabilito il report degli errori
error_reporting(0);

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'db' => 'my_technology4u'
    ),
    //nomi dei cookie, della sessione e del token contro CSFR
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'sessions' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);


//definisco delle costanti:
// cartella di upload delle carte identità e codice fiscale
define('UPLOADDIR','uploads/');
define('INCL','inc/');
define('FUNC',INCL.'functions/');

// questa funzinoe permette di caricare le classi semplicemente istanziandole, senza dover includere i file manualmente.
// Per esempio scrivendo $user = new User(); viene automaticamente caricato il file User.php dalla cartella "classes"
spl_autoload_register(function($class) {
    require_once 'classes/'.$class.'.php';
});

//includo le funzioni, file per accesso al database e query
require_once(FUNC.'functions.php');
require_once(FUNC.'sanitize.php');
require_once('query.php');

//controllo se l'utente che visita la pagina ha un cookie settato che coincide con un valore nel db. Se ce l'ha allora
// lo utilizzo per prendere l'id dell'utente dal database ed effettuare il login
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('sessions/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('sessioneutente', array('hash', '=', $hash));

    if($hashCheck->count()) {
        // prendo l'id dalla tabella e lo uso per effettuare il login in automatico
        $user = new User($hashCheck->first()->fkUtente);
        $user->login();
    }
}

// se sono presenti immagini sensibili non cifrate vengono eliminate
$user = new User();
if(file_exists(UPLOADDIR . $user->data()->idUtente . 'CIf.jpg')||file_exists(UPLOADDIR . $user->data()->idUtente . 'CIr.jpg')||file_exists(UPLOADDIR . $user->data()->idUtente . 'CF.jpg')){
    UploadFile::eliminaImmagine(UPLOADDIR . $user->data()->idUtente . 'CIf.jpg');
    UploadFile::eliminaImmagine(UPLOADDIR . $user->data()->idUtente . 'CIr.jpg');
    UploadFile::eliminaImmagine(UPLOADDIR . $user->data()->idUtente . 'CF.jpg');
}
