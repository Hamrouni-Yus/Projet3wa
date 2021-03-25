<?php

namespace App\Controllers\Admin;

use App\Validation\Validator;
use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tva;

class ProductController extends Controller

{
    //fonction pour afficher toutes les produits dans la base de donnée
    public function index()

    {
        //verification du role
        $this->isAdmin();

        //appel de la fonction all pour toutes les produits dans la base de donnée
        $product = (new Product($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('admin/product/index', compact('product'));
    }

    //fonction pour cree un nouveau produit dans la base de donnée
    public function create()

    {
        //verification du role 
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];

        //appel de la fonction all pour tous les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //appel de la fonction all pour tous les TVA dans la base de donnée
        $tva = (new Tva($this->getDB()))->all();

        //retour de la vue correspondante
        return $this->view('admin/product/form', compact('category', 'tva'));
    }

    //fonction pour la verification et l'envoie d'un nouveau produit dans la base de donnée
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
            'name' => ['required'], 'description' => ['required'], 'price' => ['required'], 'quantity' => ['required'], 'reference' => ['required'], 'permalink' => ['required'], 'picture' => ['required']
        ]);

        //condiction de verification des erreurs dans les données envoiés

        //si oui la redirection sur la page et affichage des erreurs
        if ($errors) {
            $_SESSION['errors'][] = $errors;
            $category = (new Category($this->getDB()))->all();
            $tva = (new Tva($this->getDB()))->all();
            return $this->view('admin/product/form', compact('category', 'tva'));


            //si non creation des la categorie dans la base de donnés
        } else {

            $product = new Product($this->getDB());

            //appel a la fonction create pour le creation du produit
            $result = $product->create($_POST);

            //affactation de success pour confirmé la creation de la categorie
            $_SESSION['success'] = true;

            //redirection vers la page index 
            header('Location: /projet/admin/product');
            return $this->view('admin/product');
        }
    }

    //fonction d'affichage du produit 
    public function show(int $id)
    {

        //verification du role
        $this->isAdmin();

        //appel de la fonction findById pour le produit choisit
        $product = (new Product($this->getDB()))->findById($id);

        //appel de la fonction all pour tous les categories dans la base de donnée
        $category = (new Category($this->getDB()))->findById($product->category_id);

        //redirection vers la page show avec le resultat obtenu 
        return $this->view('admin/product/show', compact('product', 'category'));
    }

    //fonction de modification d'une categorie par son ID
    public function edit(int $id)

    {

        //verification du role
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];
        //appel a la fonction findById pour la recherche du produit choisit
        $product = (new Product($this->getDB()))->findById($id);

        //appel de la fonction all pour toutes les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //appel de la fonction all pour tous les TVA dans la base de donnée
        $tva = (new Tva($this->getDB()))->all();

        //redirection vers la page de modification avec le resultat obtenu 
        return $this->view('admin/product/form', compact('product', 'category', 'tva'));
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
            'name' => ['required'], 'description' => ['required'], 'price' => ['required'], 'quantity' => ['required'], 'reference' => ['required'], 'permalink' => ['required'], 'picture' => ['required']
        ]);

        //appel a la fonction findById pour la recherche du produit choisit
        $product = (new Product($this->getDB()))->findById($id);

        //appel de la fonction all pour tous les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //appel de la fonction all pour tous les TVA dans la base de donnée
        $tva = (new Tva($this->getDB()))->all();

        //condiction de verification des erreurs dans les données envoiés

        //si oui la rediraction sur la page et affichage des erreurs    
        if ($errorsUpdate) {
            $_SESSION['errorsUpdate'][] = $errorsUpdate;
            return $this->view('admin/product/form', compact('product', 'category', 'tva'));

            //si non modification de la categorie dans la base de donnés
        } else {

            //appel a la fonction update pour la modification du produit dans la BDD
            $result = $product->update($id, $_POST);

            //affactation de success pour confirmé la creation de la categorie
            $_SESSION['success'] = true;

            //redirection vers la page d'affichage de tous les categories
            header('Location: /projet/admin/product');
            return $this->view('admin/product/');
        }
    }

    //fonction de suppression du produit par son ID
    public function destroy(int $id)

    {

        //verifation du role
        $this->isAdmin();

        $product = new Product($this->getDB());

        //appel a la fonction destroy pour supprimer le produit par son ID
        $result = $product->destroy($id);

        //affactation de delete pour confirmé la suppression du produit
        $_SESSION['delete'] = true;

        //redirection vers la page d'affichage de tous les produit
        header('Location: /projet/admin/product');
        return $this->view('admin/product');
    }
}
