<?php

require_once 'inc/init.php';

if (Input::exists()) {
//controllo il token per evitare attacchi CSRF
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'nome' => array(
                'name' => 'nome',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'cognome' => array(
                'name' => 'cognome',
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
            'codicefiscale' => array(
                'name' => 'codicefiscale',
                'required' => true,
                'min' => 6,
                'unique'=> 'codiceFiscale'
            ),
            'mail' => array(
                'name' => 'mail',
                'required' => true,
                'min' => 2,
                'max' => 50,
                'unique'=>'mail'
            ),
            'username' => array(
                'name' => 'username',
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'username'
            ),
            // posso impostare la lunghezza minima che deve avere la password.
            'password' => array(
                'name' => 'password',
                'required' => true,
                'min' => 8
            ),
            'confermapassword' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'gg' => array(
                'required' => true,
            ),
            'mm' => array(
                'required' => true,
            ),
            'yy' => array(
                'required' => true,
            )
        ));

//        se la validazione va a buon fine inserisco i dati dell'utente nel database
        if ($validate->passed()) {
            $user = new User();
//            genero un salt casuale e lo aggiungo alla password per la cifratura
            $salt = Hash::salt(32);
            $datanascita = strtotime(Input::get('yy').'-'.Input::get('mm').'-'.Input::get('gg'));
            $datanascita = date('Y-m-d',$datanascita);

            try {
                $user->create(array(
                    'nome' => Input::get('nome'),
                    'cognome' => Input::get('cognome'),
                    'codicefiscale' => strtoupper(Input::get('codicefiscale')),
                    'mail' => Input::get('mail'),
                    'dataNascita'=> $datanascita,
                    'username' => Input::get('username'),
//                    genero la password
                    'password' => Hash::make(Input::get('password'), $salt),
                    'salt' => $salt,
                    'status' => substr(hash('sha256',Input::get('mail')),0,15)
                ));
// invio all'utente una mail con il link di attivazione dell'account
                mail(
                    Input::get('mail'),
                    'OGGETTO',
                    'Attivazione account. Click to activate: www.technology4u.altervista.org/activate.php?act='.Input::get('username').'_'.hash('sha256',Input::get('mail')),
                    'From: "Gabriele Soldati" <name.surname@example.com>'
                );

                Session::flash('home', 'Benvenuto '.Input::get('username').'! Il tuo account è stato registrato. Puoi effettuare il login ');
                Redirect::to('home.php');
            } catch(Exception $e) {
                echo $error, '<br>';
            }
//se l'array _errors contiene errori vuol dire che la validazione dei campi non è andata a buon fine
// controllo quali campi non sono stati rispettati e
        } else {
            foreach ($validate->errors() as $error) {
//                stampa a video gli errori
//                echo '<p style="color:red;">'.$error.'</p>';

                switch ($error) {
                    case 'nome is required':
                        $NomeRequired = true;
                        $errorNome = true;
                        break;
                    case 'cognome is required':
                        $CognomeRequired = true;
                        $errorCognome = true;
                        break;
                    case 'mail is required':
                        $EmailRequired = true;
                        $errorEmail = true;
                        break;
                    case  'username is required':
                        $usernameRequired = true;
                        $errorUser = true;
                        break;
                    case  'codicefiscale is required':
                        $cfRequired = true;
                        $errorCf = true;
                        break;
                    case  'gg is required':
                        $errorData = true;
                        break;
                    case  'password is required':
                        $passwordRequired = true;
                        $errorPass = true;
                        break;
                    case 'confermapassword is required':
                        $confPasswordRequired = true;
                        $errorConfPass = true;
                        break;
                }
            }
        }
    }
}

require_once 'navigation.php';
?>
    <div class="homeContainer">

        <div class="col-md-8" style="padding: 20px 0px 20px 0px;">
            <div class="col-md-12">
                <form action="" class="form-group" method="post">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

                    <div class="field  col-md-6 <?php if($errorNome){echo 'has-error';}?>" style="padding-top:10px;">
                        <label for="nome">Nome:</label>
                        <input class="form-control" type="text" <?php if($NomeRequired){echo 'placeholder="required"';}?> name="nome" value="<?php echo escape(Input::get('nome')); ?>" id="name">
                    </div>

                    <div class="field col-md-6 <?php if($errorCognome){echo 'has-error';}?>" style="padding-top:10px;">
                        <label for="cognome">Cognome:</label>
                        <input class="form-control" type="text" <?php if($CognomeRequired){echo 'placeholder="required"';}?> name="cognome" id="cognome" value="<?php echo escape(Input::get('cognome')); ?>">
                    </div>

                    <div class="col-md-6 <?php if($errorEmail){echo 'has-error';}?>" style="padding-top:10px;">
                        <label for="mail">Email:</label>
                        <input class="form-control" type="text" <?php if($EmailRequired){echo 'placeholder="required"';}?> name="mail" id="mail" value="<?php echo escape(Input::get('mail')); ?>">
                    </div>

                    <div class="col-md-6 <?php if($errorUser){echo 'has-error';}?>" style="padding-top:10px;">
                        <label for="username">Username:</label>
                        <input class="form-control" type="text" <?php if($usernameRequired){echo 'placeholder="required"';}?> name="username" id="username" value="<?php echo escape(Input::get('username')); ?>">
                    </div>

                    <div class="col-md-6 <?php if($errorCf){echo 'has-error';}?>"  style="padding-top:30px;">
                        <label for="codicefiscale">Codice Fiscale: </label>
                        <input  class="form-control" type="text" <?php if($cfRequired){echo 'placeholder="required"';}?> maxlength="16" name="codicefiscale" id="codicefiscale" value="<?php echo escape(Input::get('codicefiscale')); ?>">
                    </div>

                    <div class="col-md-6 <?php if($errorData){echo 'has-error';}?>"  style="padding-top:10px;">
                        <div><label for="gg">Data di nascita: </label></div>
                        <div class=" col-md-4">
                            Giorno <select class="form-control" name="gg">
                                <option value=""> - </option>
                                <?php
                                    for ($i=1;$i<32;$i++) {
                                        echo '<option value = "'.$i.'">'.$i.'</option >';
                                    }?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            Mese <select class="form-control" name="mm">
                                <option value=""> - </option>
                                <?php
                                    for ($i=1;$i<13;$i++) {
                                        echo '<option value = "'.$i.'">'.$i.'</option >';
                                    }?>
                            </select>
                        </div>
                        <div class=" col-md-4">
                            Anno<select class="form-control" name="yy">
                                <option value=""> - </option>
                                <?php
                                    for ($i=2017;$i>1900;$i--) {
                                        echo '<option value = "'.$i.'">'.$i.'</option >';
                                    }?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 <?php if($errorPass){echo 'has-error';}?>" style="padding-top:10px;">
                        <label for="password">Password:</label>
                        <input class="form-control" type="password"  <?php if($passwordRequired){echo 'placeholder="required"';}?> name="password" id="password">
                    </div>

                    <div class="col-md-6 <?php if($errorConfPass){echo 'has-error';}?>" style="padding-top:10px;">
                        <label for="confermapassword">Conferma Password:</label>
                        <input class="form-control" type="password" <?php if($confPasswordRequired){echo 'placeholder="required"';}?> name="confermapassword" id="confermapassword" value="">
                    </div>
                    <div class="col-md-12"><br><br></div>
                    <div class="col-md-6">
                        <input class="form-control btn btn-success" type="submit" value="Registrati">
                    </div>

                </form>
            </div>
        </div>
    </div>

<?php require_once 'navigation.php';?>
