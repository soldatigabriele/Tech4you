<?php

class DB
{
    private static $_instance = null;
    private $_pdo,
        $_query,
        $_error = false,
        $_results,
        $_count = 0;

//    eseguo la connessione al database
    private function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

//    controllo se l'oggetto è stato istanziato
    public static function getInstance()
    {
//        se non è istanziato creo una nuova istanza dell'oggetto
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

//eseguo la query
    public function query($sql, $params = array())
    {
        $this->_error = false;
// eseguo il metodo prepare()
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
//                    esegue la sostituzione dei placeholder con i valori
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
// eseguo la query e salvo i risultati nella variabile $this->_results
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }

        return $this;
    }

//    questo metodo prende in input l'azione da eseguire, il nome della tabella e delle condizioni e resituisce una query
// viene richiamata dai metodi delete() e get()
    public function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
//            operatori consentiti
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
// controlla che l'operatore sia uno di quelli consentiti
            if (in_array($operator, $operators)) {
//                prepara la query con i placeholder
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
//richiamo il metodo per eseguire la query
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }

        return false;
    }

//    query per l'inserimento di dati nelle tabelle
    public function insert($table, $fields = array())
    {
        $keys = array_keys($fields);
        $values = null;
        $x = 1;
// viene preparata una query con tanti punti interrogativi quanti sono i parametri da inserire.
        foreach ($fields as $field) {
            $values .= '?';
            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
// richiamo il metodo query() per eseguire la query
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

// metodo per aggiornare una tupla della tabella Utente selezionando l'idUtente della riga da aggiornare
    public function update($table, $id, $fields)
    {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE idUtente = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

//    elimino un dato dalla tabella
    public function delete($table, $where)
    {
        return $this->action('DELETE ', $table, $where);
    }
//recupero una tupla della tabella
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }
// metodo per ritornare i risultati di una query
    public function results()
    {
        return $this->_results;
    }
//ritorno la prima riga dei risultati
    public function first()
    {
        $data = $this->results();
        return $data[0];
    }
//ritorno il conteggio dei risultati
    public function count()
    {
        return $this->_count;
    }
//ritorno gli errori
    public function error()
    {
        return $this->_error;
    }
}