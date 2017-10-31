<?php

require_once 'inc/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('home.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'nome' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'cognome' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'mail' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'codiceFiscale' => array(
                'required' => true,
                'min' => 2,
                'max' => 26
            )
        ));

        if($validate->passed()) {
            try {
                $user->update(array(
                    'nome' => Input::get('nome'),
                    'cognome' => Input::get('cognome'),
                    'mail' => Input::get('mail'),
                    'codiceFiscale' => Input::get('codiceFiscale')
                ));

                Session::flash('home', 'I tuoi dettagli sono stati aggiornati.');
                Redirect::to('home.php');

            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach($validate->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>


<?php require_once 'navigation.php'; ?>
<br>
<div class="homeContainer">
    <div class="col-md-5">
        <form action="" method="post">
            <div class="field" style="padding-top:10px;">
                <label for="nome">Nome</label>
                <input class="form-control" type="text" name="nome" value="<?php echo escape($user->data()->nome); ?>">
            </div>
            <div class="field" style="padding-top:10px;">
                <label for="cognome">Cognome</label>
                <input class="form-control" type="text" name="cognome" value="<?php echo escape($user->data()->cognome); ?>">
            </div>
            <div class="field" style="padding-top:10px;">
                <label for="codiceFiscale">CodiceFiscale</label>
                <input class="form-control" type="text" name="codiceFiscale" maxlength="16" value="<?php echo escape($user->data()->codiceFiscale); ?>">
            </div>
            <div class="field" style="padding-top:10px;">
                <label for="mail">Mail</label>
                <input class="form-control" type="text" name="mail" value="<?php echo escape($user->data()->mail); ?>">
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            </div>
            <br>
            <div class="field" style="padding-top:20px;">
                <input class="form-control btn btn-success" type="submit" value="Update">
            </div>
        </form>
    </div>
</div>
<?php require_once 'footer.php'; ?>
