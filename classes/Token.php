<?php
// questa classe serve a impostare un token ogni volta che un utente accede alla pagina di registrazione/login
// il token viene memorizzato nella variabile di sessione e viene passato in un campo nascosto del form. Ogni
//volta che l'utente preme il pulsante invio, il token viene confrontato con il valore del token settato nella
// sessione. Questo serve a evitare attacchi di tipo cross site request forgery.
class Token {
// metodo per la generazione del token
    public static function generate() {
        return Session::put(Config::get('sessions/token_name'), md5(uniqid()));
    }
// metodo per controllare che il token in sessione coincida con il token inviato
    public static function check($token) {
        $tokenName = Config::get('sessions/token_name');
        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}