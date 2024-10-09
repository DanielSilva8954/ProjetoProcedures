<!-- Resgatando dados das procedures -->
 
<div class="container-procedimentos">
    <?php if(!empty($procedimentos)): ?>
        <?php foreach($procedimentos as $procedimento): ?>
                <a class="link-procedimento" href="<?= $BASE_URL ?>view_procedimento.php?id=<?= $procedimento["id"] ?>">
                    <div class="info-procedimento">
                        <div class="title-procedimento"><h3><?= $procedimento["name"] ?></h3><h5>ID - <?= $procedimento["id"] ?></h5></div>
                        <div class="data-procedimento"><p><?= $procedimento["date_create"] ?></p></div>
                        <div class="name-user"><p><?= $procedimentoDao->getNameUserCreateProcedure($procedimento["id"]) ?></p></div>
                    </div>
                </a>    
            
        <?php endforeach; ?>
    <?php else: ?>
        <h2>Não encontramos procedimentos aqui...</h2>
    <?php endif; ?>
</div>