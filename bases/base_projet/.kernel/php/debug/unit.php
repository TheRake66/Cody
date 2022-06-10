<?php
namespace Kernel\Debug;



/**
 * Librairie gérant les tests unitaires.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Debug
 * @category Framework source
 * @copyright (c) 2022, Thibault Bustos
 */
abstract class Unit {

    /**
     * Termine le test avec un code de sortie et un message d'erreur.
     * 
     * @param string $message Le message d'erreur.
     * @return void
     */
    protected function fail($message = null) {
        if (!is_null($message)) {
            echo $message;
        }
        exit(1);
    }


    /**
     * Vérifie si une valeur est fausse.
     * 
     * @param bool $bool La valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function false($bool, $message = 'Doit être faux.') {
        if (!is_bool($bool) || $bool) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur est vraie.
     * 
     * @param bool $bool La valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function true($bool, $message = 'Doit être vrai.') {
        if (!is_bool($bool) || !$bool) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur est NULL.
     * 
     * @param mixed $mixed La valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function null($mixed, $message = 'Doit être null.') {
        if (!is_null($mixed)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur est pas NULL.
     * 
     * @param mixed $mixed La valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notNull($mixed, $message = 'Ne doit pas être null.') {
        if (is_null($mixed)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur a un type précis.
     * 
     * @param mixed $mixed La valeur à vérifier.
     * @param string $type Le type à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function type($mixed, $type, $message = 'N\'est pas du type requis.') {
        if (gettype($mixed) != $type) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur n'est pas d'un type précis.
     * 
     * @param mixed $mixed La valeur à vérifier.
     * @param string $type Le type à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notType($mixed, $type, $message = 'Est du type indesirable.') {
        if (gettype($mixed) == $type) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si un objet fait partie du classe précise.
     * 
     * @param object $object L'objet à vérifier.
     * @param string $class La classe à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function class($object, $class, $message = 'N\'est pas de la bonne classe.') {
        if (get_class($object) != $class) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si un objet ne fait pas partie du classe précise.
     * 
     * @param object $object L'objet à vérifier.
     * @param string $class La classe à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notClass($object, $class, $message = 'Est de la classe indésirable.') {
        if (get_class($object) == $class) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux valeurs sont égales. Si ces valeurs sont des objets, la comparaison se fera sur les propriétés de la classe.
     * 
     * @param object $object1 La première valeur à comparer.
     * @param object $object2 La deuxième valeur à comparer.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function assertEquals($object1, $object2, $message = 'Doit être égales.') {
        if ($object1 != $object2) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux valeurs sont différentes. Si ces valeurs sont des objets, la comparaison se fera sur les propriétés de la classe.
     * 
     * @param object $object1 La première valeur à comparer.
     * @param object $object2 La deuxième valeur à comparer.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notEquals($object1, $object2, $message = 'Ne doit pas être égales.') {
        if ($object1 == $object2) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux objets ont les mêmes références.
     * 
     * @param object $object1 La première valeur à vérifier.
     * @param object $object2 La deuxième valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function same($object1, $object2, $message = 'Doit être identique.') {
        if ($object1 !== $object2) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux objets ont des références différentes.
     * 
     * @param object $object1 La première valeur à vérifier.
     * @param object $object2 La deuxième valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notSame($object1, $object2, $message = 'Ne doit pas être identique.') {
        if ($object1 === $object2) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux objets font partie de la même classe.
     * 
     * @param object $object1 La première valeur à vérifier.
     * @param object $object2 La deuxième valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function sameClass($object1, $object2, $message = 'Doit être de la même clase.') {
        if (get_class($object1) != get_class($object2)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux objets ne font pas partie de la même classe.
     * 
     * @param object $object1 La première valeur à vérifier.
     * @param object $object2 La deuxième valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notSameClass($object1, $object2, $message = 'Ne doit pas être de la même clase.') {
        if (get_class($object1) == get_class($object2)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux valeur ont le même type.
     * 
     * @param mixed $mixed1 La première valeur à vérifier.
     * @param mixed $mixed2 La deuxième valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function sameType($mixed1, $mixed2, $message = 'Doit être du même type.') {
        if (gettype($mixed1) != gettype($mixed2)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux valeur ont un type différent.
     * 
     * @param mixed $mixed1 La première valeur à vérifier.
     * @param mixed $mixed2 La deuxième valeur à vérifier.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notSameType($mixed1, $mixed2, $message = 'Ne doit pas être du même type.') {
        if (gettype($mixed1) == gettype($mixed2)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur est dans une liste.
     * 
     * @param mixed $mixed La valeur à chercher.
     * @param array $array La liste à vérifier.
     * @param bool $checktype Si vrai, la valeur doit être du même type que la liste.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function inArray($mixed, $array, $checktype = false, $message = 'Doit être dans la liste.') {
        if (!in_array($mixed, $array, $checktype)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si une valeur n'est pas dans une liste.
     * 
     * @param mixed $mixed La valeur à chercher.
     * @param array $array La liste à vérifier.
     * @param bool $checktype Si vrai, la valeur doit être du même type que la liste.
     * @param string $message Le message en cas d'erreur.
     * @return void
     */
    protected function notInArray($mixed, $array, $checktype = false, $message = 'Ne doit pas être dans la liste.') {
        if (in_array($mixed, $array, $checktype)) {
            $this->fail($message);
        }
    }


    /**
     * Vérifie si deux listes contiennent les mêmes valeurs.
     * 
     * @param array $array1 La première liste à vérifier.
     * @param array $array2 La deuxième liste à vérifier.
     * @param string $message Le message en cas d'erreur.
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
     * Vérifie si deux listes contiennent des valeurs différentes.
     * 
     * @param array $array1 La première liste à vérifier.
     * @param array $array2 La deuxième liste à vérifier.
     * @param string $message Le message en cas d'erreur.
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