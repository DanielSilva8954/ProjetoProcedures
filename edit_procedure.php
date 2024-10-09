<?php
    include_once("templates/header.php");
    require_once("dao/ProcedimentoDAO.php");
    require_once("dao/PassoDAO.php");
    require_once("models/User.php");
    require_once("dao/UserDAO.php");
    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken();
    $passoDao = new PassoDAO($conn, $BASE_URL);
    $procedimentoDao = new ProcedimentoDAO($conn, $BASE_URL);

    $userData = $userDao->verifyToken(true);

    $id = filter_input(INPUT_POST, "id");
    $procedimento = $procedimentoDao->getProcedimentoById($id);
    $passos = $passoDao->getPassosByProcedure($procedimento->id);
    $contador = 1;
?>


<div class="form-container">
    <form action="procedure_process.php" method="POST" id="form-include" enctype="multipart/form-data">
        <input type="hidden" name="type" value="edit">
        <input type="hidden" name="id" value="<?= $procedimento->id ?>">
        <div class="title-container">
            <label for="name"><h1 class="label-procedure">EDITAR PROCEDIMENTO</h1></label><br><br>
            <input type="text" class="title_procedure" name="title_procedure" id="name" autocomplete="off" placeholder="Digite o título do procedimento: " value="<?= $procedimento->name ?>"><br><br>
        </div>
        <div class="btn-file-passo">
            <input type="file" name="file-passo" id="file-passo" style="display: none;">
            <button type="button" class="btn-input-image" onclick="clickInputFile('file-passo')"><i class="fas fa-paperclip"></i> Altere os arquivos enviados (.ZIP e .RAR)</button>
        </div>
        <?php foreach ($passos as $passo): ?>
            <div class="step-container" id="step-container-<?= $contador ?>">
                <input type="hidden" name="id-passo<?= $contador ?>" value="<?= $passo["id"] ?>">
                <label for="passo<?= $contador ?>"></label>
                <textarea class="step-text" name="passo<?= $contador ?>" id="passo<?= $contador ?>" rows="10" placeholder="Digite o passo..."><?= str_replace('<br />', "", $passo["passo"]) ?></textarea>
                <input type="file" name="image<?= $contador ?>" id="image<?= $contador ?>" onchange="previewImage(this)" style="display: none;">
                <button type="button" id="input-image" class="btn-input-image" onclick="clickEditFile('image<?= $contador ?>'); removeImageAntiga('image-antiga<?= $contador ?>');"><i class="fas fa-image"></i> Alterar imagem do Passo</button>
                <div id="image-preview<?= $contador ?>" class="image-preview">
                <?php if(!empty($passo["image_passo"])): ?>
                    <img src="./img/passos/<?= $passo["image_passo"] ?>" id="image-antiga<?= $contador ?>" style="max-width: 500px;" alt="Imagem de visualização">
                <?php endif; ?>
                </div>
                <button type="button" onclick="deletarPasso(<?= $contador ?>)" class="delete-step"><i class="fas fa-trash"></i> Deletar Passo</button>
            </div>
        <?php $contador++; ?>
        <?php endforeach; ?>
        <div class="btns-form">
            <input type="hidden" id="contador-edit" value="<?= $contador ?>">
            <button type="button" class="btn-add" onclick="adicionarPassoEdit()"><i class="fas fa-plus"></i> Adicionar Novo Passo</button>
            <button type="submit" class="submit"><i class="fas fa-paper-plane"></i> Editar Procedimento</button>
        </div>
    </form>

<script src="script.js"></script>

    
</div>
<!-- procedure-process.php -->



<?php
    include_once("templates/footer.php");
?>