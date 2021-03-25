<?php

namespace App\Validation;

class Validator
{

    private $data;
    private $errors;

    //fonction de construction du model 
    public function __construct(array $data)

    {

        $this->data = $data;
    }

    //fonction de validation
    public function validate(array $rules): ?array

    {

        foreach ($rules as $name => $rulesArray) {

            if (array_key_exists($name, $this->data)) {

                foreach ($rulesArray as $rule) {

                    switch ($rule) {

                        case 'required':

                            $this->required($name, $this->data[$name]);
                            break;

                        case substr($rule, 0, 3) === 'min':

                            $this->min($name, $this->data[$name], $rule);
                            break;

                        default:

                            break;
                    }
                }
            }
        }


        return $this->getErrors();
    }

    //fontion de requis des données
    private function required(string $name, string $value)

    {

        $value = trim($value);

        if (!isset($value) || is_null($value) || empty($value)) {

            $this->errors[$name][] = "{$name} est requis.";
        }
    }

    //fonction de la regle minimum de caracteres
    private function min(string $name, string $value, string $rule)

    {

        preg_match_all('/(\d+)/', $rule, $matches);
        $limit = (int) $matches[0][0];

        if (strlen($value) < $limit) {

            $this->errors[$name][] = "{$name} doit comprendre un minimum de {$limit} caractères";
        }
    }

    //fonction de recuperation des erreurs
    private function getErrors(): ?array

    {

        return $this->errors;
    }
}
