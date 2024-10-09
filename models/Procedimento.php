<?php
class Procedimento{
    public $id;
    public $name;
    public $file_procedimento;
    public $fk_id_usuario;

}

interface interfaceProcedimentoDAO{
    public function buildProcedimento($data);
    public function createProcedimento(Procedimento $procedimento);
    public function editProcedimentoById(Procedimento $procedimento);
    public function deleteProcedimentoById($id);
    public function selectProcedimentosByIdUser($id_user);
    public function selectAllProcedimentos();
    public function getLastProcedimento($id_user);
    public function getViewProcedimento();
    public function getProcedimentoById($id_procedimento);
    public function getFileProcedimentoById($id_procedimento);
    public function searchProcedimento($query);
    public function getNameUserCreateProcedure($id_procedure);
}

?>