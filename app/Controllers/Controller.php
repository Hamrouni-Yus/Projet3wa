<?php

namespace App\Controllers;

use Database\DBConnection;

abstract class Controller
{

    protected $db;

    //fonction de construction pour la creation de session
    public function __construct(DBConnection $db)

    {
        //condiction de verification si un session est deja ouverte
        if (session_status() === PHP_SESSION_NONE) {

            //ouverture de session
            session_start();
        }

        //affectation des donneés de session
        $this->db = $db;
    }

    //fonction de construction de l'URL
    protected function view(string $path, array $params = null)

    {

        //démarrage de la temporisation de sortie
        ob_start();

        //inclusion et exécution du fichier appelé 
        require VIEWS . $path . '.phtml';

        //lecture et affectation decontenu courant du tampon de sortie puis l'efface
        $content = ob_get_clean();

        //inclusion et exécution du fichier appelé
        require VIEWS . 'layout.phtml';
    }

    //fonction d'affectation des données
    protected function getDB()

    {

        return $this->db;
    }

    //fonction de verification du compte administrateur
    protected function isAdmin()

    {

        //condiction de verification du role dans la session currente

        //si $_SESSION['auth'] n'est pas vide et egale a 1 
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === '1') {

            //confirmation
            return true;

            //si non redirection vers la page de connection    
        } else {

            $this->view('auth/login');
            exit();
        }
    }

    //fonction de verification de connection d'un compte client non desactivé
    protected function isConnected()

    {
        //si $_SESSION['auth'] n'est pas vide et egale a 0 
        if (isset($_SESSION['auth']) && $_SESSION['auth'] === '0') {

            return true;

            //si non redirection vers la page de connection
        } else {

            $this->view('auth/login');
            exit();
        }
    }
}
