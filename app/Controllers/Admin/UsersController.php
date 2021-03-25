<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;
use App\Validation\Validator;

class UsersController extends Controller
{
    //fonction pour afficher toutes les clients dans la base de donnée
    public function index()

    {

        //verification du role 
        $this->isAdmin();

        //appel de la fonction all pour tous les client dans la base de donnée
        $users = (new User($this->getDB()))->all();

        //retour du resultat obtenu dans la vue correspondante
        return $this->view('admin/user/index', compact('users'));
    }

    //fonction pour cree un nouveau client dans la base de donnée
    public function create()

    {

        //verification du role 
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];

        //retour de la vue correspondante
        return $this->view('admin/user/form');
    }

    //fonction pour la verification et l'envoie d'un nouveau client dans la base de donnée
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
            'firstname' => ['required'], 'lastname' => ['required'], 'phone' => ['required'], 'email' => ['required'], 'password' => ['required'], 'quantity' => ['required'], 'shippingAdressLine' => ['required'], 'shippingZipCode' => ['required']
        ]);

        //condiction de verification des erreurs dans les données envoiés

        //si oui la redirection sur la page et affichage des erreurs
        if ($errors) {
            $_SESSION['errors'][] = $errors;
            return $this->view('admin/user/form');

            //si non creation du client dans la base de donnés
        } else {

            $user = new User($this->getDB());
            //appel a la fonction create pour le creation du client
            $result = $user->create($_POST);

            //affactation de success pour confirmé la creation du client
            $_SESSION['success'] = true;

            //redirection vers la page index 
            header('Location: /projet/admin/users');
            return $this->view('admin/user');
        }
    }

    //fonction d'affichage d'un client par son ID
    public function show(int $id)

    {

        //verification du role
        $this->isAdmin();
        //appel de la fonction findById pour le client choisit
        $user = (new User($this->getDB()))->findById($id);

        //redirection vers la page show avec le resultat obtenu 
        return $this->view('admin/user/show', compact('user'));
    }

    //fonction de modification d'un client par son ID
    public function edit(int $id)

    {

        //verification du role
        $this->isAdmin();

        //creation des tables d'erreurs(errorsUpdate pour la modification et errors pour une nouvel ajout)
        $_SESSION['errorsUpdate'] = [];
        $_SESSION['errors'] = [];

        //appel a la fonction findById pour la recherche du client choisit
        $user = (new User($this->getDB()))->findById($id);

        //redirection vers la page de modification avec le resultat obtenu 
        return $this->view('admin/user/form', compact('user'));
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
            'firstname' => ['required'], 'lastname' => ['required'], 'phone' => ['required'], 'email' => ['required'], 'password' => ['required'], 'quantity' => ['required'], 'shippingAdressLine' => ['required'], 'shippingZipCode' => ['required']
        ]);

        //appel a la fonction findById pour la recherche du client choisit
        $user = (new User($this->getDB()))->findById($id);

        //condiction de verification des erreurs dans les données envoiés

        //si oui la rediraction sur la page et affichage des erreurs    
        if ($errorsUpdate) {
            $_SESSION['errorsUpdate'][] = $errorsUpdate;

            return $this->view('admin/user/form', compact('user'));

            //si non modification du client dans la base de donnés

        } else {

            //appel a la fonction update pour la modification du client dans la BDD
            $result = $user->update($id, $_POST);

            //affactation de success pour confirmé la modification de la categorie
            $_SESSION['success'] = true;

            //redirection vers la page d'affichage de tous les clients
            header('Location: /projet/admin/users');
            return $this->view('admin/user');
        }
    }

    //fonction de suppression du client par son ID
    public function destroy(int $id)

    {

        //verifation du role
        $this->isAdmin();

        $user = new User($this->getDB());
        //appel a la fonction destroy pour supprimer le client par son ID
        $result = $user->destroy($id);

        //affactation de delete pour confirmé la suppression de la categorie
        $_SESSION['delete'] = true;

        //redirection vers la page d'affichage de tous les client
        header('Location: /projet/admin/users');
        return $this->view('admin/user');
    }
}
