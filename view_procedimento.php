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

// Resgata o procedimento
$idProcedimento = filter_input(INPUT_GET, "id");
$procedimento = $procedimentoDao->getProcedimentoById($idProcedimento);
$passos = $passoDao->getPassosByProcedure($procedimento->id);
$cont = 1;
$download = $procedimento->file_procedimento;
?>

<div class="container-body-procedimento">
    <div class="title-view-procedimento">
        <h1><?= $procedimento->name ?></h1>
    </div>
    <?php if(!empty($procedimento->file_procedimento)): ?>
        <?php if(substr($download, -4) == ".zip"): ?>
            <div class="btn-download-procedimento"><a download="arquivo_procedimento.zip" href="<?= $BASE_URL ?>files/files_procedures/<?= $procedimento->file_procedimento ?>"><button type="button" class="btn-download"><i class="fas fa-download"></i> Baixar Arquivos Necessários</button></a></div>
        <?php else: ?>
            <div class="btn-download-procedimento"><a download="arquivo_procedimento.rar" href="<?= $BASE_URL ?>files/files_procedures/<?= $procedimento->file_procedimento ?>"><button type="button" class="btn-download"><i class="fas fa-download"></i> Baixar Arquivos Necessários</button></a></div>
        <?php endif; ?>
    <?php endif; ?>
    <?php foreach($passos as $passo): ?>
        <div class="passo-container">
            <h3 class="title-passo">Passo <?= $cont ?></h3>
            <p class="text-passo"><?= $passo["passo"] ?></p>
            <?php if(!empty($passo["image_passo"])): ?>
                <img src="<?= $BASE_URL ?>img/passos/<?= $passo["image_passo"] ?>" alt="Imagem Passo <?= $cont ?>" class="img-passo">
            <?php endif; ?>
            <?php $cont++; ?>
        </div>
    <?php endforeach; ?>
    
</div>






<?php
require_once("templates/footer.php");
?>