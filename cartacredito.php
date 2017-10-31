<?php

require_once 'inc/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('home.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'numeroCarta' => array(
                'required' => true,
                'min' => 16,
                'max' => 16
            ),
            'dataScadenza' => array(
                'required' => true
            ),
            'ccv' => array(
                'min' => 3
            ),
            'intestatario' => array(
                'required' => true,
                'min' => 2,
                'max' => 26
            )
        ));

        if ($validate->passed()) {
            $password = $user->data()->password;
            $key = hash('md5', $password);
            $crypt = new Encryption($key);
            $numeroCartaCriptato = $crypt->encrypt(Input::get('numeroCarta'));
            $ccvCriptato = $crypt->encrypt(Input::get('ccv'));
            $intestatarioCriptato = $crypt->encrypt(Input::get('intestatario'));

            try {
                $cc = DB::getInstance();
                $cc->insert('cartacredito', array(
                    'numeroCarta' => $numeroCartaCriptato,
                    'dataScadenza' => Input::get('dataScadenza'),
                    'ccv' => $ccvCriptato,
                    'intestatario' => $intestatarioCriptato,
                    'fkUtente' => $user->data()->idUtente,
                    'fkMetodoPagamento' => Input::get('circuito')
                ));

                echo 'Dati inseriti correttamente';
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validate->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>


<?php require_once 'navigation.php'; ?>
<br>
<div class="homeContainer" >
    <div class="col-md-5" >
        <?php if (!isset($_POST['mostraCarta']) && !isset($_POST['rivelaCarte']) ) { ?>
            <form action="" method="post" id="form">
                <div class="field" style="padding-top:10px;">
                    <label for="numeroCarta">Numero Carta</label>
                    <input class="form-control" id="nc" maxlength="16" type="text" name="numeroCarta" onkeyup="checkInp(this.id)" >
                </div>
                <div class="field" style="padding-top:10px;">
                    <label for="intestatario">Intestatario</label>
                    <input class="form-control" type="text" name="intestatario">
                </div>
                <div class="field" style="padding-top:10px;">
                    <label for="dataScadenza">Data Scadenza</label>
                    <input class="form-control" type="text" maxlength="10" name="dataScadenza" placeholder="YYYY/MM/DD">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                </div>
                <div class="field col-md-4" style="padding-top:10px;">
                    <label for="ccv">CCV</label>
                    <input class="form-control" id="cvv" maxlength="3" type="text" name="ccv" onkeyup="checkInp(this.id)">
                </div>
                <div class="field col-md-4" style="padding-top:10px;">
                    <label for="circuito">Circuito</label>
                    <select name="circuito" class="form-control">

                        <?php
                        $circuiti = DB::getInstance();
                        $circuiti->get('metodopagamento',['idMetodoPagamento','>','0']);
                        foreach($circuiti->results() as $circuito){
                            echo '<option value="'.$circuito->idMetodoPagamento.'">'.$circuito->tipo.'</option>';
                        };
                        ?>

                    </select>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="field">
                    <input class="form-control btn btn-success" type="submit" value="Update">
                </div>
            </form>
            <?php
// se sono presenti delle carte salvate mostra il pulsante per vederle
            $cc = DB::getInstance();
            $count = $cc->get('cartacredito', ['fkUtente', '=', $user->data()->idUtente]);
            if($count->count()) { ?>
                <div class="clearfix"></div><br>
                <form action = "" method = "POST" >
                <div class="field" >
                    <input class="form-control btn btn-primary" type="submit" value="Mostra Carte Salvate" name="mostraCarta" >
                </div >
            </form >
                <?php } ?>
        <?php } else {
            ?>
                <form action="" method="POST" >
                    <div class="field col-md-4">
                        <label for="password">Password</label>
                        <input class="form-control" type="password" name="password">
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="field">
                        <input class="form-control btn btn-success" type="submit" value="Mostra" name="rivelaCarte">
                    </div>
                </form>
            <?php
            if(isset($_POST['rivelaCarte'])){

                $password = Hash::make(Input::get('password'), $user->data()->salt);
                if($password === $user->data()->password) {
                    $key = hash('md5', $password);
                    $crypt = new Encryption($key);
                    $cc = DB::getInstance();
                    $valori = $cc->get('cartacredito', ['fkUtente', '=', $user->data()->idUtente]);
                    foreach ($valori->results() as $valore) {
                        $carta = $crypt->decrypt($valore->numeroCarta);
                        echo '
                                <br>Numero Carta: '.substr($carta,0,4).'-'.substr($carta,4,4).'-'.substr($carta,8,4).'-'.substr($carta,12,4).'
                                <br>Intestatario: ' . $crypt->decrypt($valore->intestatario).'
                                <br>Data Scadenza: ' . $valore->dataScadenza.'
                                <hr>';
                    }
                    echo ' 
                            <form action="cartacredito.php" method="POST">
                                <div class="field">
                                    <input class="form-control btn btn-success" id="round" type="submit" value="Indietro" name="indietro">
                                </div>
                            </form>

                        ';
                }else{
                    echo 'password errata';
                }
            }
        } ?>
    </div>
    <div class="clearfix"></div><br>
</div>
<script type="text/javascript">
	function checkInp(id)
{
  var input=document.getElementById(id).value;
  if (isNaN(input)) 
  {
    alert("Sono permessi solo numeri senza spazi");
	var y = input.substr(0,input.length-1);
	document.getElementById(id).value = y;
  }
}
</script>
<?php require_once 'footer.php'; ?>
