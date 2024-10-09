<?php

class Passo{
    public $id;
    public $name_passo;
    public $passo;
    public $image_passo;
    public $fk_id_procedimento;

    public function imageGenerateName(){
        return bin2hex(random_bytes(45)) . ".jpg";
    }
    public function fileZipGenerateName(){
        return bin2hex(random_bytes(45)) . ".zip";
    }
    public function fileRarGenerateName(){
        return bin2hex(random_bytes(45)) . ".rar";
    }
}

   

interface PassoDAOInterface{
    public function buildPasso($data);
    public function createPasso(Passo $passo);
    public function findAll();
    public function getPassosByProcedure($id_procedure);
    public function findById($id);
    public function destroy($id_procedure);
    public function editPassoById(Passo $passo);
    public function searchImageById($id_passo);
    public function deletePassoByNameAndFkProcedure($name_passo, $fk_id_procedimento);
    public function getNamePassoById($id_passo);
}

?>