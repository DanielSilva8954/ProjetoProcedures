<?php
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
$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);
$procedimentoDao = new ProcedimentoDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);

$id_user = $userData->id;
// RESGATA TIPO DO FORMULÁRIO
$id_procedure = filter_input(INPUT_POST, "id");
$type = filter_input(INPUT_POST, "type");

// CRIAR PROCEDIMENTO
if($type === "create"){
    // Tipos de imagens aceitas
    $imagesAssoc = array_slice($_FILES, 1);
    $images = array_values($imagesAssoc);
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $jpgArray = ["image/jpg", "image/jpeg"];
        foreach($images as $image){
            if(!in_array($image["type"], $imageTypes) && $image["type"] != ""){
                $message->setMessage("São aceitos apenas os formatos de imagens JPG, JPEG e PNG. Tente novamente.", "error", "back");
                $procedimentoDao->deleteProcedimentoById($lastProcedimento->id);exit;
            }
        }

    // Checando se o procedimento possui título
    if(!empty($_POST['title_procedure'])){
        //RESGATANDO DADOS DOS INPUTS DE include_procedure.php
        $procedimento->name = strtoupper(htmlspecialchars(filter_input(INPUT_POST, "title_procedure"), ENT_QUOTES, 'UTF-8'));

        // Recebimento de arquivo .ZIP e .RAR
        $compresedTypes = ["application/x-compressed", "application/x-zip-compressed"];
        if(isset($_FILES["file-passo"])){
            
            $tipoZip = $_FILES["file-passo"]["type"];
            
            if(in_array($tipoZip, $compresedTypes)){
                $uploadDir = "./files/files_procedures/";

                if($_FILES["file-passo"]["type"] == "application/x-zip-compressed"){
                    $nameFile = $passo->fileZipGenerateName();
                }elseif($_FILES["file-passo"]["type"] == "application/x-compressed"){
                    $nameFile = $passo->fileRarGenerateName();
                }
                $fileSave = $uploadDir . DIRECTORY_SEPARATOR . $nameFile;
                $fileCompressed = $_FILES["file-passo"]["tmp_name"];

                // Inserindo o nome do arquivo no objeto para o BD
                $procedimento->file_procedimento = $nameFile;

                // Movendo arquivo para a pasta do BD
                if(!move_uploaded_file($fileCompressed, $fileSave)){
                    $message->setMessage("Falha ao anexar arquivos necessários.", "error", "back");
                }
            }elseif(!empty($tipoZip)){
                $message->setMessage("O arquivo anexado para realizar o procedimento não é .ZIP ou .RAR", "error", "back");exit;
            }
        }

        $procedimento->fk_id_usuario = $id_user;
        $procedimentoDao->createProcedimento($procedimento);

        // RESGATANDO DADOS DO PROCEDIMENTO
        $lastProcedimento = $procedimentoDao->getLastProcedimento($id_user);
        $passo->fk_id_procedimento = $lastProcedimento->id;

        // RESGATANDO OS PASSOS
        $passoSemFiltro = array_slice($_POST, 2);
        $passos = filter_var_array($passoSemFiltro, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keyNamePasso = array_keys($passos);

            // UPLOAD DE IMAGEM
            if(!empty($images)){
                
                $cont = 0;
                $numImage = 1;
                foreach($keyNamePasso as $name_passo){
                    $passo->name_passo = $name_passo;

                    // Verifica se o passo contém conteúdo de texto.
                    if(!empty($passos[$name_passo])){
                        $passo->passo = nl2br(ucfirst($passos[$name_passo]));
                    }else{
                        $message->setMessage("não podem haver passos sem texto.", "error", "back");
                        $procedimentoDao->deleteProcedimentoById($lastProcedimento->id);
                    }
                    
                    

                    // CHECANDO SE EXISTE IMAGEM NO PASSO
                    if(isset($_FILES["image" . $numImage])){
                        // CHECANDO TIPO DA IMAGEM
                        if(in_array($images[$cont]["type"], $imageTypes)){
                            if(in_array($images[$cont]["type"], $jpgArray)){
                                $imageFile = imagecreatefromjpeg($images[$cont]["tmp_name"]);
                            }else{
                                $imageFile = imagecreatefrompng($images[$cont]["tmp_name"]);
                            }

                            $imageName = $passo->imageGenerateName();

                            imagejpeg($imageFile, "./img/passos/" . $imageName, 100);

                            $passo->image_passo = $imageName;
                        
                        // Checando se foi enviado arquivo que não é do formato aceito
                        }elseif($images[$cont]["type"] != "application/x-compressed" && $images[$cont]["type"] != "application/x-zip-compressed" && $images[$cont]["type"] != "image/jpeg" && $images[$cont]["type"] != "image/jpg" && $images[$cont]["type"] != "image/png" && $images[$cont]["type"] != ""){
                            $message->setMessage("São aceitos apenas os formatos de imagens JPG, JPEG e PNG. Tente novamente.", "error", "back");
                            $procedimentoDao->deleteProcedimentoById($lastProcedimento->id);exit;
                        }
                    }
                    $passoDao->createPasso($passo);
                    $passo->image_passo = "";
                    $passo->passo = null;
                    $cont++;
                    $numImage++;
                }
            }
        $message->setMessage("Procedimento incluído com sucesso.", "success", "back");
    }else{
        $message->setMessage("O procedimento deve ter um título.", "error", "back");
    }
    /*______________________________________________________________________________________________________________________________________________________________________ */
}elseif($type === "delete"){
    //Deletando arquivo do procedimento apagado
    $prodecimentoDeletado = $procedimentoDao->getProcedimentoById($id_procedure);
    $caminho_do_arquivo = "./files/files_procedures/" . $prodecimentoDeletado->file_procedimento;
    
    // Verifica se o arquivo do procedimento existe
    if (file_exists($caminho_do_arquivo)) {
        // Tenta excluir o arquivo
        if (unlink($caminho_do_arquivo)) {
        }
    }

    $passosDeletados = $passoDao->getPassosByProcedure($id_procedure);
    foreach ($passosDeletados as $passoDeletado) {
            // Caminho do arquivo que você deseja deletar
        $caminho_do_arquivo = "./img/passos/" . $passoDeletado["image_passo"];
        // Verifica se o arquivo existe
        if (file_exists($caminho_do_arquivo)) {
            // Tenta excluir o arquivo
            if (unlink($caminho_do_arquivo)) {
            }
        }
    }
    $procedimentoDao->deleteProcedimentoById($id_procedure);

    $message->setMessage("Procedimento removido com sucesso.", "success", "back");
    /*______________________________________________________________________________________________________________________________________________________________________ */
}elseif($type === "delegate"){
    $id_user = filter_input(INPUT_POST,"novoProprietario", FILTER_SANITIZE_STRING);
    $id_procedure = filter_input(INPUT_POST,"id", FILTER_SANITIZE_STRING);
    $userDao->changeProprietary($id_user, $id_procedure);
    $message->setMessage("Procedimento delegado com sucesso.", "success", "back");

}elseif($type === "edit"){
    $procedimentoAntesDaEdicao = $procedimentoDao->getProcedimentoById($id_procedure);
    $procedimento->id = $id_procedure;
     // Tipos de imagens aceitas
     $imagesAssoc = array_slice($_FILES, 1);
     $images = array_values($imagesAssoc);
     $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
     $jpgArray = ["image/jpg", "image/jpeg"];
        foreach($images as $image){
            if(!in_array($image["type"], $imageTypes) && $image["type"] != ""){
                $message->setMessage("São aceitos apenas os formatos de imagens JPG, JPEG e PNG. Tente novamente.", "error", "my_procedures.php");
                exit;
            }
        }
    // Checando se o procedimento possui título
    if(!empty($_POST['title_procedure'])){
        //RESGATANDO DADOS DOS INPUTS DE include_procedure.php
        $procedimento->name = strtoupper(htmlspecialchars(filter_input(INPUT_POST, "title_procedure"), ENT_QUOTES, 'UTF-8'));

        // Recebimento de arquivo .ZIP e .RAR
        $compresedTypes = ["application/x-compressed", "application/x-zip-compressed"];
        if(isset($_FILES["file-passo"])){
            
            $tipoZip = $_FILES["file-passo"]["type"];
            
            if(in_array($tipoZip, $compresedTypes)){
                $uploadDir = "./files/files_procedures/";

                if($_FILES["file-passo"]["type"] == "application/x-zip-compressed"){
                    $nameFile = $passo->fileZipGenerateName();
                }elseif($_FILES["file-passo"]["type"] == "application/x-compressed"){
                    $nameFile = $passo->fileRarGenerateName();
                }
                $fileSave = $uploadDir . DIRECTORY_SEPARATOR . $nameFile;
                $fileCompressed = $_FILES["file-passo"]["tmp_name"];
                
                $nameAntigoArquivoProcedimento = $procedimentoAntesDaEdicao->file_procedimento;
                if ($nameFile != "" && $nameFile != $nameAntigoArquivoProcedimento && $nameAntigoArquivoProcedimento != "") {
                    // Verifica se o arquivo do procedimento existe
                    $caminho_do_arquivo = "./files/files_procedures/" . $nameAntigoArquivoProcedimento;
                    if (file_exists($caminho_do_arquivo)) {
                        // Tenta excluir o arquivo
                        if (unlink($caminho_do_arquivo)) {
                        }
                    }
                }
                // Inserindo o nome do arquivo no objeto para o BD
                $procedimento->file_procedimento = $nameFile;

                // Movendo arquivo para a pasta do BD
                if(!move_uploaded_file($fileCompressed, $fileSave)){
                    $message->setMessage("Falha ao anexar arquivos necessários.", "error", "my_procedures.php");
                }
            }elseif(!empty($tipoZip)){
                $message->setMessage("O arquivo anexado para realizar o procedimento não é .ZIP ou .RAR", "error", "my_procedures.php");exit;
            }else{
                $file_procedimento = $procedimentoDao->getFileProcedimentoById($procedimento->id);
                $procedimento->file_procedimento = $file_procedimento;
            }
        }

        $procedimentoDao->editProcedimentoById($procedimento);

        // RESGATANDO DADOS DO PROCEDIMENTO
        $procedimentoEdit = $procedimentoDao->getProcedimentoById($id_procedure);
        $passo->fk_id_procedimento = $procedimentoEdit->id;

        // RESGATANDO OS PASSOS
        $passoSemFiltro = array_slice($_POST, 0);
        $passos = filter_var_array($passoSemFiltro, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keyNamePasso = array_keys($passos);
        // COLETANDO TODOS OS ID'S DE PASSO DO PROCEDIMENTO
            $idPassos = [];
        // COLETANDO TODOS OS PASSOS DO PROCEDIMENTO
            $descPassos = [];
            $passosVinculados = [];
            // UPLOAD DE IMAGEM
            if(!empty($images)){
                
                $cont = 0;
                $numImage = 1;
                $nomePassos = [];
                //percorrendo e achando os nomes dos passos
                foreach($keyNamePasso as $keyPasso){
                        if(strstr($keyPasso, "id-passo")){
                            $idPassos[] = $passos[$keyPasso];
                        }elseif(strstr($keyPasso, "passo") && !strstr($keyPasso, "id-passo") ){
                            $descPassos[] = $passos[$keyPasso];
                            $nomePassos[] = $keyPasso;
                        }
                        
                }

                $keyIdPassos = array_keys($idPassos);
                foreach($keyIdPassos as $keyIdPasso){
                    $passosVinculados[$idPassos[$keyIdPasso]] = $descPassos[$keyIdPasso];
                }
                //PASSOS QUE DEVEM SER EDITADOS:
                $imageContador = 1;
                $cont = 0;
                $deleteContador = count($passoDao->getPassosByProcedure($id_procedure));
                for($i = 1; $i <= $deleteContador;$i++){
                    // DELETA PASSOS
                    $verificaPassoDeletado = "passo" . $i;
                    if (!in_array($verificaPassoDeletado, $nomePassos)) {
                        //Deletando imagem do passo 
                        $passoDeleteImage = $passoDao->getPassoByNameAndFkProcedure($verificaPassoDeletado, $id_procedure);
                        
                        // Caminho do arquivo que você deseja deletar
                        $caminho_do_arquivo = "./img/passos/" . $passoDeleteImage["image_passo"];

                        // Verifica se o arquivo existe
                        if (file_exists($caminho_do_arquivo)) {
                            // Tenta excluir o arquivo
                            if (unlink($caminho_do_arquivo)) {
                            }
                        }
                        //Deletando passo do BD
                        $passoDao->deletePassoByNameAndFkProcedure($verificaPassoDeletado, $id_procedure);
                        
                    }
                }
                foreach($passosVinculados as $id => $passoAtual){
                    
                    // Verifica se o passo contém conteúdo de texto.
                    if(!empty($passosVinculados[$id])){
                        $textPasso = ucfirst($passosVinculados[$id]);
                        $passo->passo = nl2br($textPasso);
                    }else{
                        $message->setMessage("Não podem haver passos sem texto.", "error", "my_procedures.php");exit;
                    }
                    
                    

                    // CHECANDO SE EXISTE IMAGEM NO PASSO
                    if(!empty($_FILES["image" . $imageContador]["name"])){
                        // CHECANDO TIPO DA IMAGEM
                        if(in_array($images[$cont]["type"], $imageTypes)){
                            if(in_array($images[$cont]["type"], $jpgArray)){
                                $imageFile = imagecreatefromjpeg($images[$cont]["tmp_name"]);
                            }else{
                                $imageFile = imagecreatefrompng($images[$cont]["tmp_name"]);
                            }

                            $imageName = $passo->imageGenerateName();
                            $oldImage = $passoDao->searchImageById($id);
                            if ($oldImage != false && $imageName != $oldImage) {
                                //Deletando imagem do passo 
                                // Caminho do arquivo que você deseja deletar
                                $caminho_do_arquivo = "./img/passos/" . $oldImage;

                                // Verifica se o arquivo existe
                                if (file_exists($caminho_do_arquivo)) {
                                    // Tenta excluir o arquivo
                                    if (unlink($caminho_do_arquivo)) {
                                    }
                                }
                            }
                            
                            imagejpeg($imageFile, "./img/passos/" . $imageName, 100);
                            $passo->image_passo = $imageName;
                        // Checando se foi enviado arquivo que não é do formato aceito
                        }elseif($images[$cont]["type"] != "application/x-compressed" && $images[$cont]["type"] != "application/x-zip-compressed" && $images[$cont]["type"] != "image/jpeg" && $images[$cont]["type"] != "image/jpg" && $images[$cont]["type"] != "image/png" && $images[$cont]["type"] != ""){
                            $message->setMessage("São aceitos apenas os formatos de imagens JPG, JPEG e PNG. Tente novamente.", "error", "my_procedures.php");
                            $procedimentoDao->deleteProcedimentoById($lastProcedimento->id);exit;
                        }
                    }else{
                        $image_name = $passoDao->searchImageById($id);
                        $passo->image_passo = $image_name;
                    }
                    $passo->id = $id;
                    $passo->name_passo = "passo" . $imageContador;
                    $passoDao->editPassoById($passo);
                    $passo->image_passo = "";
                    $passo->passo = null;
                    $cont++;
                    $imageContador++;
                    
                }
                    //PASSOS QUE DEVEM SER ADICIONADOS
                    $passosNovos = array_slice($descPassos,$cont);
                    if(!empty($passosNovos)){
                        foreach($passosNovos as $passoAtual){
                            /*___________________________________________________________________________________________________________________________________________________________*/
                                    // Verifica se o passo contém conteúdo de texto.
                                    if(!empty($passoAtual)){
                                        $passo->passo = nl2br(ucfirst($passoAtual));
                                    }else{
                                        $message->setMessage("não podem haver passos sem texto.", "error", "my_procedures.php");exit;
                                    }
                                    
                                    

                                    // CHECANDO SE EXISTE IMAGEM NO PASSO
                                    if(isset($_FILES["image" . $imageContador])){
                                        // CHECANDO TIPO DA IMAGEM
                                        if(in_array($images[$cont]["type"], $imageTypes)){
                                            if(in_array($images[$cont]["type"], $jpgArray)){
                                                $imageFile = imagecreatefromjpeg($images[$cont]["tmp_name"]);
                                            }else{
                                                $imageFile = imagecreatefrompng($images[$cont]["tmp_name"]);
                                            }

                                            $imageName = $passo->imageGenerateName();

                                            imagejpeg($imageFile, "./img/passos/" . $imageName, 100);

                                            $passo->image_passo = $imageName;
                                        
                                        // Checando se foi enviado arquivo que não é do formato aceito
                                        }elseif($images[$cont]["type"] != "application/x-compressed" && $images[$cont]["type"] != "application/x-zip-compressed" && $images[$cont]["type"] != "image/jpeg" && $images[$cont]["type"] != "image/jpg" && $images[$cont]["type"] != "image/png" && $images[$cont]["type"] != ""){
                                            $message->setMessage("São aceitos apenas os formatos de imagens JPG, JPEG e PNG. Tente novamente.", "error", "my_procedures.php");
                                            $procedimentoDao->deleteProcedimentoById($lastProcedimento->id);exit;
                                        }
                                    }
                                    $passo->name_passo = "passo" . $imageContador;
                                    $passoDao->createPasso($passo);
                                    $passo->image_passo = "";
                                    $passo->passo = null;
                                    $cont++;
                                    $imageContador++;
                                }
                            }
                            /*___________________________________________________________________________________________________________________________________________________________*/
                        }
                        $message->setMessage("Procedimento Editado com sucesso.", "success", "my_procedures.php");
                    }else{
                        $message->setMessage("O procedimento deve ter um título.", "error", "my_procedures.php");
                    }
            }
            
                
            

    
        





?>