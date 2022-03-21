<?php
namespace Kernel;



/**
 * Librairie gerant les tests unitaire
 */
class Testing {

    /**
     * Termine le test avec un code de sortie et un message d'erreur
     * 
     * @param string le message
     */
    static function fail($message = null) {
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
     */
    static function assertFalse($bool, $message = 'Doit être faux.') {
        if (!is_bool($bool) || $bool) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur est vraie
     * 
     * @param bool la valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertTrue($bool, $message = 'Doit être vrai.') {
        if (!is_bool($bool) || !$bool) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur est null
     * 
     * @param object la valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertNull($object, $message = 'Doit être null.') {
        if (!is_null($object)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur est pas null
     * 
     * @param object la valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertNotNull($object, $message = 'Ne doit pas être null.') {
        if (is_null($object)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur a un type precis
     * 
     * @param object la valeur a verifier
     * @param string le type requis
     * @param string le message en cas d'echec
     */
    static function assertType($object, $type, $message = 'N\'est pas du type requis.') {
        if (gettype($object) != $type) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur n'est pas d'un type precis
     * 
     * @param object la valeur a verifier
     * @param string le type indesirable
     * @param string le message en cas d'echec
     */
    static function assertNotType($object, $type, $message = 'Est du type indesirable.') {
        if (gettype($object) == $type) {
            self::fail($message);
        }
    }


    /**
     * Verifie si un objet fait partie du classe precise
     * 
     * @param object l'objet a verifier
     * @param string la classe requise
     * @param string le message en cas d'echec
     */
    static function assertClass($object, $class, $message = 'N\'est pas de la bonne classe.') {
        if (get_class($object) != $class) {
            self::fail($message);
        }
    }


    /**
     * Verifie si un objet ne fait pas partie du classe precise
     * 
     * @param object l'objet a verifier
     * @param string la classe indesirable
     * @param string le message en cas d'echec
     */
    static function assertNotClass($object, $class, $message = 'Est de la classe indesirable.') {
        if (get_class($object) == $class) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux valeurs sont egales. Si ces valeurs sont des objets, la
     * comparaison se fera sur les proprietes et la classe
     * 
     * @param object la premiere valeur a verifier
     * @param object la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertEquals($object1, $object2, $message = 'Doit être égales.') {
        if ($object1 != $object2) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux valeurs sont differentes. Si ces valeurs sont des objets, la
     * comparaison se fera sur les proprietes et la classe
     * 
     * @param object la premiere valeur a verifier
     * @param object la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertNotEquals($object1, $object2, $message = 'Ne doit pas être égales.') {
        if ($object1 == $object2) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux objets ont les memes references
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     */
    static function assertSame($object1, $object2, $message = 'Doit être identique.') {
        if ($object1 !== $object2) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux objets ont des references differentes
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     */
    static function assertNotSame($object1, $object2, $message = 'Ne doit pas être identique.') {
        if ($object1 === $object2) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux objets font partie de la meme classe
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     */
    static function assertSameClass($object1, $object2, $message = 'Doit être de la meme clase.') {
        if (get_class($object1) != get_class($object2)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux objets ne font pas partie de la meme classe
     * 
     * @param object le premier objet a verifier
     * @param object le deuxieme objet a verifier
     * @param string le message en cas d'echec
     */
    static function assertNotSameClass($object1, $object2, $message = 'Ne doit pas être de la meme clase.') {
        if (get_class($object1) == get_class($object2)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux valeur ont le meme type
     * 
     * @param object la premiere valeur a verifier
     * @param object la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertSameType($object1, $object2, $message = 'Doit être du meme type.') {
        if (gettype($object1) != gettype($object2)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux valeur ont un type different
     * 
     * @param object la premiere valeur a verifier
     * @param object la deuxieme valeur a verifier
     * @param string le message en cas d'echec
     */
    static function assertNotSameType($object1, $object2, $message = 'Ne doit pas être du meme type.') {
        if (gettype($object1) == gettype($object2)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur est dans une liste
     * 
     * @param object l'objet a verifier
     * @param array la liste a verifier
     * @param bool si on verifie les types
     * @param string le message en cas d'echec
     */
    static function assertInArray($object, $array, $checktype = false, $message = 'Doit être dans la liste.') {
        if (!in_array($object, $array, $checktype)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si une valeur n'est pas dans une liste
     * 
     * @param object l'objet a verifier
     * @param array la liste a verifier
     * @param bool si on verifie les types
     * @param string le message en cas d'echec
     */
    static function assertNotInArray($object, $array, $checktype = false, $message = 'Ne doit pas être dans la liste.') {
        if (in_array($object, $array, $checktype)) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux listes contiennent les memes valeurs
     * 
     * @param array la premiere liste a verifier
     * @param array la deuxieme liste a verifier
     * @param string le message en cas d'echec
     */
    static function assertArraySameValues($array1, $array2, $message = 'Doivent avoir les même valeurs.') {
        sort($array1);
        sort($array2);
        if ($array1 != $array2) {
            self::fail($message);
        }
    }


    /**
     * Verifie si deux listes contiennent des valeurs differentes
     * 
     * @param array la premiere liste a verifier
     * @param array la deuxieme liste a verifier
     * @param string le message en cas d'echec
     */
    static function assertArrayNotSameValues($array1, $array2, $message = 'Ne doivent pas avoir les même valeurs.') {
        sort($array1);
        sort($array2);
        if ($array1 == $array2) {
            self::fail($message);
        }
    }
    
}

?>