<?php
namespace App\Core;

class DB
{
    private ?object $pdo = null;
    private string $table;

    public function __construct()
    {
        //connexion à la bdd via pdo
        try{
            $this->pdo = new \PDO("mysql:host=mariadb;dbname=esgi;charset=utf8", "esgi", "esgipwd");
        }catch (\PDOException $e) {
            echo "Erreur SQL : ".$e->getMessage();
        }

        $table = get_called_class();
        $table = explode("\\", $table);
        $table = array_pop($table);
        $this->table = "esgi_".strtolower($table);
    }

    public function getDataObject(): array
    {
        return array_diff_key(get_object_vars($this), get_class_vars(get_class()));
    }

    public function save()
    {
        $data = $this->getDataObject();
        if( empty($this->getId())){
            $sql = "INSERT INTO " . $this->table . "(" . implode(",", array_keys($data)) . ") 
            VALUES (:" . implode(",:", array_keys($data)) . ")";
        }else{
            $sql = "UPDATE " . $this->table . " SET ";
            foreach ($data as $column => $value){
                $sql.= $column. "=:".$column. ",";
            }
            $sql = substr($sql, 0, -1);
            $sql.= " WHERE id = ".$this->getId();
        }
    }


    // public static function populate(int $id): object
    // {
    //     $class = get_called_class();
    //     $object = new $class();
    //     return $object->getOneBy(["id"=>$id], "object");
    // }
    public static function populate(int $id): ?object
{
    $class = get_called_class();
    $object = new $class();
    $result = $object->getOneBy(["id"=>$id], "object");

    if ($result === null) {
        return null; // Gérer le cas où aucun résultat n'est trouvé
    }

    return $result;
}


    //$data = ["id"=>1] ou ["email"=>"y.skrzypczyk@gmail.com"]
    // public function getOneBy(array $data, string $return = "array")
    // {
    //     $sql = "SELECT * FROM ".$this->table. " WHERE ";
    //     foreach ($data as $column=>$value){
    //         $sql .= " ".$column."=:".$column. " AND";
    //     }
    //     $sql = substr($sql, 0, -3);
    //     $queryPrepared = $this->pdo->prepare($sql);
    //     $queryPrepared->execute($data);
    //     if($return == "object"){
    //         $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
    //     }

    //     return $queryPrepared->fetch();

    // }
    public function getOneBy(array $data, string $return = "array")
    {
        $sql = "SELECT * FROM ".$this->table. " WHERE ";
        foreach ($data as $column=>$value){
            $sql .= " ".$column."=:".$column. " AND";
        }
        $sql = rtrim($sql, " AND");
    
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute($data);
    
        if($return == "object"){
            $queryPrepared->setFetchMode(\PDO::FETCH_CLASS, get_called_class());
        }
    
        $result = $queryPrepared->fetch();
    
        if ($return === "object" && $result === false) {
            return null; // Gérer le cas où aucun résultat n'est trouvé
        }
    
        return $result;
    }
    
}












