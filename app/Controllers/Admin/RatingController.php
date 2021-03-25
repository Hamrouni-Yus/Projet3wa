<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Rating;
use App\Models\Product;
use App\Models\User;

class RatingController extends Controller

{

    //fonction pour afficher toutes les avis dans la base de donnée
    public function index()

    {

        //verification du role 
        $this->isAdmin();

        //appel de la fonction all pour toutes les avis dans la base de donnée
        $rating = (new Rating($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('admin/rating/index', compact('rating'));
    }

    //fonction de modification de l'avis par son ID
    public function edit(int $id)

    {

        //verification du role
        $this->isAdmin();

        //appel a la fonction findById pour la recherche de l'avis choisit
        $rating = (new Rating($this->getDB()))->findById($id);

        //appel a la fonction findById pour la recherche du produit par rapport a l'avis choisit
        $product = (new Product($this->getDB()))->findById($rating->product_id);

        //appel a la fonction findById pour la recherche du client par rapport a l'avis  choisit
        $user = (new User($this->getDB()))->findById($rating->customer_id);

        //redirection vers la page de modification avec le resultat obtenu 
        return $this->view('admin/rating/form', compact('rating', 'product', 'user'));
    }

    //fonction d'envoie des données modifieés a la BDD
    public function update(int $id)
    {

        //verification du role
        $this->isAdmin();

        $rating = new Rating($this->getDB());
        //appel a la fonction update pour la modification de l'avis dans la BDD
        $result = $rating->update($id, $_POST);

        //affactation de success pour confirmé la modification de l'avis
        $_SESSION['success'] = true;

        //redirection vers la page d'affichage de tous les avis
        header('Location: /projet/admin/rating');
        return $this->view('admin/rating');
    }

    //fonction de suppression de l'avis par son ID
    public function destroy(int $id)

    {

        //verifation du role
        $this->isAdmin();

        $rating = new Rating($this->getDB());
        //appel a la fonction destroy pour supprimer la categorie par son ID
        $result = $rating->destroy($id);

        //affactation de delete pour confirmé la suppression de l'avis
        $_SESSION['delete'] = true;

        //redirection vers la page d'affichage de tous les categories
        header('Location: /projet/admin/rating');
        return $this->view('admin/rating');
    }
}
