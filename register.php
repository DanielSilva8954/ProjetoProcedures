<?php 
    require_once("templates/header.php");
    require_once("templates/footer.php");
    require_once("dao/UserDAO.php");
    require_once("dao/ProcedimentoDAO.php");
    require_once("dao/PassoDAO.php");
    require_once("config/conn.php");
    require_once("config/globals.php");

    $userData = $userDao->verifyToken(false);
    if($adm === false){
        $message->setMessage("Você não tem acesso a essa página.", "error", "index.php");
    }
?>

<div class="container-login">
    <div class="register">
        <label for="Criar-user"><h3>Criar Novo Usuário:</h3></label>
        <form action="login_process.php" method="post">
            <input type="hidden" name="type" value="register">
            <label for="nome">Nome: </label>
            <input type="text" name="name" id="name" class="input-login" placeholder="Nome">
            <label for="nome">Sobrenome: </label>
            <input type="text" name="lastname" id="lastname" class="input-login" placeholder="Sobrenome">
            <label for="E-mail">E-mail: </label>
            <input type="email" name="email" id="email" class="input-login" placeholder="E-mail">
            <label for="E-mail">Senha: <h6>(8 dígitos, 1 caractere especial, 1 maiúscula, 1 minúscula, 1 número).</h6></label>
            <input type="password" name="password" id="password" class="input-login" placeholder="Senha">
            <label for="E-mail">Confirmar Senha: </label>
            <input type="password" name="confirm-password" id="confirm-password" class="input-login" placeholder="Confirmar Senha">
            <select name="adm" class="seleciona-adm">
                <option value="0">Selecione</option>
                <option value="1">Privilégios Administrativos</option>
                <?php if($userData->adm === 2): ?>
                    <option value="2">Privilégios Super Administrativos</option>
                <?php endif; ?>
                <option value="0">Usuário Comum</option>
            </select>
            <button type="submit" class="btn-login"><i class="fas fa-sign-out-alt"></i> Criar Usuário</button>
            
        </form>
    </div>
    <div class="register">
        <label for="Editar-user"><h3>Editar Usuário:</h3></label>
        <form action="alter_user.php" method="post">
            <input type="hidden" name="type" value="search">
            <label for="E-mail">E-mail: </label>
            <input type="email" name="email" id="email" class="input-login" placeholder="E-mail">
            <button type="submit" class="btn-login"><i class="fas fa-user"></i> Buscar</button>
        </form>
    </div>

      

</div>