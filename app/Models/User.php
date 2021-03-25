<?php

namespace App\Models;

use App\Models;

class User extends Model

{

    //creation d'un variable avec le nom de la table correspondante dans la BDD
    protected $table = 'user';

    //fonction de creation d'un cleint a partir de la classe Model
    public function create(array $data, ?array $relation = null)

    {

        //acces aux propriétés et aux méthodes surchargées de la classe Model
        parent::create($data);
    }

    //fonction pour la recherche d'un client par son mail dans la BDD
    public function getByEmail(string $email): User

    {

        //resultat de la fonction query a la commande SQL 
        return $this->query("SELECT * FROM {$this->table} WHERE email = ?", [$email], true);
    }

    //fonction pour la recherche d'un client par son ID dans la BDD
    public function getByID(string $id): User

    {

        //resultat de la fonction query a la commande SQL 
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id], true);
    }


    //fonction de hashage du password lors de la creation d'un compte
    public function getPassword(string $password)
    {

        //Creation d'une clé de hachage 
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //Generation d'une valeur de hachage (empreinte numérique)
        return $this->hash;
    }
}
