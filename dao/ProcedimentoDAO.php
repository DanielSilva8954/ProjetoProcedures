<?php
require_once("models/Procedimento.php");
class ProcedimentoDAO implements interfaceProcedimentoDAO{
    private $conn;
    private $url;

    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
    }

    public function buildProcedimento($data){
        $procedimento = new Procedimento();
        $procedimento->id = $data["id"];
        $procedimento->name = $data["name"];
        $procedimento->file_procedimento = $data["file_procedimento"];
        $procedimento->fk_id_usuario = $data["fk_id_usuario"];

        return $procedimento;
    
    }
    public function createProcedimento(Procedimento $procedimento){
        $statement = $this->conn->prepare("INSERT INTO procedimentos (name, file_procedimento, fk_id_usuario) VALUES (:name, :file_procedimento, :fk_id_usuario)");
        $statement->bindParam(":name", $procedimento->name);
        $statement->bindParam(":file_procedimento", $procedimento->file_procedimento);
        $statement->bindParam(":fk_id_usuario", $procedimento->fk_id_usuario);
        $statement->execute();
    }
    public function editProcedimentoById(Procedimento $procedimento){
        $statement = $this->conn->prepare("UPDATE procedimentos SET name = :name, file_procedimento = :file_procedimento WHERE id = :id");
        $statement->bindParam(":id", $procedimento->id);
        $statement->bindParam(":name", $procedimento->name);
        $statement->bindParam(":file_procedimento", $procedimento->file_procedimento);
        $statement->execute(); 
    }
    public function deleteProcedimentoById($id){
        $statement = $this->conn->prepare("DELETE FROM passos WHERE fk_id_procedimento = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
        $statement = $this->conn->prepare("DELETE FROM procedimentos WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
    }
    public function selectProcedimentosByIdUser($id_user){
        $statement = $this->conn->prepare("SELECT * FROM procedimentos WHERE fk_id_usuario = :fk_id_usuario ORDER BY id DESC");
        $statement->bindParam(":fk_id_usuario", $id_user);
        $statement->execute();
        if($statement->rowCount() > 0){
            $procedimentos = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $procedimentos;
        }else{
            return false;
        }
        
    }
    public function selectAllProcedimentos(){
    
        
    }
    public function getLastProcedimento($id_user){
        $statement = $this->conn->prepare("SELECT * FROM procedimentos WHERE fk_id_usuario = :fk_id_usuario ORDER BY id DESC LIMIT 1");
        $statement->bindParam(":fk_id_usuario", $id_user);
        $statement->execute();
        if($statement->rowCount() > 0){
            $procedimentoArray = $statement->fetch();
            $procedimento = $this->buildProcedimento($procedimentoArray);
            return $procedimento;
        }else{
            return false;
        }
    }

    public function getViewProcedimento(){
        $statement = $this->conn->query("SELECT * FROM procedimentos ORDER BY id desc");
        $statement->execute();
        if($statement->rowCount() > 0){
            $procedimentos = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $procedimentos;
        }else{
            return false;
        }
    }
    
    public function getProcedimentoById($id_procedimento){
        $statement = $this->conn->prepare("SELECT * FROM procedimentos WHERE id = :id");
        $statement->bindParam(":id", $id_procedimento);
        $statement->execute();
        if($statement->rowCount() > 0){
            $procedimentoArray = $statement->fetch();
            $procedimento = $this->buildProcedimento($procedimentoArray);
            return $procedimento;
        }else{
            return false;
        }
    }
    public function getFileProcedimentoById($id_procedimento){
        $statement = $this->conn->prepare("SELECT file_procedimento FROM procedimentos WHERE id = :id");
        $statement->bindParam(":id", $id_procedimento);
        $statement->execute();
        if($statement->rowCount() > 0){
            $file_procedimento = $statement->fetchColumn();
            return $file_procedimento;
        }else{
            return false;
        }
    }

    public function searchProcedimento($query){
        $statement = $this->conn->prepare("SELECT DISTINCT p.* FROM procedimentos AS p LEFT JOIN passos AS ps ON p.id = ps.fk_id_procedimento LEFT JOIN usuarios AS u ON u.id = p.fk_id_usuario WHERE p.name LIKE :query OR ps.passo LIKE :query2 OR u.name LIKE :query3 OR u.lastname LIKE :query4 OR p.id LIKE :query5");
        $statement->bindParam(":query", $query);
        $statement->bindParam(":query2", $query);
        $statement->bindParam(":query3", $query);
        $statement->bindParam(":query4", $query);
        $statement->bindParam(":query5", $query);
        $statement->execute();
        if($statement->rowCount() > 0){
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }else{
            return false;
        }
    }

    public function getNameUserCreateProcedure($id_procedure){
        $statement = $this->conn->prepare("SELECT u.name, u.lastname FROM usuarios AS u LEFT JOIN procedimentos AS p ON u.id = p.fk_id_usuario WHERE p.id = :id_procedure");
        $statement->bindParam(":id_procedure", $id_procedure);
        $statement->execute();
        if($statement->rowCount() > 0){
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $result = $result["name"] . " " . $result["lastname"];
            return $result;
        }else{
            return false;
        }
    }
}

?>