<?php

namespace App\Models;

use Database\DBConnection;
use PDO;
use DateTime;

abstract class Model
{
    protected $db;
    protected $table;


    //fonction de construction du model 
    public function __construct(DBConnection $db)

    {

        //affectation des donneés de session
        $this->db = $db;
    }

    //fonction d'affichage pour toutes les donneés de la table $table dans la BDD
    public function all(): array

    {

        //resultat de la fonction query a la commande SQL 
        return $this->query("SELECT * FROM {$this->table} ORDER BY id ASC");
    }

    //fonction de recherche des donneés dans la table $table par l'id dans la BDD
    public function findById(int $id): Model

    {

        //resultat de la fonction query a la commande SQL 
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true);
    }

    //fonction de recherche des donneés dans la table $table par l'email dans la BDD
    public function findByEmail(string $email): Model

    {

        //resultat de la fonction query a la commande SQL 
        return $this->query("SELECT * FROM {$this->table} WHERE email = ?", [$email], true);
    }

    //fonction d'execution de la commande SQL
    public function query(string $sql, array $param = null, bool $single = null)
    {

        //fonction ternaire (si le param passé est null method reçois query si non prepare)
        $method = is_null($param) ? 'query' : 'prepare';

        //condiction de verification de la commande SQL si elle contient l'un de ces suite de caractere
        if (
            strpos($sql, 'DELETE') === 0 ||  strpos($sql, 'UPDATE') === 0 || strpos($sql, 'INSERT') === 0
        ) {

            $stmt = $this->db->getPDO()->$method($sql);

            //récupération du résultat dans la classe et que le constructeur soit exécuté  avant que le PDO n'assigne les propriétés de l'objet,
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);

            //appel a la fonction execute pour la requête préparée  
            return $stmt->execute($param);
        }

        //fonction ternaire (si le param passé est null fetch reçois fetchAll si non fetch)
        $fetch = is_null($single) ? 'fetchAll' : 'fetch';

        //appele a la fonction getPDO pour l'interface d'abstraction à l'accès de données
        $stmt = $this->db->getPDO()->$method($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);

        //condiction de verification de la methode

        //si elle est egale a query
        if ($method === 'query') {

            return $stmt->$fetch();

            //si non appel a la fonction execute pour la requête préparée  
        } else {

            $stmt->execute($param);
            return $stmt->$fetch();
        }
    }

    //fonction de modification du format de la date de creation recuperé de la BDD 
    public function getCreatedAt(): string

    {

        return (new DateTime($this->createdAt))->format('d/m/y à H:i');
    }

    //fonction de modification du format de la date de payement recuperé de la BDD 
    public function getPaidAt(): string

    {

        return (new DateTime($this->paidAt))->format('d/m/y à H:i');
    }

    //fonction de modification du format de la date de livraision recuperé de la BDD 
    public function getDeliveredAt(): string

    {

        return (new DateTime($this->deliveredAt))->format('d/m/y à H:i');
    }

    //fonction de recherche du nom du client par son ID dans la BDD
    public function getUserName(int $id): User

    {

        return $this->query("SELECT lastname FROM {$this->table} WHERE id = ?", [$id]);
    }

    //fonction de tronquage d'une chaine de caractere supperieure a 40 caracteres
    public function tronqueChaine(): string

    {

        $chaine = $this->comment;

        //si la chaine est > a 40 caractere
        if (strlen($chaine) > 40) {

            $last_space = strrpos($chaine, 40);
            $chaine = substr($chaine, 0, 40);
            $last_space = strrpos($chaine, 40);
            $chaine = substr($chaine, $last_space) . "...";
        }

        return $chaine;
    }

    //fonction de creation de la commande SQL conforme pour PHPAdmin
    public function create(array $data, ?array $relations = null)
    {

        //initialisasion 1er segment de la commande 
        $firstParenthesis = "";

        //initialisasion 2eme segment de la commande 
        $secondParenthesis = "";

        //compteur
        $i = 1;

        foreach ($data as $key => $value) {

            $comma = $i === count($data) ? "" : ', ';
            $firstParenthesis .= "{$key}{$comma}";
            $secondParenthesis .= ":{$key}{$comma}";
            $i++;
        }

        //appel a la fonction query pour l'execution de la commande SQL
        return $this->query("INSERT INTO {$this->table} ($firstParenthesis) VALUES ($secondParenthesis)", $data);
    }

    //fonction de comptage des données dans une table
    public function countRow()
    {
        return $this->query("SELECT COUNT(*) AS count FROM {$this->table}");
    }

    //fonction de comptage des données dans une table lorsque le role = 0 afin decompte que les client non bani( leurs role =2 )
    public function countRowU()

    {

        return $this->query("SELECT COUNT(*) AS count FROM {$this->table} WHERE role=0");
    }

    //fonction de trie des 4 derniers clients inscrits dans la BDD
    public function lastUser()

    {

        return $this->query("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 4");
    }

    //fonction de trie des 4 derniers commande inscrites dans la BDD
    public function lastOrder()

    {

        return $this->query("SELECT * FROM orderdetail ORDER BY id DESC LIMIT 4");
    }

    //fonction de modification dans la BDD
    public function update(int $id, array $data)

    {

        //initialisasion 1er de la commande 
        $sqlRequestPart = "";

        //compteur
        $i = 1;

        foreach ($data as $key => $value) {

            //fonction ternaire si i egale a count de data alors comma reçois vide si non , 
            $comma = $i === count($data) ? "" : ', ';
            $sqlRequestPart .= "{$key} = :{$key}{$comma}";
            $i++;
        }

        $data['id'] = $id;

        return $this->query("UPDATE {$this->table} SET {$sqlRequestPart} WHERE id = :id", $data);
    }

    //fonction de supression des donneés dans une table par un id
    public function destroy(int $id): bool

    {

        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}
