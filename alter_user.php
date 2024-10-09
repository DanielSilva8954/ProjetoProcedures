<?php
require_once("templates/header.php");
require_once("templates/footer.php");
require_once("config/conn.php");
require_once("config/globals.php");
require_once("models/Message.php");
require_once("models/Passo.php");
require_once("models/Procedimento.php");
require_once("models/User.php");
require_once("dao/PassoDAO.php");
require_once("dao/ProcedimentoDAO.php");
require_once("dao/UserDAO.php");

if($adm === false){
    $message->setMessage("Você não tem acesso a essa página.", "error", "index.php");
}

$message = new Message($BASE_URL);
$procedimento = new Procedimento();
$procedimentoDao = new ProcedimentoDAO($conn, $BASE_URL);
$passo = new Passo();
$passoDao = new PassoDAO($conn, $BASE_URL);
$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");
$email = filter_input(INPUT_POST, "email");
if ($type == "") {
    $type = "search";
}
$userSession = $userDao->verifyToken(false);
$userData = $userDao->findByEmail($email);

?>
<?php if ($type === "search"): ?>
    <?php if($userData): ?>
    <div class="container-login">
        <div class="register">
        <label for="Editar-user"><h3>Editar Usuário:</h3></label>
        <form action="login_process.php" method="post">
            <input type="hidden" name="type" value="edit">
            <label for="nome">Nome: </label>
            <input type="text" name="name" id="name" class="input-login" placeholder="Nome" value="<?= $userData->name ?>">
            <label for="nome">Sobrenome: </label>
            <input type="text" name="lastname" id="lastname" class="input-login" placeholder="Sobrenome" value="<?= $userData->lastname ?>">
            <label for="E-mail">E-mail: </label>
            <input type="email" name="email" id="email"  class="input-login" placeholder="E-mail" readonly value="<?= $email ?>">
            <label for="Password">Nova Senha: <h6>(8 dígitos, 1 caractere especial, 1 maiúscula, 1 minúscula, 1 número).</h6><h6 style="color:red;">Não preencha senha se não pretende altera-la!</h6></label>
            <input type="password" name="password" id="password" class="input-login" placeholder="Senha">
            <label for="E-mail">Confirmar Nova Senha: </label>
            <input type="password" name="confirm-password" id="confirm-password" class="input-login" placeholder="Confirmar Senha">
            <select name="adm" class="seleciona-adm">
                <option value="0">Selecione</option>
                <option value="1" <?php if($userData->adm == 1){echo "selected";} ?>>Privilégios Administrativos</option>
                <?php if($userSession->adm === 2): ?>
                    <option value="2" <?php if($userData->adm == 2){echo "selected";} ?>>Privilégios Super Administrativos</option>
                <?php endif; ?>
                <option value="0" <?php if($userData->adm == 0){echo "selected";} ?>>Usuário Comum</option>
            </select>
            <div class="container-btns-form">   
                    <button type="submit" class="btn-login btn-edit"><i class="fas fa-user"></i> Alterar Dados de Usuário</button>
        </form>
                <form action="login_process.php" method="post" id="deleteForm">
                    <input type="hidden" name="type" value="delete">
                    <input type="hidden" name="email" value="<?= $email ?>">
                    <button type="button" class="delete-step delete-user" onclick="confirmDelete()"><i class="fas fa-trash"></i> Deletar Usuário</button>
                </form>
            </div> 
        </div>
        
    </div>
    <?php else: ?>
       <?php $message->setMessage("User não encontrado.", "error", "register.php"); ?>       
    <?php endif; ?>
<?php endif; ?>
<script>
  function confirmDelete() {
    // Exibe uma mensagem de confirmação
    if (confirm("Tem certeza de que deseja deletar este usuário?")) {
      // Se o usuário confirmar, envia o formulário
      document.getElementById("deleteForm").submit();
    }
  }
</script>

