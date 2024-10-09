<?php 
    require_once("templates/header.php");
    require_once("templates/footer.php");
    require_once("dao/UserDAO.php");
    require_once("dao/ProcedimentoDAO.php");
    require_once("dao/PassoDAO.php");
    require_once("config/conn.php");
    require_once("config/globals.php");
?>
    <div class="container-login">
        <div class="login">
            <form action="login_process.php" method="post">
                <input type="hidden" name="type" value="login">
                <label for="E-mail">E-mail: </label>
                <input type="email" name="email" id="email" class="input-login" placeholder="E-mail">
                <label for="E-mail">Senha: </label>
                <input type="password" name="password" id="password" class="input-login" placeholder="Senha">
                <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i>
                Entrar</button>
            </form>
        </div>

    </div>