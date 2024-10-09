<?php
    require_once("config/conn.php");
    require_once("config/globals.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");
    require_once("models/User.php");

    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);
    $message = new Message($BASE_URL);
    $flashMessage = $message->getMessage();
    
    $userData = $userDao->verifyToken();
    if (!empty($userData->id)) {
        $adm  = $userDao->checkAdm($userData->id);
    }else{
        $adm = false;
    }

    

    if(!empty($flashMessage["msg"])){
        //Limpar a mensagem
        $message->clearMessage();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="img\logos\icon.png">
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/style.css">
    <!-- Font Family -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- FONTE AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>GSC - POPS</title>
</head>
<body>
<header>
    <div class="nav-bar">
        <div class="nav-logo">
            <img class="img-nav-logo" src="<?= $BASE_URL ?>img/logos/logo_nav_bar.png" alt="LOGO GRUPOSC">
        </div>
        <div class="menu-hamburguer" id="menu-btn">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <ul class="nav-links" id="nav-links">
            <?php if($userData): ?>
                <a class="nav-link" href="<?= $BASE_URL ?>"><li class="nav-item"><i class="fas fa-home"></i> Ínicio</li></a>
                <a class="nav-link" href="<?= $BASE_URL ?>include_procedure.php"><li class="nav-item">Criar Procedimento</li></a>

                <?php if($adm): ?>
                    <a class="nav-link" href="<?= $BASE_URL ?>my_procedures.php"><li class="nav-item">Gerenciar Procedimentos</li></a>
                    <a class="nav-link" href="<?= $BASE_URL ?>register.php"><li class="nav-item"><i class="fas fa-user-plus"></i> Gerenciar Usuários</li></a>
                <?php else: ?>
                    <a class="nav-link" href="<?= $BASE_URL ?>my_procedures.php"><li class="nav-item">Meus Procedimentos</li></a>
                <?php endif; ?>

                <!-- <a class="nav-link" href="#"><li class="nav-item">Meu Perfil</li></a> (Futura pagina de informações do usuário - quantidade de procedimentos, avaliação, perguntas, etc...) -->
                <a class="nav-link" href="logout.php"><li class="nav-item"><i class="fas fa-sign-out-alt"></i> Logout</li></a>
            <?php else: ?>
                <a class="nav-link" href="<?= $BASE_URL ?>"><li class="nav-item"><i class="fas fa-home"></i> Ínicio</li></a>
                <a class="nav-link" href="login.php"><li class="nav-item"><i class="fas fa-sign-in-alt"></i> Entrar</li></a>
            <?php endif; ?>
        </ul>
        <div class="search-procedure ">
            <form action="search_process.php" method="GET">
                <input type="text" class="box-search" name="q" id="search" type="search" placeholder="Pesquisar">
                <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
    
</header>
<div class="div-inv"></div>
<div class="main-container">
<?php if(!empty($flashMessage['msg'])): ?>
        <div class="msg-container">
            <p class="msg <?= $flashMessage["type"] ?>"><?= $flashMessage["msg"] ?></p>
        </div>
<?php endif; ?>
<script>
    const menuBtn = document.getElementById('menu-btn');
    const navLinks = document.getElementById('nav-links');

    menuBtn.addEventListener('click', () => {
    navLinks.classList.toggle('open');
    });
</script>