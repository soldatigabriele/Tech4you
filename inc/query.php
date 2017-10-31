<?php

//CONNESSIONE AL DATABASE
$db = mysql_connect(Config::get('mysql/host'),Config::get('mysql/username'),Config::get('mysql/password'))
or die(
"<div  style=' width: 200; background-color: black;'>
                <font color=white>
                    <b>ERRORE DI ACCESSO AI DATI</b>
                    <br>L'errore di solito &egrave; dovuto a problemi di sovraccarico del server, &egrave; temporaneo e scompare dopo qualche minuto.<br>
                    <a href='Javascript:location.reload()'>riprova</a>
                </font>
            </div>"
);
mysql_select_db(Config::get('mysql/db'));

//QUERY DI POPOLAMENTO E PAGINAZIONE
//numero di elementi da mostrare per pagina
$x_pag = 6;

// Controllo se $pag è valorizzato...
// ...in caso contrario gli assegno valore 1
if(!isset($_GET['pag'])){ 
    $pag = 1;
}else{// Recupero il numero di pagina corrente.
    $pag = $_GET['pag'];
}

//QUERY RICERCA E POPOLAZIONE
$select="SELECT DISTINCT p.idProdotto, p.titolo, p.immagine, p.numeroDisponibilita, p.prezzo, p.descrizione, p.modello, c.nome, m.nome";
$from="FROM prodotto p, categoria c,  marca m";
$where="WHERE p.fkMarca = m.idMarca AND p.fkCategoria = c.idCategoria";

    //Parametri aggiornati tramite get
    if((isset($_GET['categoria']))&&($_GET['categoria']!=0)){
        $where="$where AND c.idCategoria=$_GET[categoria]";
    }
    if((isset($_GET['marca']))&&($_GET['marca']!=0)){
        $where="$where AND m.idMarca=$_GET[marca]";
    }
    if(isset($_GET['idProdotto'])){
        $where="$where AND p.idProdotto=$_GET[idProdotto]";
    }    
    if(isset($_GET['prezzo'])){
        switch ($_GET['prezzo']){
            case 1:
                $where="$where AND p.prezzo<=20";
            break;
            case 2:
                $where="$where AND p.prezzo>20 AND p.prezzo<=50";
            break;            
            case 3:
                $where="$where AND p.prezzo>50 AND p.prezzo<=100";
            break;
            case 4:
                $where="$where AND p.prezzo>100 AND p.prezzo<=500";
            break;
            case 5:
                $where="$where AND p.prezzo>500";
            break;
        }
    }
    if(isset($_GET['ricerca'])){
        $parolecercate=explode(" ",$_GET['ricerca']);
        foreach ($parolecercate as $parola){
            $where="$where AND p.titolo LIKE '%$parola%'";
        }
    }


//scrittura query
$query="$select $from $where";
// Uso mysql_num_rows per contare le righe presenti
$all_rows=mysql_num_rows(mysql_query($query));
// Tramite una semplice operazione matematica definisco il numero totale di pagine (ceil restituisce il più piccolo interno >= ad un numero)
$all_pages = ceil($all_rows / $x_pag);
// Calcolo da quale record iniziare
$first = ($pag-1)*$x_pag;

// Recupero i record per la pagina corrente...
// utilizzando LIMIT per partire da $first e contare fino a $x_pag
$Mysqlsearch="$select $from $where LIMIT $first, $x_pag";
$result=mysql_query($Mysqlsearch);
$numRighe = mysql_num_rows($result);


//Query per la home
$Mysqlhome="SELECT * FROM prodotto";
$home=mysql_query($Mysqlhome);
//$numRigheHome = mysql_num_rows($home);



//QUERY POPOLAMENTO MENU CATEGORIA
$MyCategoria="SELECT idCategoria, nome FROM categoria ORDER BY nome";
$resultCategoria=mysql_query($MyCategoria);


//QUERY POPOLAMENTO MENU MARCA
$MyMarca="SELECT idMarca, nome FROM marca ORDER BY nome";
$resultMarca=mysql_query($MyMarca);
?>