<?php

namespace App\Controllers;

use App\Models\User;
use App\Validation\Validator;

class UserController extends Controller
{

    //fonction de retour de la page connection
    public function login()
    {
        $_SESSION['errorsUpdate'] = null;
        return $this->view('auth/login');
    }

    //fonction d'envoi des données de connection
    public function loginPost()
    {

        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            if ($user = (new User($this->getDB()))->getByEmail($_POST['email'])) {
                if (password_verify($_POST['password'], $user->password)) {
                    if ($user->role == '1') {
                        $_SESSION['auth'] = $user->role;
                        $_SESSION['lastname'] = $user->lastname;
                        $isValid = true;
                        $_SESSION['verif'] = $isValid;
                        $_SESSION['success'] = $isValid;
                        $_SESSION['delete'] = false;
                        header('Location: admin/home');
                        return $this->view('/admin/home');
                    } elseif ($user->role == '0') {
                        $_SESSION['auth'] = $user->role;
                        $_SESSION['lastname'] = $user->lastname;
                        $_SESSION['id'] = $user->id;
                        $_SESSION['success'] = true;
                        $_SESSION['connected'] = true;
                        $_SESSION['delete'] = false;
                        header('Location: home');
                        return $this->view('/front/home');
                    } else {
                        return $this->view('/front/ban');
                    }
                }
            }
        }

        $validator = new Validator($_POST);
        $errors = $validator->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        $isValid = false;
        $_SESSION['verif'] = $isValid;
        $_SESSION['connected'] = $isValid;
        $_SESSION['errors'][] = $errors;
        header('Location: /projet/login');
        return $this->view('auth/login');
    }

    //fonction de retour de la page d'inscription
    public function signin()
    {
        $_SESSION['errors'] = null;
        return $this->view('auth/signin');
    }
    //fonction d'envoi des données de connection
    public function signinPost()
    {

        $validator = new Validator($_POST);
        $errorsUpdate = $validator->validate([
            'firstname' => ['required'],
            'lastname' => ['required'],
            'phone' => ['required'],
            'email' => ['required'],
            'password' => ['required']
        ]);

        if ($errorsUpdate) {
            $_SESSION['errorsUpdate'][] = $errorsUpdate;
            header('Location: /projet/signin');
            return $this->view('auth/signin');
        } else {
            $user = new User($this->getDB());
            $userData = $user->all();
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $_POST['password'] = $passwordHash;
            $result = $user->create($_POST);
            $_SESSION['auth'] = $user->role;
            $_SESSION['lastname'] = $user->lastname;
            header('Location: /projet/home');
            return $this->view('/projet/home');
        }
    }


    //fonction de deconnection
    public function logout()
    {
        session_destroy();
        header('Location: /projet/login');
        session_destroy();
        return $this->view('auth/login');
    }
}
