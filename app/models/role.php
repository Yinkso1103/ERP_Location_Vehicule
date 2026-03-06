<?php
class Role {
    private $id_role;
    private $nom_role;

    public function __construct($id_role, $nom_role) {
        $this->id_role = $id_role;
        $this->nom_role = $nom_role;
    }

    public function getIdRole() {
        return $this->id_role;
    }

    public function getNomRole() {
        return $this->nom_role;
    }

}
?>