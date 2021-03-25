<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Validation\Validator;
use App\Models\Rating;
use App\Models\OrderDetail;
use App\Models\User;

class SiteController extends Controller

{

    //fonction d'affichage de la page d'accuil correspondante au role du compte
    public function home()

    {

        //condiction de verification du compte administrateur ou client 

        //si c'est un compte administrateur
        if (isset($_SESSION['auth']) && ($_SESSION['auth']) == 1) {

            //appel de la fonction countRow pour calculer le total des categories dans la BDD
            $category = (new Category($this->getDB()))->countRow();

            //appel de la fonction countRow pour calculer le total des produits dans la BDD
            $product = new Product($this->getDB());
            $products = $product->countRow();


            //appel de la fonction countRowU pour calculer le total des client dans la BDD
            $user = new User($this->getDB());
            $users = $user->countRowU();

            //appel de la fonction lastUser pour afficher les derniers clients enregistres dans la BDD
            $lastUser = $user->lastUser();


            //appel de la fonction countRow pour calculer le total des commandes enregistres dans la BDD
            $orders = new Order($this->getDB());
            $order = $orders->countRow();

            //appel de la fonction countRow pour calculer le total des avis enregistres dans la BDD
            $rating = new Rating($this->getDB());
            $rating = $rating->countRow();

            //appel de la fonction lastOrder pour afficher les dernieres commandes enregistres dans la BDD
            $orderDetail = new OrderDetail($this->getDB());
            $orderDetails = $orderDetail->lastOrder();


            //redirection vers la page d'accuil de l'administrateur avec les resultat
            return $this->view(
                'admin/home',
                compact('category', 'products', 'users', 'lastUser', 'order', 'orderDetails', 'rating')
            );

            //si non c'est compte client
        } else {

            //redirection vers la page d'accuil du client 
            return $this->view('front/home');
        }
    }

    //fonction d'affichage des commandes du client
    public function indexO()

    {

        //verification de la connection
        $this->isConnected();

        //appel de la fonction all pour toutes les commandes dans la base de donnée
        $orders = (new Order($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('front/order/indexOrder', compact('orders'));
    }

    //fonction d'affichage de tous les produits
    public function indexP()

    {

        //appel de la fonction all pour toutes les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //appel de la fonction all pour toutes les produits dans la base de donnée
        $products = (new Product($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('front/product/indexP', compact('products', 'category'));
    }

    //fonction de trie des produits par categorie
    public function indexPC(int $id)

    {

        //appel de la fonction all pour toutes les produits dans la base de donnée
        $products = (new Product($this->getDB()))->all();

        //appel de la fonction findById pour la categorie choisit
        $category = (new Category($this->getDB()))->findById($id);

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('front/product/indexPC', compact('products', 'category'));
    }


    //fonction d'affichage du produit
    public function showP(int $id)

    {

        //appel de la fonction all pour toutes les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //appel de la fonction findById pour le produit choisit
        $product = (new Product($this->getDB()))->findById($id);

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('front/product/showP', compact('product', 'category'));
    }

    //fonction d'affichage des avis
    public function indexR()

    {

        //appel de la fonction all pour toutes les avis dans la base de donnée
        $rating = (new Rating($this->getDB()))->all();

        //appel de la fonction all pour toutes les produits dans la base de donnée
        $product = (new Product($this->getDB()))->all();

        //appel de la fonction all pour toutes les clients dans la base de donnée
        $user = (new User($this->getDB()))->all();


        //retour du resultat obtenu dans la vue correspondante
        return $this->view('front/rating/show', compact('product', 'rating', 'user'));
    }

    //fonction de creation d'un avis
    public function create()

    {
        //condiction de verification de connection 

        //si un compte est connecté
        if ($this->isConnected()) {

            //rediretion vers la vue de d'envoie des données 
            return $this->view('front/rating');

            //si non redirection vers la page de connection
        } else {

            return $this->view('login');
        }
    }

    //fonction de creation d'un avis
    public function createPost()

    {

        //verification de connection
        $this->isConnected();


        //creation de la table d'erreur $_SESSION['errorsUpdate'] pour un nouvel ajout
        $_SESSION['errors'] = [];

        //creation du model de validation des données 
        $validator = new Validator($_POST);

        //appel a la fonction de validation des données envoiés
        $errors = $validator->validate([
            'product_id' => ['required'], 'comment' => ['required']
        ]);

        //condiction de verification des erreurs dans les données envoiés

        //si oui la redirection sur la page precedente et affichage des erreurs
        if ($errors) {
            $_SESSION['errors'][] = $errors;
            return $this->view('rating');

            //si non creation de l'avis dans la base de donnés
        } else {

            //Suppression des balises HTML et PHP
            $_POST['comment'] = strip_tags($_POST['comment']);

            //affectation de l'id du compte connecté a l'id du client de l'avis envoié
            $_POST['customer_id'] = $_SESSION['id'];

            //appel de la fonction create pour la creation de l'avis dans la BDD
            $rating = (new Rating($this->getDB()))->create($_POST);

            //affactation de success pour confirmé l'envoie de l'avis
            $_SESSION['success'] = true;

            //redirection vers la page des avis 
            header('Location: /projet/rating');
            return $this->view('rating');
        }
    }

    //fonction de creation de contact
    public function createContact()

    {

        //condiction de verification de connection

        //si oui affichage de la page de contact
        if ($this->isConnected()) {
            return $this->view('front/contact/contact');

            //si non redirection vers la page de connection
        } else {

            return $this->view('login');
        }
    }

    //fonction d'evoie du formulaire de contact
    public function createPostContact()

    {
        //verification de connection
        $this->isConnected();

        //creation du tableau des services
        $service = ["SAV", "Compte", "Service client", "Autre"];

        //creation du tableau des erreurs
        $errors = [];

        //verification des données envoies 
        if (!array_key_exists('name', $_POST) || ($_POST['name'] == '')) {

            $errors['name'] = "Vous n'avez pas mis votre nom";
        }

        //verification du mail avec filter_var et FILTER_VALIDATE_EMAIL comme paramettre
        if (!array_key_exists('email', $_POST) || ($_POST['email'] == '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))) {

            $errors['email'] = "Vous n'avez pas mis un mail valide";
        }

        if (!array_key_exists('message', $_POST) || ($_POST['message'] == '')) {

            $errors['message'] = "Vous n'avez pas mis votre message";
        }

        if (!array_key_exists('service', $_POST) || (!isset($service[$_POST['service']]))) {

            $errors['service'] = "Le service ne correspond pas";
        }

        //condiction de verification des erreurs

        //si oui la redirection sur la page de contact et affichage des erreurs et les donneés correctes
        if (!empty($errors)) {

            $_SESSION['errors'] = $errors;
            $_SESSION['inputs'] = $_POST;

            return $this->view('front/contact/contact');

            //si non envoie du formalaire
        } else {

            //Suppression des balises HTML et PHP
            $_POST['message'] = strip_tags($_POST['message']);

            //affectation de success pour confirmé l'envoie du formulaire de contact
            $_SESSION['success'] = true;

            //concatination du mail avec FROM:
            $headers = 'FROM: ' . $_POST['email'];

            // Envoi de mail avec la fonction predefinie mail
            mail('yhtechnologie@gmail.com', 'De :' . $_POST['name'], $_POST['message'], $headers);

            //rediraction vers la page de contact
            return $this->view('front/contact/contact');
        }
    }
}
