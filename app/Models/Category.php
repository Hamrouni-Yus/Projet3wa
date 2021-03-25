<?php

namespace App\Models;

class Category extends Model

{

    //creation d'un variable avec le nom de la table correspondante dans la BDD
    protected $table = 'category';

    //fonction de creation d'une categorie a partir de la classe Model
    public function create(array $data, ?array $relation = null)

    {

        //acces aux propriétés et aux méthodes surchargées de la classe Model
        parent::create($data);
    }
}
