<div class="container-procedimentos">
    <?php if (!empty($procedimentos)): ?>
        <?php $contador = 1; ?>
        <?php foreach ($procedimentos as $procedimento): ?>
            <a class="link-procedimento" href="<?= $BASE_URL ?>view_procedimento.php?id=<?= $procedimento["id"] ?>">
                <div class="info-procedimento">
                    <div class="title-procedimento"><h3><?= $procedimento["name"] ?></h3><h5>ID - <?= $procedimento["id"] ?></h5></div>
                    <div class="data-procedimento"><p><?= $procedimento["date_create"] ?></p></div>
                    <div class="name-user"><?= $procedimentoDao->getNameUserCreateProcedure($procedimento["id"]) ?></div>
                
                    <form action="edit_procedure.php" method="POST">
                        <div class="manutencao-procedure">
                            <input type="hidden" name="id" value="<?= $procedimento["id"] ?>">
                            <button class="edit-procedure" type="submit"><i class="fas fa-edit"></i> Editar</button>
                        </div>
                    </form>
                    <form action="procedure_process.php" method="post" id="<?= $procedimento["id"] ?>">
                        <input type="hidden" name="id" value="<?= $procedimento["id"] ?>">
                        <button class="delete-procedure" type="submit" name="type" value="delete"><i class="fas fa-trash"></i> Deletar</button>
                    </form>
                </a>

            <?php if ($adm): ?>
                <!-- Botão para abrir o modal, com ID único -->
                <button class="delegate-procedure" id="alterarProprietarioBtn<?= $contador ?>"><i class="fas fa-random"></i> Alterar Proprietário</button>

            <?php endif; ?>
            </div>
            <!-- Modal com ID único -->
            <div id="meuModal<?= $contador ?>" class="modal">
                <div class="modal-content">
                    <span class="close" id="fecharModal<?= $contador ?>">&times;</span>
                    <h2>Selecione o Novo Proprietário</h2>

                    <!-- Lista de seleção de nomes -->
                    <form action="procedure_process.php" method="POST">
                        <input type="hidden" name="type" value="delegate">
                        <input type="hidden" name="id" value="<?= $procedimento["id"] ?>">
                        <label for="proprietario">Nome:</label>
                        <select id="proprietario" name="novoProprietario">
                            <?php foreach($dataUsers as $dataUser): ?>
                            <option value="<?= $dataUser["id"] ?>"><?= $dataUser["email"] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit">Confirmar Alteração</button>
                    </form>
                </div>
            </div>

            <script>
                // Obtém o modal específico
                var modal<?= $contador ?> = document.getElementById("meuModal<?= $contador ?>");

                // Obtém o botão que abre o modal específico
                var btn<?= $contador ?> = document.getElementById("alterarProprietarioBtn<?= $contador ?>");

                // Obtém o <span> que fecha o modal específico
                var span<?= $contador ?> = document.getElementById("fecharModal<?= $contador ?>");

                // Quando o usuário clicar no botão, abre o modal específico
                btn<?= $contador ?>.onclick = function() {
                    modal<?= $contador ?>.style.display = "block";
                }

                // Quando o usuário clicar no <span> (x), fecha o modal específico
                span<?= $contador ?>.onclick = function() {
                    modal<?= $contador ?>.style.display = "none";
                }

                // Quando o usuário clicar fora do modal, fecha o modal específico
                window.onclick = function(event) {
                    if (event.target == modal<?= $contador ?>) {
                        modal<?= $contador ?>.style.display = "none";
                    }
                }
            </script>

            <?php $contador++; ?>

        <?php endforeach; ?>
    <?php else: ?>
        <h2>Você ainda não adicionou procedimentos.</h2>
        <p>Para adicionar um procedimento clique <a href="<?= $BASE_URL ?>include_procedure.php">aqui.</a></p>
    <?php endif; ?>
</div>
