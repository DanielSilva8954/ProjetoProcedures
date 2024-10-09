<?php
    require_once("templates/header.php");
    require_once("config/conn.php");
    require_once("config/globals.php");
    require_once("models/Message.php");
    require_once("models/Passo.php");
    require_once("models/Procedimento.php");
    require_once("dao/PassoDAO.php");
    require_once("dao/ProcedimentoDAO.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    $message = new Message($BASE_URL);
    $procedimento = new Procedimento();
    $passo = new Passo();
    $passoDao = new PassoDAO($conn, $BASE_URL);
    $procedimentoDao = new ProcedimentoDAO($conn, $BASE_URL);
    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken();

    // Resgata todos os procedimentos
    $procedimentos = $procedimentoDao->getViewProcedimento();

?>
    <?php require("templates/card_procedimento.php") ?>
    

<?php
    require_once ("templates/footer.php");
?>