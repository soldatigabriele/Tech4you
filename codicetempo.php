<?php
require_once 'inc/init.php';
?>
<?php require_once 'navigation.php'; ?>
<br>
<div class="homeContainer">
    <div class="col-md-7">
        <form action="" method="post">
            <div class="col-md-8">
                <div class="col-md-6">
                    <label for="username">Username</label>
                    <input class="form-control" maxlength="16" type="text" value="<?php echo escape(Input::get('username')); ?>" name="username">
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="col-md-6">
                    <input class="form-control btn btn-success" type="submit" value="Genera Codice">
                </div>
            </div>
        </form>

    <?php
    if (Input::exists()) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true
            )
        ));
        if ($validate->passed()) {
            $user = new User(Input::get('username'));
            $id = $user->data()->idUtente;
            $code = TimeCode::getTime($id);
            echo ' <div class="clearfix"></div><br>';
            echo '<div class="col-md-6"> Codice a tempo: <h2 id="codiceGenerato">' . $code . '</h2></div>';
            echo ' <div class="clearfix"></div><br>';
            echo '<div class="col-md-6"> Il codice per l\'account <b>' . escape(Input::get('username')) . ' </b> vale ancora per:<p style="font-weight:800;font-size:16px;"><span id="timerMin"></span><span id="timerSec"></span></p></div>';
        }
    }
    ?>
        <div class="clearfix"></div><br>
        <div class="col-md-3">
            <form action="login.php" method="POST">
                <div class="field">
                    <input type="hidden" name="username" value="<?php echo escape(Input::get('username'));?>">
                    <input type="hidden" id="codice" name="codice" value="">
                    <input class="form-control btn btn-success" onclick="getCode()" type="submit" value="Indietro" name="indietro" style="margin-left:15px;">
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
    <br>
</div>
<?
echo date('H-i-s'); ?>
<script>
    function myTimer() {
        var d = new Date();
        document.getElementById("timerMin").innerHTML = (Math.ceil(d.getMinutes() / 10) * 10) - d.getMinutes()-1 + " minuti e ";
        document.getElementById("timerSec").innerHTML = 60 - (d.getSeconds()) + " secondi";
    }


    var c = 1;
    var d = new Date();
    if (d.getSeconds() != 0 || 60 - (Math.ceil(d.getMinutes() / 10) * 10) != 0) {
        var myVar = setInterval(myTimer, 100);
    }else{
        document.getElementById("timerMin").innerHTML = "Codice Scaduto: ";
        document.getElementById("timerSec").innerHTML = " ricaricare la pagina";
    }

</script>
<script>
    function getCode(){
        var code = document.getElementById("codiceGenerato").innerHTML;
        document.getElementById('codice').value = code;
    }
</script>

<?php require_once 'footer.php'; ?>
