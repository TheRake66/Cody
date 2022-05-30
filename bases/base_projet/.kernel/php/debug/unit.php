<?php
namespace Kernel\Debug;



/**
 * Librairie gerant les tests unitaire
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Debug
 * @category Framework source
 * @copyright (c) 2022, Thibault Bustos
 */
abstract class Unit {

    /**
     * Termine le test avec un code de sortie et un message d'erreur
     * 
     * @param string le message
     * @return void
     */
    protected function fail($message = null) {
        if (!is_null($message)) {
            echo $message;
        }
        exit(1);
    }


    /**
     * Verifie si une valeur est fausse
     * 
     * @param bool la valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function false($bool, $message = 'Doit être faux.') {
        if (!is_bool($bool) || $bool) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur est vraie
     * 
     * @param bool la valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function true($bool, $message = 'Doit être vrai.') {
        if (!is_bool($bool) || !$bool) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur est null
     * 
     * @param mixed la valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function null($mixed, $message = 'Doit être null.') {
        if (!is_null($mixed)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur est pas null
     * 
     * @param mixed la valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notNull($mixed, $message = 'Ne doit pas être null.') {
        if (is_null($mixed)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur a un type precis
     * 
     * @param mixed la valeur a verifier
     * @param string le type requis
     * @param string le message en cas d'echec
     * @return void
     */
    protected function type($mixed, $type, $message = 'N\'est pas du type requis.') {
        if (gettype($mixed) != $type) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur n'est pas d'un type precis
     * 
     * @param mixed la valeur a verifier
     * @param string le type indesirable
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notType($mixed, $type, $message = 'Est du type indesirable.') {
        if (gettype($mixed) == $type) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si un objet fait partie du classe precise
     * 
     * @param object l'objet a verifier
     * @param string la classe requise
     * @param string le message en cas d'echec
     * @return void
     */
    protected function class($object, $class, $message = 'N\'est pas de la bonne classe.') {
        if (get_class($object) != $class) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si un objet ne fait pas partie du classe precise
     * 
     * @param object l'objet a verifier
     * @param string la classe indesirable
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notClass($object, $class, $message = 'Est de la classe indesirable.') {
        if (get_class($object) == $class) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux valeurs sont egales. Si ces valeurs sont des objets, la
     * comparaison se fera sur les proprietes et la classe
     * 
     * @param object la premiere valeur a verifier
     * @param object la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function assertEquals($object1, $object2, $message = 'Doit être égales.') {
        if ($object1 != $object2) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux valeurs sont differentes. Si ces valeurs sont des objets, la
     * comparaison se fera sur les proprietes et la classe
     * 
     * @param object la premiere valeur a verifier
     * @param object la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notEquals($object1, $object2, $message = 'Ne doit pas être égales.') {
        if ($object1 == $object2) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux objets ont les memes references
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function same($object1, $object2, $message = 'Doit être identique.') {
        if ($object1 !== $object2) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux objets ont des references differentes
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notSame($object1, $object2, $message = 'Ne doit pas être identique.') {
        if ($object1 === $object2) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux objets font partie de la meme classe
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function sameClass($object1, $object2, $message = 'Doit être de la meme clase.') {
        if (get_class($object1) != get_class($object2)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux objets ne font pas partie de la meme classe
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notSameClass($object1, $object2, $message = 'Ne doit pas être de la meme clase.') {
        if (get_class($object1) == get_class($object2)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux valeur ont le meme type
     * 
     * @param mixed la premiere valeur a verifier
     * @param mixed la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function sameType($mixed1, $mixed2, $message = 'Doit être du meme type.') {
        if (gettype($mixed1) != gettype($mixed2)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux valeur ont un type different
     * 
     * @param mixed la premiere valeur a verifier
     * @param mixed la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notSameType($mixed1, $mixed2, $message = 'Ne doit pas être du meme type.') {
        if (gettype($mixed1) == gettype($mixed2)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur est dans une liste
     * 
     * @param mixed l'objet a verifier
     * @param array la liste a verifier
     * @param bool si on verifie les types
     * @param string le message en cas d'echec
     * @return void
     */
    protected function inArray($mixed, $array, $checktype = false, $message = 'Doit être dans la liste.') {
        if (!in_array($mixed, $array, $checktype)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si une valeur n'est pas dans une liste
     * 
     * @param mixed l'objet a verifier
     * @param array la liste a verifier
     * @param bool si on verifie les types
     * @param string le message en cas d'echec
     * @return void
     */
    protected function notInArray($mixed, $array, $checktype = false, $message = 'Ne doit pas être dans la liste.') {
        if (in_array($mixed, $array, $checktype)) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux listes contiennent les memes valeurs
     * 
     * @param array la premiere liste a verifier
     * @param array la deuxieme liste a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function arraySameValues($array1, $array2, $message = 'Doivent avoir les même valeurs.') {
        sort($array1);
        sort($array2);
        if ($array1 != $array2) {
            $this->fail($message);
        }
    }


    /**
     * Verifie si deux listes contiennent des valeurs differentes
     * 
     * @param array la premiere liste a verifier
     * @param array la deuxieme liste a verifier
     * @param string le message en cas d'echec
     * @return void
     */
    protected function arrayNotSameValues($array1, $array2, $message = 'Ne doivent pas avoir les même valeurs.') {
        sort($array1);
        sort($array2);
        if ($array1 == $array2) {
            $this->fail($message);
        }
    }
    
}

?>