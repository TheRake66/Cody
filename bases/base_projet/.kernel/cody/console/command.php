<?php
namespace Cody\Console;



/**
 * Librairie gérant les commandes du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Cody\Console
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Command {

    /**
     * Affiche la liste des commandes disponibles.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function help($args) {
        Output::printLn(
"* help                            Affiche la liste des commandes disponible.
api [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un module d'API avec le nom spécifié.
build                           Construit le projet, minifie et compile les fichiers. Nécessite npm.
* cd [*chemin]                    Change le dossier courant ou affiche la liste des fichiers et des dossiers
                                du dossier courant.
* cls                             Nettoie la console.
com [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un composant (controleur, vue, style,
                                script) avec le nom spécifié.
* bye                             Quitte Cody en fermant le serveur PHP si il y en a un.
* dl [url] [chemin]               Télécharge un fichier avec l'URL spécifiée.
* exp                             Ouvre le projet dans l'explorateur de fichiers.
lib [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une librairie (PHP, LESS, et JavaScript).
                                avec le nom spécifié.
* ls                              Affiche la liste des projets.
maj                             Vérifie les mises à jour disponibles.
new [nom]                       Créer un nouveau projet avec le nom spécifié puis défini le dossier courant.
obj [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un objet (classe DTO, classe DAO)
                                avec le nom spécifié.
pkg [-t|-l|-s] [*nom]           Télécharge, liste ou supprime un package depuis le dépôt de Cody.
rep                             Ouvre la dépôt GitHub de Cody.
* run [-f]                        Lance un serveur PHP et ouvre le projet dans le navigateur. Si l'option '-f'
                                est ajouté, tous les processus PHP seront arrêté, sinon seul le processus
                                démarrer par Cody sera arrêté.
* stop [-f]                       Arrête le serveur PHP. L'option '-f' arrête tous les processus PHP.
tes [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une classe de test unitaire.
tra [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un trait.
unit                            Lance les tests unitaires.
* vs                              Ouvre le projet dans Visual Studio Code.
init
schem

* : Argument facultatif.");
    }


    /**
     * Initialise un project.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function init($args) {
        Output::printLn("Initialisation du projet...");

        $p_file = Project::FILE_PROJECT;
        $c_file = Project::FILE_CONFIGURATION;
        $project = basename(Environnement::root());
        $version = Program::CODY_VERSION;
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $user = getenv('username');

        Project::replace('PROJECT_NAME', $project, $p_file);
        Project::replace('PROJECT_VERSION', $version, $p_file);
        Output::print('Fichier : "');
        Output::print($p_file, Output::COLOR_FORE_GREEN);
        Output::print('" modifié.');

        Project::replace('PROJECT_CREATED', $date, $p_file);
        Project::replace('PROJECT_AUTHOR', $user, $p_file);
        Project::replace('PROJECT_NAME', $project, $c_file);
        Project::replace('PROJECT_AUTHOR', $user, $c_file);
        Output::print('Fichier : "');
        Output::print($c_file, Output::COLOR_FORE_GREEN);
        Output::print('" modifié.');

        Output::printLn("Projet initialisé avec succès.");
    }


    /**
     * Liste les projets du dossier courant.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function ls($args) {
        $path = rtrim(getcwd(), '/') . '/*';
        $dirs = glob($path, GLOB_ONLYDIR);
        $bar = str_repeat('═', Environnement::MAX_WINDOW_WIDTH - 2);
        $space = function($text, $sub = 26) {
            return str_repeat(' ', Environnement::MAX_WINDOW_WIDTH - $sub - strlen($text));
        };
        $invalid = function($text, $value, 
            $valid = Output::COLOR_FORE_DEFAULT, 
            $invalid = Output::COLOR_FORE_RED, $check = '???') use ($space) {
            Output::print('║ ' . $text . ' : ');
            Output::print($value, $value !== $check ? $valid : $invalid);
            Output::printLn($space($value) . ' ║');
        };
        $count = 0;
        foreach ($dirs as $dir) {
            if (Project::is($dir)) {
                $count++;
                $decode = Project::decode(Project::FILE_PROJECT, $dir);
                if ($decode) {
                    $folder = basename($dir);
                    $name = $decode->name ?? '???';
                    $version = $decode->version ?? '???';
                    $created = $decode->created ?? null;
                    $created = $created ? date('d/m/Y H:i:s', strtotime($created)) : '???';
                    $author = $decode->author ?? '???';
                    $nombre = 0;
                    $taille = 0;

                    Output::printLn('╔' . $bar . '╗');
                    Output::printLn('║ Projet n°' . $count . $space($count, 13) . ' ║');
                    Output::printLn('╠' . $bar . '╣');
                    $invalid('Dossier            ', $folder);
                    $invalid('Nom                ', $name, Output::COLOR_FORE_MAGENTA);
                    $invalid('Version            ', $version, Output::COLOR_FORE_YELLOW, Output::COLOR_FORE_GREEN, Program::CODY_VERSION);
                    $invalid('Créé le            ', $created);
                    $invalid('Fait par           ', $author);
                    $invalid('Nombre de fichiers ', $nombre);
                    $invalid('Taille mémoire     ', $taille);
                    Output::printLn('╚' . $bar . '╝');
                }
            }
        }
        if ($count > 0) {
            Output::print('Listage terminé. Il y a ');
            Output::print($count, Output::COLOR_FORE_GREEN);
            Output::printLn(' projet(s) dans le dossier courant.');
            Output::printLn('Les dossiers commençant par "." ont été ignorés pour les calculs. (Sauf le dossier .kernel)');
        } else {
            Output::printLn('Heuuu, il n\'y a aucun projet dans le dossier courant...');
        }
    }


    /**
     * Télécharge un fichier depuis l'URL spécifiée.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function dl($args) {
        if (isset($args[0]) && isset($args[1])) {
            Output::printLn("Téléchargement du fichier...");
            $url = $args[0];
            $path = $args[1];
            $file = file_get_contents($url);
            if ($file !== false) {
                if (file_put_contents($path, $file) !== false) {
                    Output::printLn("Le fichier a été téléchargé.");
                } else {
                    Output::printLn("Impossible de créer le fichie sur le disque.");
                }
            } else {
                Output::printLn("Erreur lors du téléchargement du fichier.");
            }
        } else {
            Output::printLn("Erreur : il faut deux arguments : l'URL et le chemin de destination.");
        }
    }


    /**
     * Quitte Cody en fermant le serveur PHP si il y en a un.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function bye($args) {
        Server::stop();
        exit(0);
    }


    /**
     * Nettoie la console.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cls($args) {
        Output::clear();
    }


    /**
     * Lance le serveur PHP et ouvre le projet dans le navigateur.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function run($args) {
        Output::printLn('Lancement du serveur...');
        $run = Server::run();
        if (is_null($run)) {
            Output::printLn('Le serveur est déjà lancé !');
        } elseif ($run) {
            Output::printLn('Serveur lancé !');
            shell_exec('start http://localhost:6600/index.php');
        } else {
            Output::printLn('Erreur lors du lancement du serveur !');
        }
    }


    /**
     * Ferme le serveur PHP.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function stop($args) {
        Output::printLn('Arrêt du serveur...');
        $stop = Server::stop();
        if (is_null($stop)) {
            Output::printLn('Le serveur n\'est pas lancé !');
        } elseif ($stop) {
            Output::printLn('Serveur arrêté !');
        } else {
            Output::printLn('Erreur lors de l\'arrêt du serveur !');
        }
    }


    /**
     * Ouvre le projet dans Visual Studio Code.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function vs($args) {
        Output::printLn('Ouverture de Visual Studio Code...'); 
        if (Environnement::async('code .')) {
            Output::printLn('Ouverture réussie.');
        } else {
            Output::printLn('Ouverture échouée.');
        }
    }


    /**
     * Ouvre le projet dans l'explorateur de fichiers.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function exp($args) {
        Output::printLn('Ouverture de l\'explorateur de fichiers...');
        shell_exec('start .');
    }


    /**
     * Change le dossier courant par celui du projet.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function root($args) {
        Output::printLn('Retour au dossier du projet...');
        chdir(Environnement::root());
    }


    /**
     * Change le dossier courant par celui spécifié. 
     * Ou affiche la liste des fichiers et des dossiers du dossier courant.
     * 
     * @param array $args Arguments de la commande.
     * @return void
     */
    static function cd($args) {
        if (isset($args[0])) {
            $path = $args[0];
            if (is_dir($path)) {
                chdir($path);
            } else {
                Output::printLn('Ce dossier n\'existe pas !');
            }
        } else {
            $path = rtrim(getcwd(), '/') . '/*';
            $dirs = glob($path, GLOB_ONLYDIR);
            $files = glob($path);
            $alls = array_unique(array_merge($dirs, $files));
            
            $longest = 0;
            foreach ($alls as $element) {
                $base = basename($element);
                $len = strlen($base);
                if ($len > $longest) {
                    $longest = $len;
                }
            }
            $longest += 3;

            $count = 0;
            foreach ($alls as $element) {
                $base = basename($element);
                $len = strlen($base);
                $margin = $longest - $len;
                $space = str_repeat(' ', $margin);
                $count += $len + $margin;
                $output = $base . $space;
                $color = is_dir($element) ? 
                    (Project::is($element) ?
                        Output::COLOR_FORE_MAGENTA : 
                        Output::COLOR_FORE_BLUE) :
                    Output::COLOR_FORE_CYAN;

                Output::print($output, $color);

                if ($count >= Environnement::MAX_WINDOW_WIDTH) {
                    Output::break();
                    $count = 0;
                }
            }
            
        }
    }

}


?>