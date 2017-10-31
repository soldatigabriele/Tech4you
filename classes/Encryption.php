<?php

// questa classe serve a cifrare i file
class Encryption
{
    const CIPHER = MCRYPT_RIJNDAEL_128; // AES
    const MODE   = MCRYPT_MODE_CBC;
    private $file;

    // la chiave sarà definita per ogni utente e sarà equivalente all'hash in md5 della password cifrata
    private $key;
    public function __construct($key) {
        $this->key = $key;
    }

    public function encrypt($plaintext) {
// l'IV è un vettore di valori generati casualmente che determinano il seed utilizzato dall'algoritmo di criftatura
// la sua lunghezza è determinata dalla combinazione del metodo e dall'algoritmo di cifratura utilizzati
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
// popolo il vettore iv con valori generati casualmente
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_URANDOM);
// cripto il valore contenuto in $plaintext
        $ciphertext = mcrypt_encrypt(self::CIPHER, $this->key, $plaintext, self::MODE, $iv);
// $iv serve a decodificare il contenuto di $ciphertext, quindi lo allego al contenuto del valore cifrato
        return base64_encode($iv.$ciphertext);
    }

// decodifico il testo cifrato passato in input
    public function decrypt($ciphertext) {
        $ciphertext = base64_decode($ciphertext);
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE);
// controllo se presente l'iv
        if (strlen($ciphertext) < $ivSize) {
            throw new Exception('Missing initialization vector');
        }
//recupero il vettore IV
        $iv = substr($ciphertext, 0, $ivSize);
//recupero il corpo del testo cifrato
        $ciphertext = substr($ciphertext, $ivSize);
//   decodifico i dati e li ritorno
        $plaintext = mcrypt_decrypt(self::CIPHER, $this->key, $ciphertext, self::MODE, $iv);
        return rtrim($plaintext, "\0");
    }
}
