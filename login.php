<?php

require_once 'inc/init.php';

// setto una variabile di sessione per il controllo CAPTCHA collegata all'ip dell'utente
if (!isset($_SESSION[$_SERVER['REMOTE_ADDR']])) {
    $count = 0;
    $_SESSION[$_SERVER['REMOTE_ADDR']] = $count;
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {

        // eseguo la validazione dei risultati
        if ($_SESSION[$_SERVER['REMOTE_ADDR']] < 3) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'username' => array('required' => true),
                'password' => array('required' => true),
                'codiceTempo' => array('required' => true)
            ));

            if ($validate->passed()) {
                // variabile per la comparsa del captcha
                $user = new User();
                $data = Input::get('yy') . '-' . Input::get('mm') . '-' . Input::get('gg');
                $data = date('Y-m-d', strtotime($data));
                $remember = (Input::get('remember') === 'on') ? true : false;
                $login = $user->login(Input::get('username'), Input::get('password'), $data, Input::get('codiceTempo'), $remember);

                if ($login) {
                    $_SESSION[$_SERVER['REMOTE_ADDR']] = 0;
                    Redirect::to('home.php');

                } else {
                    echo '<p>Username, password o data di nascita invalidi</p>';
                    // aumento il contatore per il CAPTCHA
                    $_SESSION[$_SERVER['REMOTE_ADDR']]++;
                    $_SESSION[$_SERVER['REMOTE_ADDR']];
                }
            } else {
                foreach ($validate->errors() as $error) {
//                    echo $error, '<br>';
                    switch ($error) {
                        case 'username is required':
                            $usernameRequired = true;
                            $errorUser = true;
                            break;
                        case 'password is required':
                            $passwordRequired = true;
                            $errorPassword = true;
                            break;
                        case  'codiceTempo is required':
                            $codiceRequired = true;
                            $errorCodice = true;
                            break;

                    }
                }
            }

            // dopo 3 tentativi di login senza successo
        } else {
            $captcha = $_POST['g-recaptcha-response'];
            $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcitCITAAAAAEfK2BCfMMag9D4i3GSeW8qyiRSo&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
            // se la risposta ai captcha è positiva procedo con la validazione dei campi
            if ($response['success'] == true) {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'username' => array('required' => true),
                    'password' => array('required' => true),
                    'codiceTempo' => array('required' => true)

                ));
            }
            if ($validate->passed()) {
                // variabile per la comparsa del captcha
                $user = new User();
                $data = Input::get('yy') . '-' . Input::get('mm') . '-' . Input::get('gg');
                $data = date('Y-m-d', strtotime($data));
                $remember = (Input::get('remember') === 'on') ? true : false;
                $login = $user->login(Input::get('username'), Input::get('password'), $data, $remember);

                if ($login) {

                    $_SESSION[$_SERVER['REMOTE_ADDR']] = 0;
                    Redirect::to('home.php');
                } else {
                    echo '<p>Username, password o data di nascita invalidi</p>';
                }
            } else {
                foreach ($validate->errors() as $error) {
//                    echo $error, '<br>';
                    switch ($error) {
                        case 'username is required':
                            $usernameRequired = true;
                            $errorUser = true;
                            break;
                        case 'password is required':
                            $passwordRequired = true;
                            $errorPassword = true;
                            break;
                        case  'codiceTempo is required':
                            $codiceRequired = true;
                            $errorCodice = true;
                            break;

                    }

                }
            }
        }
    }
}

require_once 'navigation.php';
?>
<div class="homeContainer">
    <div class="col-md-8 ">
        <form action="" class="form-group" method="post">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <div class="col-md-8">
                <div class="col-md-6 <?php if ($errorUser) {
                    echo 'has-error';
                } ?>" style="padding-top:10px;">
                    <label for='username'>Username</label>
                    <input class="form-control" type="text" name="username" id="username"
                           value="<?php echo escape(Input::get('username')); ?>" <?php if ($usernameRequired) {
                        echo 'placeholder="required"';
                    } ?>>
                </div>
                <div class="col-md-6 <?php if ($errorPassword) {
                    echo 'has-error';
                } ?>" style="padding-top:10px;">
                    <label for='password'>Password</label>
                    <input class="form-control" type="password" name="password"
                           id="password" <?php if ($passwordRequired) {
                        echo 'placeholder="required"';
                    } ?>>
                </div>
                <div class="col-md-6 <?php if ($errorCodice) {
                    echo 'has-error';
                } ?>" style="vertical-align: middle; padding-top:10px;">
                    <label for='codiceTempo'>Codice</label>
                    <input class="form-control" type="text" maxlength="5" name="codiceTempo"
                           value="<?php echo escape(Input::get('codice')); ?>"
                           id="codiceTempo" <?php if ($codiceRequired) {
                        echo 'placeholder="required"';
                    } ?>>
                </div>
                <div class="col-md-6"><br></div>
                <div class="col-md-6 spacing" style="padding: 20px 0px 0px 15px;">
                    <a href="codicetempo.php?username=" id="linkCodiceTempo" onclick="myFunction()"> &#8594; codice a
                        tempo</a>
                </div>

                <div class="col-md-12" style="padding-top:10px;">
                    <div><label for="gg">Data di nascita: </label></div>
                    <div class=" col-md-4">
                        Giorno <select class="form-control" name="gg">
                            <option value=""> -</option>
                            <?php
                            for ($i = 1; $i < 32; $i++) {
                                echo '<option value = "' . $i . '">' . $i . '</option >';
                            } ?>
                        </select>
                    </div>
                    <div class=" col-md-4">
                        Mese <select class="form-control" name="mm">
                            <option value=""> -</option>
                            <?php
                            for ($i = 1; $i < 13; $i++) {
                                echo '<option value = "' . $i . '">' . $i . '</option >';
                            } ?>
                        </select>
                    </div>
                    <div class=" col-md-4">
                        Anno<select class="form-control" name="yy">
                            <option value=""> -</option>
                            <?php
                            for ($i = 2017; $i > 1900; $i--) {
                                echo '<option value = "' . $i . '">' . $i . '</option >';
                            } ?>
                        </select>
                    </div>
                </div>
                <?php
                if ($_SESSION[$_SERVER['REMOTE_ADDR']] >= 3) { ?>
                    <div class="col-md-12"><br></div>
                    <div class=" col-md-12">
                        <div class="g-recaptcha" data-sitekey="6LcitCITAAAAALL743iWZ37CmTpczD19ZBdU1WPu"></div>
                    </div>
                    <div class="col-md-12"><br></div>

                <?php } ?>
                <div class="clearfix"></div>
                <div class="col-md-8" style="padding-top:10px;">
                    <label for="remember">Ricordami</label>
                    <input type="checkbox" name="remember" id="remember">
                </div>
                <div class="col-md-12" style="padding-top:20px;">
                    <input type="submit" class="form-control btn btn-success" value="Accedi">
                </div>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
    <br>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    function myFunction() {
        var username = document.getElementById('username').value;
        document.getElementById('linkCodiceTempo').href = "codicetempo.php?username=" + username;

    }

</script>
<?php require_once 'footer.php'; ?>

