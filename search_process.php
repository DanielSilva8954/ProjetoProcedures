<?php 
    require_once("templates/header.php");
    require_once("config/conn.php");
    require_once("config/globals.php");
    require_once("models/Message.php");
    require_once("models/Passo.php");
    require_once("models/Procedimento.php");
    require_once("dao/PassoDAO.php");
    require_once("dao/ProcedimentoDAO.php");
    $message = new Message($BASE_URL);
    $procedimento = new Procedimento();
    $passo = new Passo();
    $passoDao = new PassoDAO($conn, $BASE_URL);
    $procedimentoDao = new ProcedimentoDAO($conn, $BASE_URL);
    
$query = filter_input(INPUT_GET,"q");
$query = "%$query%";

$procedimentos = $procedimentoDao->searchProcedimento($query);

?>

<?php 
    if ($adm) {
        require("templates/card_my_procedimento.php");
    }else{
        require("templates/card_procedimento.php");
    }

  ?>
    

<?php
    require_once ("templates/footer.php");
?>