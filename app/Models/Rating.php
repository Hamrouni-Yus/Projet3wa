<?php

namespace App\Models;

use App\Models;

class Rating extends Model

{
    //creation d'un variable avec le nom de la table correspondante dans la BDD
    protected $table = 'rating';

    //fonction de creation d'un avis a partir de la classe Model
    public function create(array $data, ?array $relation = null)

    {

        //acces aux propriétés et aux méthodes surchargées de la classe Model
        parent::create($data);
    }
}
