<?php 
    require_once("config/conn.php");
    require_once("config/globals.php");
    require_once("models/Message.php");
    require_once("models/Passo.php");
    require_once("models/Procedimento.php");
    require_once("models/User.php");
    require_once("dao/PassoDAO.php");
    require_once("dao/ProcedimentoDAO.php");
    require_once("dao/UserDAO.php");
    $message = new Message($BASE_URL);
    $procedimento = new Procedimento();
    $procedimentoDao = new ProcedimentoDAO($conn, $BASE_URL);
    $passo = new Passo();
    $passoDao = new PassoDAO($conn, $BASE_URL);
    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST,"email");
    $password = filter_input(INPUT_POST,"password");
    $confirmpassword = filter_input(INPUT_POST,"confirm-password");
    $token = $user->generateToken();
    $adm = filter_input(INPUT_POST, "adm");

    $userData = $userDao->verifyToken(TRUE);


    $type = filter_input(INPUT_POST,"type");
    
    if ($type === "register") {
        if($confirmpassword === $password) {
            if($userDao->findByEmail($email) === false) {
                if (strlen($password) >= 8 && preg_match('/\d/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[\W_]/', $password)){
                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $user->generatePassword($password);
                    $user->token = $token;
                    $user->adm = $adm;
                    $userDao->createUser($user, False);
                    $message->setMessage("Usuário Criado!", "success", "register.php");
                }else{
                    $message->setMessage("Senha não atende os critérios de segurança!", "error", "register.php");
                }
            }else{
                $message->setMessage("E-mail já está sendo utilizado!", "error", "register.php");
            }    
        }else{
            $message->setMessage("A confirmação de senha deve ver igual a senha!", "error", "register.php");
        }


    }elseif ($type === "login") {

        if($userDao->authenticateUser($email, $password)){
            $message->setMessage("Usuário logado com sucesso!", "success", "my_procedures.php");
        }else{
            $message->setMessage("Usuário ou senha incorretos!", "error", "index.php");
        }
    }elseif ($type === "edit") {
        if($alterUser = $userDao->findByEmail($email)){
            if($alterUser->adm === 0 || $userData->adm === 2){
                if($confirmpassword === $password) {
                    if (strlen($password) >= 8 && preg_match('/\d/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[\W_]/', $password) || $password === ""){
                        $alterUser->name = $name;
                        $alterUser->lastname = $lastname;
                        $alterUser->email = $email;
                        if ($password != "") {
                            $alterUser->password = $alterUser->generatePassword($password);
                        }
                        $alterUser->adm = $adm;
                        $userDao->update($alterUser, True);
                        $message->setMessage("Usuário Editado!", "success", "register.php");
                    }else{
                        $message->setMessage("Senha não atende os critérios de segurança!", "error", "register.php");
                    }  
                }else{
                    $message->setMessage("A confirmação de senha deve ver igual a senha!", "error", "register.php");
                }
            }else{
                $message->setMessage("Você não pode editar um administrador, procure pela equipe de sistemas!", "error", "register.php");
            }
        }else {
            $message->setMessage("Não existe usuário com esse e-mail!", "error", "register.php");
        }
        
    }elseif ($type === "delete") {
        $user = $userDao->findByEmail($email);
        $userAdm = $user->adm;
        $superAdm = 2;
        if ($userData->email != $email) {
            if($userAdm === 0 || $userData->adm === $superAdm){
                $id = $user->id;
                $desvinculaProprietario = 13;
                $procedimentosDoUser = $procedimentoDao->selectProcedimentosByIdUser($id);
                foreach ($procedimentosDoUser as $procedimento) {
                    $idProcedimento = $procedimento["id"];
                    $userDao->changeProprietary($desvinculaProprietario, $idProcedimento);
                }
                $userDao->deleteByEmail($email);
            }else{
                $message->setMessage("Você não pode excluir um usuário administrador!", "error", "register.php");
            }
        }else{
            $message->setMessage("Você não pode excluir seu proprio usuário!", "error", "register.php");
        }

    }


?>