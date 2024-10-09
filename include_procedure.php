<?php
    include_once("templates/header.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);
?>


<div class="form-container">
    <form action="procedure_process.php" method="POST" id="form-include" enctype="multipart/form-data">
        <input type="hidden" name="type" value="create">
        <div class="title-container">
            <label for="name"><h1 class="label-procedure">INCLUIR PROCEDIMENTO</h1></label><br><br>
            <input type="text" class="title_procedure" name="title_procedure" id="name" autocomplete="off" placeholder="Digite o título do procedimento: "><br><br>
        </div>
        <div class="btn-file-passo">
            <input type="file" name="file-passo" id="file-passo" style="display: none;">
            <button type="button" class="btn-input-image" onclick="clickInputFile('file-passo')"><i class="fas fa-paperclip"></i> Anexe Arquivos Necessários Para Realizar o Procedimento (.ZIP e .RAR)</button>
        </div>
        <div class="step-container">
            <label for="passo1"></label>
            <textarea class="step-text" name="passo1" id="passo1" rows="10" placeholder="Digite o passo..."></textarea>
            <input type="file" name="image1" id="image1" onchange="previewImage(this)" style="display: none;">
            <button type="button" id="input-image" class="btn-input-image" onclick="clickInputFile('image1')"><i class="fas fa-image"></i> Anexar Imagem ao Passo</button>
            <div id="image-preview1" class="image-preview"></div>
        </div>
        <div class="btns-form">
            <button type="button" class="btn-add" onclick="adicionarPasso()"><i class="fas fa-plus"></i> Adicionar Novo Passo</button>
            <button type="submit" class="submit"><i class="fas fa-paper-plane"></i> Enviar Procedimento</button>
        </div>
    </form>

<script src="script.js"></script>

    
</div>
<!-- procedure-process.php -->



<?php
    include_once("templates/footer.php");
?>