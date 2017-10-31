<?php
// in questa pagina 
require_once 'inc/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('home.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'password_corrente' => array(
                'required' => true,
                'min' => 3
            ),
            'nuova_password' => array(
                'required' => true,
                'min' => 3
            ),
            'conferma_nuova_password' => array(
                'required' => true,
                'min' => 3,
                'matches' => 'nuova_password'
            )
        ));
    }


    if($validate->passed()) {
        if(Hash::make(Input::get('password_corrente'), $user->data()->salt) !== $user->data()->password) {
            echo 'Your current password is wrong.';
        } else {
            $salt = Hash::salt(32);
            $user->update(array(
                'password' => Hash::make(Input::get('nuova_password'), $salt),
                'salt' => $salt
            ));
            Redirect::to('home.php?password=changed');
        }
    } else {
        foreach ($validate->errors() as $error) {
//                    echo $error, '<br>';
            switch ($error) {
                case 'password_corrente is required':
                    $OldPassRequired = true;
                    $errorOP = true;
                    break;
                case 'nuova_password is required':
                    $NewPassRequired = true;
                    $errorPassword = true;
                    break;
                case  'conferma_nuova_password is required':
                    $ConfNewPassRequired = true;
                    $errorConfNP = true;
                    break;
            }
        }
    }
}

include_once 'navigation.php';

?>
<br>
<div class="homeContainer">
    <div class="col-md-5">
        <form action="" method="post">
            <div class="field <?php if($errorOP){echo 'has-error';}?>" style="padding-top:10px;">
                <label for="password_corrente">Password attuale</label>
                <input class="form-control" type="password" name="password_corrente" <?php if($OldPassRequired){echo 'placeholder="required"';}?> id="password_corrente">
            </div>

            <div class="field <?php if($errorPassword){echo 'has-error';}?>" style="padding-top:10px;">
                <label for="nuova_password">Nuova Password</label>
                <input class="form-control" type="password" name="nuova_password" id="nuova_password" <?php if($NewPassRequired){echo 'placeholder="required"';}?>>
            </div>

            <div class="field <?php if($errorConfNP){echo 'has-error';}?>" style="padding-top:10px;">
                <label for="conferma_nuova_password">Conferma Nuova Password</label>
                <input class="form-control" type="password" name="conferma_nuova_password" id="conferma_nuova_password" <?php if($ConfNewPassRequired){echo 'placeholder="required"';}?>>
            </div>
            <br>
            <input type="hidden" name="token" id="token" value="<?php echo escape(Token::generate()); ?>" style="padding-top:20px;">
            <input class="form-control btn btn-success" type="submit" value="Cambia Password">
        </form>
    </div>
</div>

<?php include_once 'footer.php'; ?>