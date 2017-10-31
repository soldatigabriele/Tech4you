

<?php
//Navigation (Nagigation include le funzioni per la connessione al db, search e apertura html)
    include_once("navigation.php");


$idUtente = $_SESSION['IDutente'];

//ELIMINO IL PRODOTTO DAL CARRELLO
if(isset($_POST['Togli_dal_Carrello'])){
    TogliDalCarrello($_POST['idProdotto'], $idUtente,0);
    echo"<script>
            alert('Il prodotto è stato rimosso correttamente dal carrello.');
        </script>";
}


//AGGIUNGO PRODOTTI AL CARRELLO SE RICHIESTO e AGGIORNO IL MAGAZZINO
if((isset($_POST['Carrello']))&&(isset($_POST['codProdotto']))&&(isset($_POST['numeroDisponibilita']))&&($_POST['numeroDisponibilita']!=0)){
    $CercoProdottoInCarrello="SELECT fkProdotto FROM carrello WHERE ordine=0 AND fkUtente=$idUtente AND fkProdotto=$_POST[codProdotto]";
    $CercoResult=mysql_query($CercoProdottoInCarrello);
    $num_rows= mysql_num_rows($CercoResult);

    if($num_rows==0){
        $AggiungoCarrello="INSERT INTO `carrello` (`ordine`, `fkUtente`, `fkProdotto`, `quantita`) VALUES ('0', '$idUtente', '$_POST[codProdotto]', '$_POST[numeroDisponibilita]')";
        mysql_query($AggiungoCarrello);
    }else{

        $AggiungoCarrello="UPDATE carrello SET quantita=quantita+$_POST[numeroDisponibilita] WHERE ordine=0 AND fkUtente=$idUtente AND fkProdotto=$_POST[codProdotto]";
        mysql_query($AggiungoCarrello);
    }

    //SCALO QUANTITA' DAL MAGAZZINO
    $TogliMagazzino="UPDATE prodotto SET numeroDisponibilita=numeroDisponibilita-$_POST[numeroDisponibilita] WHERE idProdotto=$_POST[codProdotto]";
    mysql_query($TogliMagazzino);
}


//QUERY CARRELLO
$QueryCarrello="SELECT p.idProdotto, c.quantita, p.titolo, p.immagine, p.prezzo FROM prodotto p, carrello c WHERE p.idProdotto=c.fkProdotto AND ordine=0 AND fkUtente=$idUtente";
$resultCarrello=mysql_query($QueryCarrello);

//QUERY ORDINI
$QueryOrdini="SELECT o.idOrdine, sc.stato, o.indirizzo, o.data, cc.numeroCarta, cc.idCartaCredito FROM ordine o, cartacredito cc, statoconsegna sc WHERE sc.idStatoConsegna=o.fkStatoConsegna AND cc.idCartaCredito=o.fkCartaCredito AND o.idOrdine IN (SELECT DISTINCT c.ordine FROM carrello c WHERE c.ordine!=0 AND fkUtente=$idUtente)";
$resultOrdini=mysql_query($QueryOrdini);

?>

        <div class="homeContainer" style="margin-bottom:60%; height:100%;">
            <?php
                $user = new User();
                if($user->isLoggedIn()){ //se è stato fatto il login
            ?>
                <div class="col-md-8">

                    <table id="carrello" class="table table-striped" style="margin-top:15px;">
                        <thead>
                           <tr style="font-weight: 600;">
                                <td>ID</td>
                                <td>Titolo</td>
                                <td>Quantità</td>
                                <td>Prezzo</td>
                                <td></td>
                            </tr>
                        </thead>
                        
                        <tbody>
                        <?php
                            $row= mysql_num_rows($resultCarrello);
                            $totale=0;
                            while($rsCarrello=mysql_fetch_array($resultCarrello)){
                                $totale=$totale+($rsCarrello['prezzo']*$rsCarrello['quantita']);
                                echo"<tr>";
                                    echo"<td>$rsCarrello[idProdotto]</td>";
                                    echo"<td>$rsCarrello[titolo]</td>";
                                    echo"<td>$rsCarrello[quantita]</td>";
                                    echo"<td>$rsCarrello[prezzo]</td>";
                                    echo"<td>
                                            <form nome=\"elimina\" action=\"carrello.php\" target=\"_top\" method=\"post\">
                                            <input class=\"btn btn-success\" type=\"submit\" src=\"img/meno.png\" Value='Elimina' Name='Togli_dal_Carrello' />
                                            <input type=\"hidden\" Value=$rsCarrello[idProdotto] Name='idProdotto' />
                                            </form>
                                        </td>";
                                echo"</tr>";
                            }

                            if($totale>0){
                            echo"<tr>";
                                echo"<td></td>";
                                echo"<td></td>";
                                echo"<td></td>";
                                echo"<td></td>";
                                echo"<td>TOT: ".sprintf("%01.2f", $totale)."</td>";
                            echo"</tr>";
                            }
                        ?>
                        </tbody>
                   </table>
                    <?php if($row!=0){ ?>
                        <form name="confermaordine" action="confermaordine.php" target="_top" method="post">                
                            <input class="btn btn-success" type="submit" src="img/carrello.png" style="margin-left:20px;" Value='Conferma' Name='Conferma_Ordine' />
                            <input type="hidden" Value=<?php echo"$totale"?> Name='prezzototale' />

                        </form>
                    <?php } ?>
                        <br/>
                    <h3>I miei ordini</h3>
                    
                    <table id="carrello" class="table table-striped">
                        <thead>
                            <tr style="font-weight: 600;">
                                <td>ID</td>
                                <td>Data</td>
                                <td>Indirizzo</td>
                                <td>Carta Utilizzata</td>
                                <td>Stato</td>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        while($rsOrdine=mysql_fetch_array($resultOrdini)){
                            echo"<tr>";
                                echo"<td>$rsOrdine[idOrdine]</td>";
                                echo"<td>$rsOrdine[data]</td>";
                                echo"<td>$rsOrdine[indirizzo]</td>";
                                //echo"<td>$rsOrdine[numeroCarta]</td>";
                                //Estrazione numero carta usata nell'ordine
                                //$user = new User( 4 ); /*qui va messo l'id dell'utente*/
                                $key = hash('md5', $user->data()->password);
                                $crypt = new Encryption($key);
                                $cc = DB::getInstance();
                                $valori = $cc->get('cartacredito', ['fkUtente', '=', $user->data()->idUtente]);
                                foreach ($valori->results() as $valore) {
                                    if($valore->idCartaCredito == $rsOrdine[idCartaCredito]){ /*qui va messo l'id della carta */
                                    echo '
                                            <td>**** **** **** ' . substr($crypt->decrypt($valore->numeroCarta), 12, 16).'</td>';
                                    }
                                }
                            
                                echo"<td>$rsOrdine[stato]</td>";

                            echo"</tr>";
                        }
                    echo "</tbody>";
                    echo"</table>";
                    ?>
                </div>
            <?php
            }else{
                include 'inc/paginaRiservata.php';

                }
            ?>
        </div>
    
    <!-- fine container -->


    <!--Footer e chiusura html + JavaScript e jQuery-->
    <?php
        include_once("footer.php");
    ?>


