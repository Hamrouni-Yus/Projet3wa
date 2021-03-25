<?php

namespace App\Controllers\Admin;

use App\Validation\Validator;
use App\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{

    //fonction pour afficher toutes les categories dans la base de donnée
    public function index()

    {
        //verification du role 
        $this->isAdmin();

        //appel de la fonction all pour toutes les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('admin/category/index', compact('category'));
    }

    //fonction pour cree une nouvelle categories dans la base de donnée
    public function create()

    {
        //verification du role 
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];

        //retour de la vue correspondante
        return $this->view('admin/category/form');
    }

    //fonction pour la verification et l'envoie d'une nouvelle categories dans la base de donnée
    public function createPost()

    {

        //verification du role
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];

        //creation du model de validation des données
        $validator = new Validator($_POST);

        //appel a la fonction de validation des données envoiés
        $errors = $validator->validate([
            'title' => ['required'], 'description' => ['required'], 'picture' => ['required'], 'permalink' => ['required']
        ]);

        //condiction de verification des erreurs dans les données envoiés

        //si oui la redirection sur la page et affichage des erreurs
        if ($errors) {
            $_SESSION['errors'][] = $errors;
            $category = new Category($this->getDB());
            return $this->view('admin/category/form', compact('category'));

            //si non creation de la categorie dans la base de donnés
        } else {

            $category = new Category($this->getDB());

            //appel a la fonction create pour le creation de la categorie
            $result = $category->create($_POST);

            //affactation de success pour confirmé la creation de la categorie
            $_SESSION['success'] = true;

            //redirection vers la page index 
            header('Location: /projet/admin/category');
            return $this->view('admin/category');
        }
    }

    //fonction de trie des produits par categorie
    public function show(int $id)

    {

        //verification du role
        $this->isAdmin();

        //appel de la fonction findById pour la categorie choisit
        $category = (new Category($this->getDB()))->findById($id);

        //appel de la fonction all pour tous les produits dans la base de donnée
        $products = (new Product($this->getDB()))->all();

        //redirection vers la page show avec le resultat obtenu 
        return $this->view('admin/category/show', compact('products', 'category'));
    }

    //fonction de modification d'une categorie par son ID
    public function edit(int $id)

    {

        //verification du role
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];

        //appel a la fonction findById pour la recherche de la categorie choisit
        $category = (new Category($this->getDB()))->findById($id);

        //redirection vers la page de modification avec le resultat obtenu 
        return $this->view('admin/category/form', compact('category'));
    }

    //fonction d'envoie des données modifieés a la BDD
    public function update(int $id)

    {

        //verification du role
        $this->isAdmin();

        //creation la table d'erreur errorsUpdate pour la modification
        $_SESSION['errorsUpdate'] = [];

        //creation du model de validation des données
        $validator = new Validator($_POST);

        //appel a la fonction de validation des données envoiés
        $errorsUpdate = $validator->validate([
            'title' => ['required'], 'description' => ['required'], 'picture' => ['required'], 'permalink' => ['required']
        ]);

        //appel a la fonction findById pour la recherche de la categorie choisit
        $category = (new Category($this->getDB()))->findById($id);

        //condiction de verification des erreurs dans les données envoiés

        //si oui la rediraction sur la page et affichage des erreurs    
        if ($errorsUpdate) {
            $_SESSION['errorsUpdate'][] = $errorsUpdate;

            return $this->view('admin/category/form', compact('category'));

            //si non modification de la categorie dans la base de donnés
        } else {

            //appel a la fonction update pour la modification de la categorie dans la BDD
            $result = $category->update($id, $_POST);

            //affactation de success pour confirmé la modification de la categorie
            $_SESSION['success'] = true;

            //redirection vers la page d'affichage de tous les categories
            header('Location: /projet/admin/category');
            return $this->view('admin/category');
        }
    }

    //fonction de suppression de categorie par son ID
    public function destroy(int $id)

    {

        //verifation du role
        $this->isAdmin();

        $category = new Category($this->getDB());
        //appel a la fonction destroy pour supprimer la categorie par son ID
        $result = $category->destroy($id);

        //affactation de delete pour confirmé la suppression de la categorie
        $_SESSION['delete'] = true;

        //redirection vers la page d'affichage de tous les categories
        header('Location: /projet/admin/category');
        return $this->view('admin/category/');
    }
}
