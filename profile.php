<?php

require_once 'inc/init.php';
require_once 'navigation.php';

if(!$username = Input::get('username')) {
    Redirect::to('home.php');
} else {
    $user = new User($username);

    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
        ?>

<!--        per ritornare il nome dell'utente richiamo la funzione escape() definita in functions\sanitize.php-->
        <div class="homeContainer">
            <h3><?php echo escape($data->username); ?></h3>
            <p>Nome: <?php echo escape($data->nome); ?></p>
            <p>Cognome: <?php echo escape($data->cognome); ?></p>
            <p>Username: <?php echo escape($data->username); ?></p>
            <p>mail: <?php echo escape($data->mail); ?></p>
            <p>Codice Fiscale: <?php echo escape($data->codiceFiscale); ?></p>
        </div>
<?php
    }
}
require_once 'footer.php';

