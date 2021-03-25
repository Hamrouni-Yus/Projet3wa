<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Tva;

class OrdersController extends Controller

{

    //fonction pour afficher toutes les commandes dans la base de donnée
    public function index()

    {

        //verification du role
        $this->isAdmin();

        //appel de la fonction all pour toutes les commandes dans la base de donnée
        $order = (new Order($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('admin/order/index', compact('order'));
    }

    //fonction de modification d'une commande par son ID 
    public function edit(int $id)

    {

        //verification du role
        $this->isAdmin();

        //appel a la fonction findById pour la recherche de la commande choisit
        $order = (new Order($this->getDB()))->findById($id);

        //appel de la fonction all pour toutes les categories dans la base de donnée
        $category = (new Category($this->getDB()))->all();

        //appel a la fonction findById pour la recherche des details de la commande choisit
        $orderdetail = (new OrderDetail($this->getDB()))->findById($order->id);

        //appel a la fonction findById pour la recherche des produits par rapport a ceux present dans les details de la commande choisit
        $product = (new Product($this->getDB()))->findById($orderdetail->product_id);

        //appel a la fonction findById pour la recherche du client qui a passé la commande choisit
        $user = (new User($this->getDB()))->findById($orderdetail->customer_id);

        //appel de la fonction all pour toutes les tarrifs de TVA dans la base de donnée
        $tva = (new Tva($this->getDB()))->all();

        //redirection vers la page de modification avec les resultats obtenus
        return $this->view('admin/order/form', compact('order', 'orderdetail', 'product', 'category', 'user', 'tva'));
    }

    //fonction d'envoie des données modifieés a la BDD
    public function update(int $id)

    {

        //verification du role
        $this->isAdmin();

        //appel a la fonction update pour la modification de la commande dans la BDD
        $order = (new Order($this->getDB()))->update($id, $_POST);

        //redirection vers la page d'affichage de tous les commandes
        header('Location: /projet/admin/order');
        return $this->view('admin/order/index');
    }

    //fonction pour afficher toutes les commandes par son ID
    public function show(int $id)

    {

        //verification du role
        $this->isAdmin();

        //appel de la fonction findById pour la commande choisit
        $order = (new Order($this->getDB()))->findById($id);

        //appel de la fonction findById pour le details de la commande choisit 
        $orderdetail = (new OrderDetail($this->getDB()))->findById($order->id);

        //appel de la fonction findById pour le produit de la commande choisit 
        $product = (new Product($this->getDB()))->findById($orderdetail->product_id);

        //appel de la fonction findById pour le client de la commande choisit 
        $user = (new User($this->getDB()))->findById($orderdetail->customer_id);

        //redirection vers la page d'affichage de la commande choisit
        return $this->view('admin/order/show', compact('order', 'user', 'product', 'orderdetail'));
    }

    //fonction de suppression de categorie par son ID
    public function destroy(int $id)

    {

        //verifation du role
        $this->isAdmin();

        $order = new Order($this->getDB());
        //appel a la fonction destroy pour supprimer la commande par son ID
        $result = $order->destroy($id);

        //redirection vers la page d'affichage de tous les commandes
        header('Location: /projet/admin/order');
        return $this->view('admin/order/index');
    }
}
