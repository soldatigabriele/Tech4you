<?php

//Questa classe permette la validazione degli input. In input prende un array contenente le varie regole che devono
// essere soddisfatte e il metodo con cui vengono passati i parametri

class Validate {
    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    //effettuo la connessione al database
    public function __construct() {
        $this->_db = DB::getInstance();
    }

    // regole per la validazione degli input.
    public function check($source, $items = array()) {
        // eseguo un doppio ciclo for per scorrere tutte le regole. Se non sono soddisfatte le condizioni viene
        // aggiunto nell'array $_errors un errore tramite il metodo addError()
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $rule_value) {
                $value = $source[$item];
                $item = escape($item);

                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value)) {
                    switch($rule) {
                        // imposto una lunghezza minima
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                            break;
                        // imposto una lunghezza massima
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                            break;
                        // controllo che due parametri corrispondano (password e conferma_password)
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}.");
                            }
                            break;
                        // controllo nella tabella utente che non ci siano parametri gia' presenti
                        case 'unique':
                            $check = $this->_db->get('utente', array($rule_value, '=', $value));

                            if($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            break;
                    }
                }
            }
        }

//se non sono presenti errori $_passed assume valore true
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
    }
// metodo per l'aggiunta di eventuali errori riscontrati durante la validazione degli input
    private function addError($error) {
        $this->_errors[] = $error;
    }
//mostro gli errori all'utente
    public function errors() {
        return $this->_errors;
    }
    // $_passed assume false di default come valore, dopo la validazione, se non sono riscontrati errori viene impostata true
    public function passed() {
        return $this->_passed;
    }
}
