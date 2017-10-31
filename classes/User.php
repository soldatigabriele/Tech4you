<?php

//Questa classe permette di interagire con la tabella utente del database, gestire i login e il logout, creare nuovi
// utente, modificare i dati degli utente esistenti e verificare lo stato di attivazione dell'account.

class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $isLoggedIn;

    public function __construct($user = null) {
//        creo un nuova istanza della classe DB per interagire con il database
        $this->_db = DB::getInstance();
//        prelevo il nome della sessione e del cookie dal file di configurazione globale
        $this->_sessionName = Config::get('sessions/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        // controllo la sessione per verificare se l'utente ha efefttuato il login.
        if(!$user) {
            if(Session::exists($this->_sessionName)) {
        // se è presente un cookie di sessione lo utilizzo per prelare l'id dell'utente e, se esiste, eseguire il login
                $user = Session::get($this->_sessionName);
                if($this->find($user)) {
                    $this->isLoggedIn = true;
                }
            }
        } else {
            $this->find($user);
        }
    }

//    metodo per creare un nuovo utente. Se il processo fallisce viene ritornato un messaggio di errore
    public function create($fields = array()) {
        if(!$this->_db->insert('utente', $fields)) {
            echo 'Problema nella creazione account';
        }
    }

//metodo per modificare i valori in una tabella
    public function update($fields = array(), $id = null) {
// se l'utente è loggato prelevo il suo id
        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->idUtente;
        }
        // se ci sono dei problemi ritorno un messaggio di errore
        if(!$this->_db->update('utente', $id, $fields)) {
            throw new Exception('Problema aggiornamento valori');
        }
    }

    // questa funzione cerca se è presente un utente nella tabella "utente" partendo dall'id o dall'username
    public function find($user = null) {
        if($user) {
            $field = (is_numeric($user)) ? 'idUtente' : 'username';
            $data = $this->_db->get('utente', array($field, '=', $user));
//            se un utente corrispondente all'id o all'username specificato viene trovato, allora la variabile $this->_data assume come valore il contenuto della tupla relativa all'utente
//             in questo modo è possibile in seguito prelevare i dati dell'utente  semplicemente invocando il metodo ->data()->parametro, che ritorna il valore di $this->_data
            if($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null, $password = null, $data = null, $codiceTempo = null,  $remember = false) {
    //se non viene specificato il nome utente e la password, allora viene loggato l'utente in automatico, se esiste. Serve per autenticare verificando la presenza dei cookie
        if(!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->idUtente);
            Session::put('IDutente', $this->data()->idUtente);
            Session::put('NomeUtente', $this->data()->nome);
        } else {
            // se invece l'utente viene specificato, allora  significa che l'utente sta effettuando un login dal form login.php e procedo normalmente
            $user = $this->find($username);

            if ($user) {
                //genero il codice a tempo basandomi sull'idUtente e i minuti attuali
                $var = substr(hash('md5', date('i').$this->data()->idUtente),0,5);
               $var = TimeCode::getTime($this->data()->idUtente);
                // Login sicuro con username, password e data di nascita
                if ($codiceTempo == $var && $this->data()->password === Hash::make($password, $this->data()->salt) && $data == $this->data()->dataNascita) {
                        // inserisco nella sessione l'id dell'utente autentecato
                        Session::put($this->_sessionName, $this->data()->idUtente);
                        //se l'utente preme il tasto "remember me" in fase di login viene eseguito il seguente codice:
                        if ($remember) {
                            // controlla se nella tabella sessioni utente è presente un hash relativo all'id dell'utente.
                            $hashCheck = $this->_db->get('sessioneutente', array('fkUtente', '=', $this->data()->idUtente));
                            //Se non c'è inserisco nel db l'id utente e un hash univoco
                            if (!$hashCheck->count()) {
                                //genero un hash
                                $hash = Hash::unique();
                                $this->_db->insert('sessioneutente', array(
                                    'fkUtente' => $this->data()->idUtente,
                                    'hash' => $hash
                                ));
                            } else {
                                //altrimenti prelevo il valore dell'hash salvato nel database
                                $hash = $hashCheck->first()->hash;
                            }
                            // genero un cookie con il valore dell'hash relativo all'utente; il nome e la durata li prendo dal file di configurazione globale
                            Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                        }
                    // sessione per la gestione del carrello e degli ordini
                    Session::put('IDutente', $this->data()->idUtente);
                    Session::put('NomeUtente', $this->data()->nome);
                    return true;
                }
            }
        }
        return false;
    }


    //prendo lo status dell'utente. se il valore non è 1, allora l'utente non è attivato

    public function status(){
        return $this->data()->status;
    }

    // controllo che l'utente abbia cliccato il link di attivazione arrivatogli per mail
    public function activated()
    {
        if ($this->status() == '1') {
            return true;
        }
        return false;
    }

    //controllo l'esistenza dell'utente
    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }

    //al logout elimino cookie e sessione
    public function logout() {
        // elimino l'hash dalla tabella sessioni_utente
        $this->_db->delete('sessioneutente', array('fkUtente', '=', $this->data()->idUtente));
        //elimino la sessione e i cookie
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    //ritorno i dati relativi all'utente
    public function data(){
        return $this->_data;
    }

    //ritorna se l'utente ha effettuato il login o meno
    public function isLoggedIn() {
        return $this->isLoggedIn;
    }
}