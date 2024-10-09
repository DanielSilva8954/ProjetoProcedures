<?php
require_once("models/User.php");
require_once("models/Message.php");

class UserDAO implements interfaceUserDAO{
    private $conn;
    private $url;
    private $message;
    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
        $this->message =  new Message($url);
    }

    public function buildUser($data){
        $user = new User();

        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->token = $data["token"];
        $user->adm = $data["adm"];

        return $user;
    }

    public function createUser(User $user, $authUser = false){
        $statement = $this->conn->prepare("INSERT INTO usuarios (name, lastname, email, password, token, adm) VALUES (:name, :lastname, :email, :password, :token, :adm)");
        $statement->bindParam(":name", $user->name);
        $statement->bindParam(":lastname", $user->lastname);
        $statement->bindParam(":email", $user->email);
        $statement->bindParam(":password", $user->password);
        $statement->bindParam(":token", $user->token);
        $statement->bindParam(":adm", $user->adm);
        $statement->execute();

        // Autenticar usuário, caso a auth seja True
        if($authUser){
            $this->setTokenToSession($user->token);
        }
    }

    public function update(User $user, $redirect = true){
        $statement = $this->conn->prepare("UPDATE usuarios SET 
        name = :name,
        lastname = :lastname,
        email = :email,
        password = :password,
        token =:token,
        adm = :adm
        WHERE id = :id
        ");

        $statement->bindParam(":name", $user->name);
        $statement->bindParam(":lastname", $user->lastname);
        $statement->bindParam(":email", $user->email);
        $statement->bindParam(":token", $user->token);
        $statement->bindParam(":id", $user->id);
        $statement->bindParam(":adm", $user->adm);
        $statement->bindParam(":password", $user->password);

        $statement->execute();

        if($redirect){
            // Redireciona para o pefil do usuário
            $this->message->setMessage("Dados atualizados com sucesso!", "success", "register.php");
        }
    }

    public function findByToken($token){
        if($token != ""){
            $statement = $this->conn->prepare("SELECT * FROM usuarios WHERE token = :token");
            $statement->bindParam(":token", $token);
            $statement->execute();
            
            if($statement->rowCount() > 0){
                $data = $statement->fetch(PDO::FETCH_ASSOC);

                $user = $this->buildUser($data);

                return $user;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function verifyToken($protected = false){
        if(!empty($_SESSION["token"])){
            // Pega o token da session
            $token = $_SESSION["token"];
            $user = $this->findByToken($token);
            if($user){
                return $user;
            }else if($protected){
                // Redireciona para a página de login
                $this->message->setMessage("Faça login para acessar essa página!", "error", "index.php");
                return false;
            }
        }elseif($protected){
            // Redireciona para a página de login
            $this->message->setMessage("Faça login para acessar essa página!", "error", "index.php");
            return false;
        }
    }

    public function setTokenToSession($token, $redirect = true){
        // Salvar token na session
        $_SESSION["token"] = $token;
        if($redirect){
            // Redireciona para o pefil do usuário
            $this->message->setMessage("Seja bem-vindo!", "success", "my_procedures.php");
        }
    }

    public function authenticateUser($email, $password){
        $user = $this->findByEmail($email);
        
        if($user){
            //Checar se as senhas batem
            if(password_verify($password, $user->password)){
                // Gerar um token e inserir na sessão
                $token = $user->generateToken();
                $this->setTokenToSession($token, false);

                // Atualizar Token no usuário
                $user->token = $token;
                $this->update($user, false);

                return true;

            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function findByEmail($email){
        
        if($email != ""){
            $statement = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $statement->bindParam(":email", $email);
            $statement->execute();
            
            if($statement->rowCount() > 0){
                $data = $statement->fetch(PDO::FETCH_ASSOC);
                $user = $this->buildUser($data);

                return $user;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function findById($id){

    }

    public function changePassword(User $user){

    }

    public function destroyToken(){
                // Remove o token da sessão
                $_SESSION["token"] = "";

                // Redirecionar e apresentar a mensagem de sucesso
                $this->message->setMessage("Usuário deslogado com sucesso!", "success", "index.php");
    }
    public function checkAdm($id){
        $statement = $this->conn->prepare("SELECT adm FROM usuarios WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
        if($statement->rowCount() > 0){
            $data = $statement->fetchColumn();
            if($data >= 1){
                return true;
            }else{
                return false;
            }
        }
    }
    public function selectAllUsers(){
        $query = $this->conn->query("SELECT id, email FROM usuarios");
        $query->execute();
        if ($query->rowCount() > 0){
            
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
            return $data;

        }
    }
    public function changeProprietary($id, $id_procedure){
        $statement = $this->conn->prepare("UPDATE procedimentos SET fk_id_usuario = :id where id = :id_procedure");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":id_procedure", $id_procedure);
        $statement->execute();
        
    }
    public function deleteByEmail($email){
        
        $statement = $this->conn->prepare("DELETE FROM usuarios WHERE email = :email");
        $statement->bindParam(":email", $email);
        if ($statement->execute()) {
            $this->message->setMessage("Usuário deletado!", "success", "register.php");
        }else{
            $this->message->setMessage("Não foi possivel deletar usuário!", "error", "register.php");
        }
    }
}



?>