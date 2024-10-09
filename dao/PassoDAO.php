<?php
require_once("models/Passo.php");
class PassoDAO implements PassoDAOInterface{
    
    private $conn;
    private $url;

    public function __construct(PDO $conn, $url){
        $this->conn = $conn;
        $this->url = $url;
    }
    public function buildPasso($data){
        $passo = new Passo();

        $passo->id = $data["id"];
        $passo->name_passo = $data["name_passo"];
        $passo->passo = $data["passo"];
        $passo->image_passo = $data["image_passo"];
        $passo->passo = $data["file_passo"];
        $passo->fk_id_procedimento = $data["fk_id_procedimento"];

        return $passo;
    }

    public function createPasso(Passo $passo){
        $statement = $this->conn->prepare("INSERT INTO passos (name_passo, passo, image_passo, fk_id_procedimento) VALUES(
            :name_passo, 
            :passo, 
            :image_passo,
            :fk_id_procedimento
            )");
        $statement->bindParam(":name_passo", $passo->name_passo);
        $statement->bindParam(":passo", $passo->passo);
        $statement->bindParam(":image_passo", $passo->image_passo);
        $statement->bindParam(":fk_id_procedimento", $passo->fk_id_procedimento);

        $statement->execute();

    }

    public function findAll(){
        $statement = $this->conn->query("SELECT * FROM passos");
        $statement->execute();
        if($statement->rowCount() > 0){

            $passosArray = $statement->fetchAll();
            return $passosArray;

        }else{
            return false;
        }
        
    }

    public function getPassosByProcedure($id_procedure){
        $statement = $this->conn->prepare("SELECT * FROM passos WHERE fk_id_procedimento = :fk_id_procedimento ORDER BY CAST(SUBSTRING(name_passo, 6) AS UNSIGNED)");
        $statement->bindParam(":fk_id_procedimento", $id_procedure);
        $statement->execute();
        if($statement->rowCount() > 0){
            $passosByProcedure = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $passosByProcedure;
        }else{
            return false;
        }
    }

    public function getPassoByNameAndFkProcedure($nome_passo, $id_procedure){
        $statement = $this->conn->prepare("SELECT * FROM passos WHERE fk_id_procedimento = :fk_id_procedimento AND name_passo = :name_passo");
        $statement->bindParam(":fk_id_procedimento", $id_procedure);
        $statement->bindParam(":name_passo", $nome_passo);
        $statement->execute();
        if($statement->rowCount() > 0){
            $passosByProcedure = $statement->fetch(PDO::FETCH_ASSOC);
            return $passosByProcedure;
        }else{
            return false;
        }
    }

    public function findById($id){

    }

    public function destroy($id_procedure){

    }

    public function editPassoById(Passo $passo){
        $statement = $this->conn->prepare("UPDATE passos SET  name_passo = :name_passo, passo = :passo, image_passo = :image_passo WHERE id = :id");
        $statement->bindParam(":id", $passo->id);
        $statement->bindParam(":name_passo", $passo->name_passo);
        $statement->bindParam(":passo", $passo->passo);
        $statement->bindParam(":image_passo", $passo->image_passo);
        $statement->execute();
    }

    public function searchImageById($id_passo){
        $statement = $this->conn->prepare("SELECT image_passo FROM passos WHERE id = :id");
        $statement->bindParam(":id", $id_passo);
        $statement->execute();
        if ($statement->rowCount() > 0){
            $image_passo = $statement->fetchColumn();
            return $image_passo;
        }else{
            $image_passo = false;
            return $image_passo;
        }
    }

    public function deletePassoByNameAndFkProcedure($name_passo, $fk_id_procedimento){
        $statement = $this->conn->prepare("DELETE FROM passos WHERE name_passo = :name_passo and fk_id_procedimento = :fk_id_procedimento");
        $statement->bindParam(":name_passo", $name_passo);
        $statement->bindParam(":fk_id_procedimento", $fk_id_procedimento);
        $statement->execute();
    }
    public function getNamePassoById($id_passo){
        $statement = $this->conn->prepare("SELECT name_passo FROM passos WHERE id = :id");
        $statement->bindParam(":id", $id_passo);
        $statement->execute();
        if ($statement->rowCount() > 0){
            $name_passo = $statement->fetchColumn();
            return $name_passo;
        }else{
            $name_passo = "";
            return $name_passo;
        }
    }
}
?>