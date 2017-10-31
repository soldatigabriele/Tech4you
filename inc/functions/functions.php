<?php
function TogliDalCarrello($IDprodotto, $IDutente, $ordine){
    $MyProdotto="SELECT quantita FROM carrello WHERE fkProdotto=$IDprodotto AND fkUtente=$IDutente";
    $resultProdotto=mysql_query($MyProdotto);
    $numeroProdotti=mysql_fetch_array($resultProdotto);
    
    //tolgo il prodotto dal carrello
    if($numeroProdotti['quantita']>1){            
        $MySqlTogliCarrello="UPDATE carrello SET quantita=quantita-1 WHERE fkProdotto=$IDprodotto AND fkUtente=$IDutente";
        mysql_query($MySqlTogliCarrello);
    }else{
        $MySqlTogliCarrello="DELETE FROM carrello WHERE fkProdotto=$IDprodotto AND fkUtente=$IDutente";
        mysql_query($MySqlTogliCarrello);
    }
    
    //aggiunge prodotto nel magazzino
    $MySqlAggMagazino="UPDATE prodotto SET numeroDisponibilita=numeroDisponibilita+1 WHERE idProdotto=$IDprodotto";
    mysql_query($MySqlAggMagazino);
    
}

?>
