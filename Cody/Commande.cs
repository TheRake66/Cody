using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Reflection;
using Cody.Properties;
using Newtonsoft.Json;

namespace Cody
{
    public class Commande
    {
        // L'instance du serveur lancer
        private static Process serverRun;


        // Affiche l'aide
        public static void aideCom(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Affiche l'aide
                Console.WriteLine(
@"aide                            Affiche la liste des commandes disponible.
api [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un module d'API avec le nom spécifié.
build                           Construit le projet, minifie et compile les fichiers. Nécessite npm.
cd [*chemin]                    Change le dossier courant ou affiche la liste des fichiers et des dossiers
                                du dossier courant.
cls                             Nettoie la console.
com [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un composant (controleur, vue, style,
                                script) avec le nom spécifié.
dev                             Active ou désactive le mode développeur (bêta-testeur).
bye                             Quitte Cody en fermant le serveur PHP si il y en a un.
dl [url] [fichier]              Télécharge un fichier avec l'URL spécifiée.
exp                             Ouvre le projet dans l'explorateur de fichiers.
lib [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une librairie (PHP, LESS, et JavaScript).
                                avec le nom spécifié.
ls                              Affiche la liste des projets.
maj                             Vérifie les mises à jour disponibles.
new [nom]                       Créer un nouveau projet avec le nom spécifié puis défini le dossier courant.
obj [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un objet (classe DTO, classe DAO)
                                avec le nom spécifié.
pkg [-t|-l|-s] [*nom]           Télécharge, liste ou supprime un package depuis le dépôt de Cody.
rep                             Ouvre la dépôt GitHub de Cody.
run [-f]                        Lance un serveur PHP et ouvre le projet dans le navigateur. Si l'option '-f'
                                est ajouté, tous les processus PHP seront arrêté, sinon seul le processus
                                démarrer par Cody sera arrêté.
stop [-f]                       Arrête le serveur PHP. L'option '-f' arrête tous les processus PHP.
tes [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime une classe de test unitaire.
tra [-s|-a|-l] [*nom]           Ajoute, liste, ou supprime un trait.
unit                            Lance les tests unitaires.
vs                              Ouvre le projet dans Visual Studio Code.

* : Argument facultatif.");
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Nettoire la console
        public static void devMode(string[] cmd)
        {
            if (cmd.Length == 0)
                if (Program.config.modeBeta)
                {
                    Program.config.modeBeta = false;
                    Console.WriteLine("Mode développeur désactivé.");
                }
                else
                {
                    Program.config.modeBeta = true;
                    Console.WriteLine("Mode développeur activé.");
                }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Change le chemin courant
        public static void changeDir(string[] cmd)
        {
            if (cmd.Length == 1)
            {
                string path = cmd[0];

                // Verifi si le dossier existe deja
                if (Directory.Exists(path))
                {
                    try
                    {
                        // Change le dossier
                        Directory.SetCurrentDirectory(path);
                        Program.config.lastPath = Directory.GetCurrentDirectory();
                        Console.WriteLine("Chemin changé.");
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible de changer de dossier !", e);
                    }
                }
                else
                    Console.WriteLine("Erreur, le chemin spécifié n'existe pas !");
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, seul un chemin est attendu.");
            else
            {
                try
                {
                    string path = Directory.GetCurrentDirectory();
                    string[] dirs = Directory.GetDirectories(path);
                    string[] files = Directory.GetFiles(path);


                    string[] all = new string[dirs.Length + files.Length];
                    dirs.CopyTo(all, 0);
                    files.CopyTo(all, dirs.Length);


                    string longest = "";
                    foreach (string i in all)
                    {
                        string name = Path.GetFileName(i);
                        if (name.Length > longest.Length) longest = name;
                    }
                    int max = longest.Length + 3;


                    int x = Console.CursorLeft;
                    foreach (string i in all)
                    {
                        Message.setPos(x, Console.CursorTop);
                        Message.writeIn(dirs.Contains(i) ? 
                            Librairie.isFolderProject(i) ? ConsoleColor.Magenta : ConsoleColor.Blue : 
                            ConsoleColor.Cyan, Path.GetFileName(i));
                        x += max;
                        if (x + max >= Console.WindowWidth)
                        {
                            x = 0;
                            Console.WriteLine();
                        }
                    }

                    Console.WriteLine();
                    Console.WriteLine($"{Librairie.toNumberFr(dirs.Length)} dossier(s) et {Librairie.toNumberFr(files.Length)} fichier(s).");
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible de lister les dossiers et fichiers", e);
                }
            }
        }


        // Telecharge un fichier
        public static void downFile(string[] cmd)
        {
            if (cmd.Length == 2)
            {
                // Recupere les args
                string url = cmd[0];
                string file = Librairie.remplaceDirSep(cmd[1]);


                // Prepapre l'animation
                Console.WriteLine(
@"▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄
█                                                  █
▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀");
                int x = 0;
                int y = Console.CursorTop - 3;
                int x_barre = x + 1;
                int y_barre = y + 1;
                int x_byte = x + 53;
                long total_byte = 0;
                object lk = new object(); // lock
                bool ended = false;
                Exception ex = null;

                Action<int, long, long> display_barre = (percent, receceid, total) =>
                {
                    // Progress
                    Message.setPos(x_barre, y_barre);
                    Console.ForegroundColor = ConsoleColor.DarkGreen;
                    // Pas de percent / 2, le modulo est plus rapide que la division flotante
                    for (float i = 0; i < percent; i++)
                        if (i % 2 == 0) Console.Write("▓");
                    Console.ResetColor();

                    Message.setPos(x_byte, y_barre);
                    Console.Write($"{percent}% ");
                    Message.writeIn(ConsoleColor.DarkYellow, Librairie.toNumberMem(receceid));
                    Console.Write(" sur ");
                    Message.writeIn(ConsoleColor.DarkYellow, Librairie.toNumberMem(total));
                    Console.Write("...");
                };


                WebClient web = Librairie.getProxyClient();
                web.DownloadProgressChanged += (s, e) =>
                {
                    lock (lk)
                    {
                        // Progress
                        display_barre(e.ProgressPercentage, e.BytesReceived, e.TotalBytesToReceive);
                        if (total_byte == 0) total_byte = e.TotalBytesToReceive;
                        // Pour les tests
                        // dl https://launcher.mojang.com/v1/objects/a16d67e5807f57fc4e550299cf20226194497dc2/server.jar server.jar
                        // dl https://i.pinimg.com/originals/89/3c/48/893c48d2342c5e0336fdefe231c40d48.png a.png
                    }
                };
                web.DownloadFileCompleted += (s, e) =>
                {
                    ended = true;
                    ex = e.Error;
                };
                // Telecharge en asyncrone
                web.DownloadFileTaskAsync(url, file);


                // Attends la fin et de delockage
                while (!ended || !Monitor.TryEnter(lk)) 
                {
                    Thread.Sleep(500);
                }


                if (ex == null) // Si aucune exception
                {
                    // Progress complete
                    display_barre(100, total_byte, total_byte);
                    Message.setPos(x, y + 3);
                    Console.WriteLine("Téléchargement terminé.");
                }
                else
                {
                    Message.setPos(x, y + 3);
                    Message.writeExcept("Impossible de télécharger ce fichier !", ex);
                }
            }
            else if (cmd.Length > 2)
                Console.WriteLine("Problème, seul l'url et le chemin du fichier sont attendus !");
            else
                Console.WriteLine("Problème, il manque l'url et le chemin du fichier !");
        }


        // Nettoire la console
        public static void clearCons(string[] cmd)
        {
            if (cmd.Length == 0)
                Console.Clear(); // Clear la console
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Ouvre le depot github
        public static void openRepo(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Ouvre dans le navigateur
                try 
                {
                    // Ouvre dans le navigateur
                    Librairie.startProcess("https://github.com/TheRake66/Cody");
                    Console.WriteLine("Navigateur lancé.");
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible d'ouvrir le navigateur !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Ferme l'app
        public static void quitterApp(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Ferme les serveur php
                if (Commande.serverRun != null && !Commande.serverRun.HasExited)
                    Commande.serverRun.Kill();
                Environment.Exit(0); // Ferme avec un code 0
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Verifi les mise a jour
        public static void verifMAJ(string[] cmd)
        {
            if (cmd.Length == 0)
                checkUpdate();
            else
                Console.WriteLine("Problème, aucun argument est attendu !");
        }
        public static void checkUpdate(bool silent = false)
        {
            try
            {
                // Prepare un client http
                WebClient client = Librairie.getProxyClient();
                Prod lastversion = JsonConvert.DeserializeObject<Prod>(
                    client.DownloadString(Librairie.getGitBranch() + "/version.json"));

                // Compare les version
                if (lastversion.version.Equals(Program.version))
                {
                    if (!silent) Console.WriteLine("Vous êtes à jour !");
                }
                else
                {
                    Console.WriteLine();
                    Console.Write("La version ");
                    Message.writeIn(ConsoleColor.Green, lastversion.version);
                    Console.WriteLine(" est disponible, voulez vous la télécharger ?");
                    Console.WriteLine();
                    Console.WriteLine("Modifications :");
                    foreach (string st in lastversion.modifications)
                        Console.WriteLine("   • " + st);
                    Console.WriteLine("Bugs :");
                    foreach (string st in lastversion.bugs)
                        Console.WriteLine("   • " + st);
                    Console.WriteLine();
                    Console.Write("Rétro-compatible : ");
                    if (lastversion.retrocompatible)
                        Message.writeLineIn(ConsoleColor.Green, "Oui");
                    else
                        Message.writeLineIn(ConsoleColor.DarkRed, "Non");
                    Console.WriteLine();

                    bool continu = Librairie.inputYesNo();
                    if (continu)
                    {
                        try
                        {
                            Librairie.startProcess("https://cody-framework.fr/index.php?routePage=telecharger");
                        }
                        catch (Exception e)
                        {
                            Message.writeExcept("Impossible de d'ouvrir le navigateur !", e);
                        }
                    }
                }
            }
            catch (Exception e)
            {
                if (!silent) Message.writeExcept("Impossible de vérifier les mises à jour !", e);
            }
        }


        // Ouvre dans l'explorateur
        public static void openExplorer(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Si le projet existe
                if (Librairie.isProject())
                {
                    try
                    {
                        // Ouvre dans le navigateur
                        Librairie.startProcess(Directory.GetCurrentDirectory());
                        Console.WriteLine("Explorateur de fichiers lancé.");
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible d'ouvrir l'explorateur !", e);
                    }
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Ouvre dans vs code
        public static void openVSCode(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Si le projet existe
                if (Librairie.isProject())
                {
                    try
                    {
                        // Ouvre dans le navigateur
                        Librairie.startProcess("code", ".", ProcessWindowStyle.Hidden);
                        Console.WriteLine("Visual Studio Code lancé.");
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible d'ouvrir Visual Studio Code !", e);
                    }
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // ########################################################################


        // Ouvre le projet dans le navigateur et lance un serveur PHP
        public static void runProjet(string[] cmd)
        {
            if (cmd.Length <= 1)
            {
                // Verifie les arguments
                bool force = false;
                if (cmd.Length == 1)
                {
                    string arg = cmd[0];
                    if (arg == "-f")
                        force = true;
                    else
                    {
                        Console.WriteLine("Argument '" + arg + "' est invalide !");
                        return;
                    }
                }

                // Si le projet existe
                if (Librairie.isProject())
                {
                    try
                    {
                        // Arrete toutes les instances
                        if (force)
                        {
                            Process[] php = Process.GetProcessesByName("php");
                            if (php.Length > 0)
                            {
                                foreach (Process p in php)
                                    p.Kill();
                                Console.WriteLine("Toutes les instances de PHP ont été arrêté.");
                            }
                            else
                                Console.WriteLine("Aucune instance de PHP !");
                        }
                        else
                        {
                            // Ferme le serveur php
                            if (Commande.serverRun != null && !Commande.serverRun.HasExited)
                            {
                                Commande.serverRun.Kill();
                                Console.WriteLine("Le serveur a été arrêté.");
                            }
                        }

                        // Lance PHP
                        Commande.serverRun = Librairie.startProcess("php", "-S localhost:6600", ProcessWindowStyle.Minimized);
                        Console.WriteLine("Serveur PHP lancé.");

                        // Ouvre dans le navigateur
                        Librairie.startProcess($"http://localhost:6600");
                        Console.WriteLine("Navigateur lancé.");
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible de lancer le projet !", e);
                    }
                }
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, trop d'arguments !");
        }

        // Ferme le serveur PHP
        public static void stopProjet(string[] cmd)
        {
            if (cmd.Length <= 1)
            {
                // Verifie les arguments
                bool force = false;
                if (cmd.Length == 1)
                {
                    string arg = cmd[0];
                    if (arg == "-f")
                        force = true;
                    else
                    {
                        Console.WriteLine("Argument '" + arg + "' est invalide !");
                        return;
                    }
                }

                // Si le projet existe
                if (Librairie.isProject())
                {
                    // Arrete toutes les instances
                    if (force)
                    {
                        Process[] php = Process.GetProcessesByName("php");
                        if (php.Length > 0)
                        {
                            foreach (Process p in php)
                                p.Kill();
                            Console.WriteLine("Toutes les instances de PHP ont été arrêté.");
                        }
                        else 
                            Console.WriteLine("Aucune instance de PHP !");
                    }
                    else
                    {
                        // Ferme le serveur php
                        if (Commande.serverRun != null)
                        {
                            if (!Commande.serverRun.HasExited)
                            {
                                try
                                {
                                    Commande.serverRun.Kill();
                                    Console.WriteLine("Le serveur a été arrêté.");
                                }
                                catch (Exception e)
                                {
                                    Message.writeExcept("Impossible d'arrêter le serveur !", e);
                                }
                            }
                            else
                                Console.WriteLine("Le serveur s'est déjà arrêté !");
                        }
                        else
                            Console.WriteLine("Aucun serveur n'a été lancé !");
                    }
                }
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, trop d'arguments !");
        }


        // Minify le projet
        public static void buildProject(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Si le projet existe
                if (Librairie.isProject())
                {
                    string[] excludedFiles = new string[]
                    {
                        ".gitignore",
                        ".gitattributes",
                        "less@4.1.1.js",
                        "theme.less",
                        "project.json",
                        "component.json",
                        "object.json",
                        "trait.json",
                        "library.json",
                        "test.json",
                        "api.json"
                    };
                    string[] excludedFolder = new string[]
                    {
                        ".git",
                        ".vs",
                        ".vscode",
                        "release",
                        "documents",
                        "logs",
                        "tests"
                    };

                    try
                    {
                        if (Librairie.installNpmPackage("less") && 
                            Librairie.installNpmPackage("minify"))
                        {
                            string c = Directory.GetCurrentDirectory();
                            string t = Path.Combine(c, "release");
                            if (Directory.Exists(t)) {
                                Directory.Delete(t, true);
                            }
                            Directory.CreateDirectory(t);
                            recursiveCopyAndMinify(c, c, t, excludedFiles, excludedFolder);
                            Console.WriteLine("Le projet a été construit. N'oubliez pas de modifier le fichier de configuration afin de faire la mise en production.");
                        }
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible de construire le projet !", e);
                    }
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }
        private static void recursiveCopyAndMinify(string path, string origin, string originto, string[] exFi, string[] exFo)
        {
            // Traite les fichier
            foreach (string f in Directory.GetFiles(path))
            {
                if (!exFi.Contains(Path.GetFileName(f)))
                {
                    // Extension to lower
                    string ex = Path.GetExtension(f).ToLower();
                    // C:\projet = origin
                    // C:\projet\release = originto
                    // C:\projet\debug\app\global.less = f
                    // release = premier substring
                    // debug\app\global.less = deuxieme substring
                    // release\debug\app\global.less = combine
                    string rel = Path.Combine(
                            originto.Substring(origin.Length + 1),
                            f.Substring(origin.Length + 1));
                    string nf = Path.Combine(origin, rel);

                    // Si less on compile puis minifi
                    if (ex == ".less")
                        compileMinifyLess(f, nf, rel);
                    // Juste minifi
                    else if (ex == ".js")
                        minifyFile(f, nf, rel, ex);
                    // Juste copie
                    else
                        moveFileToRelease(f, nf, rel);
                }
            }

            // Creer les dossier puis recursive
            foreach (string d in Directory.GetDirectories(path))
            {
                if (!exFo.Contains(Path.GetFileName(d)))
                {
                    string rel = Path.Combine(
                            originto.Substring(origin.Length + 1),
                            d.Substring(origin.Length + 1));
                    string nd = Path.Combine(origin, rel);
                    if (createDirToRelease(nd, rel))
                        recursiveCopyAndMinify(d, origin, originto, exFi, exFo);
                }
            }
        }
        private static bool createDirToRelease(string dir, string rel)
        {
            try
            {
                Directory.CreateDirectory(dir);

                Console.Write("Dossier : '");
                Message.writeIn(ConsoleColor.Magenta, rel);
                Console.WriteLine("' ajouté.");
                return true;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de créer le dossier !", e);
                return false;
            }
        }
        private static void moveFileToRelease(string file, string to, string rel)
        {
            try
            {
                File.Copy(file, to);

                Console.Write("Fichier : '");
                Message.writeIn(ConsoleColor.DarkGreen, rel);
                Console.Write("' copié (");
                Message.writeIn(ConsoleColor.DarkYellow, Librairie.getFileSize(file));
                Console.WriteLine(").");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de copier le fichier !", e);
            }
        }
        private static void minifyFile(string file, string to, string rel, string ex)
        {
            try
            {
                rel = Path.ChangeExtension(rel, ".min" + ex);
                to = Path.ChangeExtension(to, ".min" + ex);

                if (Librairie.runNpmCmd("minify", "\"" + file + "\" > \"" + to + "\""))
                {
                    // Pour les imports
                    string code = File.ReadAllText(to);
                    File.WriteAllText(to, code.Replace(ex, ".min" + ex));

                    Console.Write("Fichier : '");
                    Message.writeIn(ConsoleColor.DarkGreen, rel);
                    Console.Write("' minifié (");
                    Message.writeIn(ConsoleColor.DarkYellow, Librairie.getFileSize(to));
                    Console.WriteLine(").");
                }
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de minifier le fichier !", e);
            }
        }
        private static void compileMinifyLess(string file, string to, string rel)
        {
            try
            {
                // Minify n'accepte pas les fichiers less meme si ils contiennent du css
                string css = Path.ChangeExtension(to, ".css");
                string min = Path.ChangeExtension(to, ".min.css");
                rel = Path.ChangeExtension(rel, ".min.css");
                if (Librairie.runNpmCmd("lessc", "\"" + file + "\" > \"" + css + "\""))
                {
                    if (Librairie.runNpmCmd("minify", "\""+ css + "\" > \"" + min + "\""))
                    {
                        Console.Write("Fichier : '");
                        Message.writeIn(ConsoleColor.DarkGreen, rel);
                        Console.Write("' compilé puis minifié (");
                        Message.writeIn(ConsoleColor.DarkYellow, Librairie.getFileSize(min));
                        Console.WriteLine(").");
                    }
                    File.Delete(css);
                }
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de compiler et minifier le fichier !", e);
            }
        }


        // Lance les tests unitaires
        public static void runUnit(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Si le projet existe
                if (Librairie.isProject())
                {
                    string jsoni = "tests/test.json";
                    if (File.Exists(jsoni))
                    {
                        try
                        {
                            string json = File.ReadAllText(jsoni);

                            if (json != "")
                            {
                                Console.WriteLine("Lancement des tests...");
                                Console.WriteLine("═════════════════════════════════════════════════════════════════════");

                                List<Item> objs = JsonConvert.DeserializeObject<List<Item>>(json);
                                int count = 0;
                                int passed = 0;
                                foreach (Item obj in objs)
                                {
                                    if (obj.paths.Count > 0)
                                    {
                                        string file = obj.paths[0];
                                        if (File.Exists(file))
                                        {
                                            count++;
                                            if (runTestFile(obj))
                                                passed++;
                                        }
                                        else
                                        {
                                            Console.Write("Le fichier de test '");
                                            Message.writeIn(ConsoleColor.DarkYellow, file);
                                            Console.WriteLine("' est indexé mais est introuvable !");
                                        }
                                    }
                                    else
                                    {
                                        Console.WriteLine("Aucun fichier de test pour le test '" + obj.name + "'.");
                                    }
                                }

                                Console.WriteLine("═════════════════════════════════════════════════════════════════════");
                                if (count > 0)
                                {
                                    if (count == passed)
                                    {
                                        Message.writeIn(ConsoleColor.DarkGreen, passed);
                                        Console.Write(" test(s) sur ");
                                        Message.writeIn(ConsoleColor.DarkGreen, count);
                                        Console.WriteLine(". Le ou les tests sont passés.");
                                    }
                                    else
                                    {
                                        Message.writeIn(ConsoleColor.DarkRed, passed);
                                        Console.Write(" test(s) sur ");
                                        Message.writeIn(ConsoleColor.DarkRed, count);
                                        Console.WriteLine(". Un ou plusieurs des tests n'est pas passé.");
                                    }
                                }
                                else
                                {
                                    Console.WriteLine("Heuuu, il n'y a aucun fichier de test dans ce projet...");
                                }
                            }
                            else
                                Console.WriteLine("Heuuu, aucun élément n'est indexé...");
                        }
                        catch (Exception e)
                        {
                            Message.writeExcept("Impossible de lire la liste des tests existant !", e);
                        }
                    }
                    else
                        Console.WriteLine("Heuuu, aucune liste de tests n'a été trouvée...");
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }
        private static bool runTestFile(Item obj)
        {
            try
            {
                string[] sp = Librairie.remplaceDirSep(obj.name).Split(Path.DirectorySeparatorChar);
                string nspc = "Test";
                foreach (string s in sp)
                {
                    if (s.Length > 0)
                    {
                        nspc += "\\" + s.Substring(0, 1).ToUpper();
                        if (s.Length > 1)
                            nspc += s.Substring(1).ToLower();
                    }
                }
                nspc = nspc.Replace(" ", "_");


                // Lance PHP
                string[] rep = Librairie.outputProcess("php", "-r \"" +
                    "set_error_handler(function() { }); " +
                    "register_shutdown_function(function() { }); " +
                    "require_once(__DIR__ . '/.kernel/php/io/autoloader.php'); " +
                    "Kernel\\IO\\Autoloader::register(); " +
                    "require_once('" + obj.paths[0] + "'); " +
                    "(new " + nspc + "())->run();" +
                    "\"");


                if (rep[0] == "0")
                {
                    Console.Write("[");
                    Message.writeIn(ConsoleColor.DarkGreen, "√");
                    Console.Write("] Test : '");
                    Message.writeIn(ConsoleColor.DarkYellow, nspc);
                    Console.WriteLine("', test réussi.");
                    return true;
                }
                else
                {
                    if (rep[0] != "1")
                        rep[1] = "Erreur interne de PHP, code de sortie : " + rep[0] + " !";
                    Console.Write("[");
                    Message.writeIn(ConsoleColor.DarkRed, "×");
                    Console.Write("] Test : '");
                    Message.writeIn(ConsoleColor.DarkYellow, nspc);
                    Console.WriteLine("', test échoué !");
                    Console.Write(" └──► Raison : ");
                    Message.writeLineIn(ConsoleColor.DarkRed, rep[1]);
                    return false;
                }
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de lancer les tests du fichier '" + obj.paths[0] + "' !", e);
                return false;
            }
        }


        // ########################################################################


        // Gere les item
        public static void gestPackage(string[] cmd)
        {
            if (cmd.Length == 1 || cmd.Length == 2)
            {
                bool continu = true;
                List<Package> list = null;
                try
                {
                    // Prepare un client http
                    WebClient client = Librairie.getProxyClient();
                    string json = client.DownloadString(Librairie.getGitBranch() + "/packages/list_packages.json");
                    list = JsonConvert.DeserializeObject<List<Package>>(json);
                }
                catch (Exception e)
                {
                    Message.writeExcept("Erreur, impossible de télécharger la liste des packages !", e);
                    continu = false;
                }

                if (continu)
                {
                    switch (cmd[0].ToLower())
                    {
                        case "-l":
                            if (cmd.Length == 1) listerPackage(list);
                            else Console.WriteLine("Trop d'arguments !");
                            break;

                        case "-s":
                            if (cmd.Length == 2)
                            {
                                // Si le projet est en derniere version
                                if (Librairie.isProject() && Librairie.checkProjetVersion())
                                {
                                    string nom = Librairie.remplaceDirSep(cmd[1].ToLower());
                                    traitementPackage(nom, list, false);
                                }
                            }
                            else Console.WriteLine("Il manque le nom du package !");
                            break;

                        case "-t":
                            if (cmd.Length == 2)
                            {
                                // Si le projet est en derniere version
                                if (Librairie.isProject() && Librairie.checkProjetVersion())
                                {
                                    string nom = Librairie.remplaceDirSep(cmd[1].ToLower());
                                    traitementPackage(nom, list, true);
                                }
                            }
                            else Console.WriteLine("Il manque le nom du package !");
                            break;

                        default:
                            Console.WriteLine("Le type d'action est invalide !");
                            break;
                    }
                }
            }
            else if (cmd.Length > 2)
                Console.WriteLine("Problème, trop d'arguments ont été données !");
            else
                Console.WriteLine("Problème, il manque le type d'action ou le nom du package !");
        }


        // Liste les package
        private static void listerPackage(List<Package> list)
        {
            List<Package> trier = list.OrderBy(o => o.name).ToList();

            Console.WriteLine("╔══════════════════════════════════╦══════════════╦═════════════════════════╦═══════════════════╗");
            Console.WriteLine("║ Nom                              ║ Version      ║ Crée le                 ║ Par               ║");
            Console.WriteLine("╠══════════════════════════════════╩══════════════╩═════════════════════════╩═══════════════════╣");

            int count = 0;
            foreach (Package pck in trier)
            {
                Console.WriteLine("║                                                                                               ║");
                afficherUnPackage(pck);
                Console.WriteLine("╟───────────────────────────────────────────────────────────────────────────────────────────────╢");

                Console.WriteLine("║ Description :                                                                                 ║");
                Console.WriteLine("║                                                                                               ║");
                afficherDescriptionPackage(pck);
                Console.WriteLine(".");
                Console.WriteLine("╟───────────────────────────────────────────────────────────────────────────────────────────────╢");

                count++;
            }

            Message.setPos(0, Console.CursorTop - 1);

            if (count > 0)
            {
                Console.WriteLine("╚═══════════════════════════════════════════════════════════════════════════════════════════════╝");
                Console.Write("Listage terminé. Il y a ");
                Message.writeIn(ConsoleColor.DarkYellow, Librairie.toNumberFr(count));
                Console.WriteLine(" package(s).");
            }
            else
            {
                Console.WriteLine("╚══════════════════════════════════╩══════════════╩═════════════════════════╩═══════════════════╝");
                Console.WriteLine("Heuuu, il n'y a aucun package...");
            }
        }
        private static void afficherDescriptionPackage(Package pack)
        {
            Message.setPos(2, Console.CursorTop - 1);
            Message.writeIn(ConsoleColor.DarkCyan, pack.description);
        }
        private static void afficherUnPackage(Package pack)
        {
            Message.setPos(2, Console.CursorTop - 1);
            Message.writeIn(ConsoleColor.Magenta, pack.name);

            Message.setPos(37, Console.CursorTop);
            Console.Write(pack.version);

            Message.setPos(52, Console.CursorTop);
            Console.Write(pack.created.ToString());
            Message.setPos(78, Console.CursorTop);
            Console.WriteLine(pack.madeby);
        }


        // Ajoute ou supprime un package
        private static void traitementPackage(string nom, List<Package> list, bool ajouter)
        {
            Package p = null;
            foreach (Package pck in list)
            {
                if (pck.name.ToLower() == nom)
                {
                    p = pck;
                    break;
                }
            }

            if (p != null)
            {
                Console.Write("Description : ");
                Message.writeIn(ConsoleColor.DarkCyan, p.description);
                Console.WriteLine(".");
                Console.WriteLine("═════════════════════════════════════════════════════════════════════");

                int count = 0;
                foreach (Element arc in p.elements)
                {
                    Console.Write("Élément : ");
                    Message.writeIn(ConsoleColor.DarkYellow, arc.name);
                    Console.WriteLine(".");

                    if (ajouter)
                    {
                        if (ajouterItem(arc.name, arc.archive, arc.index, Librairie.getGitBranch() + "/packages/")) count++;
                    }
                    else
                    {
                        if (supprimerItem(arc.name, arc.index)) count++;
                    }
                    Console.WriteLine("─────────────────────────────────────────────────────────────────────");
                }

                if (count > 0)
                {
                    Console.Write("Traitement terminé. ");
                    Message.writeIn(ConsoleColor.DarkYellow, Librairie.toNumberFr(count));
                    Console.WriteLine(" élément(s) ont/a été traité(s).");

                    if (count == p.elements.Count)
                        Console.WriteLine("Le package a été entièrement traité.");
                    else
                        Console.WriteLine("Le package a été partiellement traité.");
                }
                else
                {
                    Console.WriteLine("Aucun élément n'a été traité.");
                }
            }
            else
                Console.WriteLine("Heuuu, ce package n'existe pas...");
        }


        // ########################################################################


        // Liste les projets du dossier courant
        public static void listProjet(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                {
                    // Recupere tous les dossier du dossier courant
                    string[] dirs = Directory.GetDirectories(Directory.GetCurrentDirectory());

                    if (dirs.Length > 0)
                    {
                        int count = 0;
                        Console.WriteLine("╔═════════════════════════════╦═════════════════╦═════════════════╦═══════════════╦═════════════════════════╦═══════════════════╗");
                        Console.WriteLine("║ Nom                         ║ Fichier         ║ Taille          ║ Version       ║ Crée le                 ║ Par               ║");
                        Console.WriteLine("╠═════════════════════════════╩═════════════════╩═════════════════╩═══════════════╩═════════════════════════╩═══════════════════╣");

                        foreach (string dir in dirs)
                        {
                            // Si ca contient un index.php c'est un projet
                            string f = Path.Combine(dir, "project.json");

                            if (File.Exists(f))
                            {
                                Console.WriteLine("║                                                                                                                               ║");
                                calculerProjet(dir, f);
                                Console.WriteLine("╟───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────╢");
                                count++;
                            }
                        }

                        Message.setPos(0, Console.CursorTop - 1);

                        if (count > 0)
                        {
                            Console.WriteLine("╚═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝");
                            Console.Write("Listage terminé. Il y a ");
                            Message.writeIn(ConsoleColor.DarkYellow, Librairie.toNumberFr(count));
                            Console.WriteLine(" projet(s).");
                        }
                        else
                        {
                            Console.WriteLine("╚═════════════════════════════╩═════════════════╩═════════════════╩═══════════════╩═════════════════════════╩═══════════════════╝");
                            Console.WriteLine("Heuuu, il n'y a aucun projet dans ce dossier...");
                        }
                    }
                    else
                        Console.WriteLine("Heuuu, il n'y a aucun dossier...");
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible de lister les projets !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }
        private static void calculerProjet(string dir, string file)
        {
            Message.setPos(2, Console.CursorTop - 1);
            Message.writeIn(ConsoleColor.Magenta, Path.GetFileName(dir));

            // Calcule ne nb de fichier et la taille total
            try
            {
                long[] data = Librairie.getCountAndSizeFolder(dir);

                Message.setPos(32, Console.CursorTop);
                Console.Write(Librairie.toNumberFr((int)data[0]));
                Message.setPos(50, Console.CursorTop);
                Console.Write(Librairie.toNumberMem(data[1]));
            }
            catch (Exception e)
            {
                Console.WriteLine(e.Message);
                Message.setPos(32, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                Message.setPos(50, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
            }

            // Recupere les info de version du projet
            try
            {
                string json = File.ReadAllText(file);
                Projet inf = JsonConvert.DeserializeObject<Projet>(json);

                Message.setPos(68, Console.CursorTop);
                Message.writeIn(inf.version == Program.version ? ConsoleColor.Green : ConsoleColor.DarkYellow, inf.version);
                Message.setPos(84, Console.CursorTop);
                Console.Write(inf.created.ToString());
                Message.setPos(110, Console.CursorTop);
                Console.Write(inf.madeby);
            }
            catch
            {

                Message.setPos(68, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                Message.setPos(84, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                Message.setPos(110, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
            }

            Console.WriteLine();
        }


        // Creer un nouveau projet
        public static void creerProjet(string[] cmd)
        {
            if (cmd.Length == 1)
            {
                // Verifi si projet existe deja
                string name = cmd[0];

                if (!File.Exists(Path.Combine(name, "project.json")))
                {
                    if (creerDossierProjet(name))
                    {
                        string zip = Path.Combine(name, "base_projet.zip");
                        if (downloadProjet(zip))
                            parcoursArchiveProjet(zip, name);
                    }
                }
                else
                    Console.WriteLine("Heuuu, le projet existe déjà...");
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, seul le nom du nouveau projet est attendu !");
            else
                Console.WriteLine("Problème, il manque le nom du nouveau projet !");
        }
        private static bool downloadProjet(string path)
        {
            try
            {
                // Prepare un client http
                WebClient client = Librairie.getProxyClient();
                client.DownloadFile(Librairie.getGitBranch() + "/bases/base_projet.zip", path);
                return true;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de télécharger l'archive source de cet item !", e);
                return false;
            }
        }
        private static bool creerDossierProjet(string name)
        {
            try
            {
                // Creer le dossier du projet
                Directory.CreateDirectory(name);
                return true;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de créer le dossier du projet !", e);
                return false;
            }
        }
        private static void parcoursArchiveProjet(string zip, string name)
        {
            try
            {
                // Ouvre l'archive
                using (ZipArchive arc = ZipFile.OpenRead(zip))
                {
                    // Parcour chaque entree
                    foreach (ZipArchiveEntry ent in arc.Entries)
                    {
                        string path = Librairie.remplaceDirSep(Path.Combine(name, ent.FullName
                                .Replace("{PROJECT_NAME}", name)
                                .Replace("{USER_NAME}", Environment.UserName)
                            )); // projet\entry

                        // Si c'est un dossier
                        if (ent.Name == "")
                            extraireDossierProjet(path);
                        // Si c'est un fichier
                        else
                            extraireFichierProjet(ent, path, name);
                    }
                }

                supprimerArchiveProjet(zip);
                cacherKernelProjet(name);
                creerJsonProject(name);
                changerDossierProjet(name);

                Console.WriteLine("Le projet a été crée.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire l'archive !", e);
            }
        }
        private static void extraireDossierProjet(string path)
        {
            try
            {
                // Creer le dossier
                Directory.CreateDirectory(path);

                Console.Write("Dossier : '");
                Message.writeIn(ConsoleColor.Magenta, path);
                Console.WriteLine("' ajouté.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'ajouter le dossier !", e);
            }
        }
        private static void extraireFichierProjet(ZipArchiveEntry ent, string path, string name)
        {
            try
            {
                // Extrait le fichier de l'archive
                ent.ExtractToFile(path);

                Console.Write("Fichier : '");
                Message.writeIn(ConsoleColor.DarkGreen, path);
                Console.Write("' extrait (");
                Message.writeIn(ConsoleColor.DarkYellow, Librairie.getFileSize(path));
                Console.WriteLine(").");

                // Fichiers ou l'on rajoute le nom
                string[] toedit = new string[]
                {
                    ".php",
                    ".js",
                    ".json",
                    ".less"
                };

                if (toedit.Contains(Path.GetExtension(path)))
                {
                    try
                    {
                        // Modifie le fichier
                        File.WriteAllText(path, File.ReadAllText(path)
                            .Replace("{PROJECT_NAME}", name)
                            .Replace("{USER_NAME}", Environment.UserName)
                            );
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible d'éditer le fichier !", e);
                    }
                }
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire le fichier !", e);
            }
        }
        private static void supprimerArchiveProjet(string zip)
        {
            try
            {
                // Supprime l'archive
                File.Delete(zip);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de supprimer l'archive !", e);
            }
        }
        private static void cacherKernelProjet(string name)
        {
            try
            {
                DirectoryInfo di = new DirectoryInfo(Path.Combine(name, ".kernel"));
                if ((di.Attributes & FileAttributes.Hidden) != FileAttributes.Hidden)
                    di.Attributes |= FileAttributes.Hidden;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de cacher le dossier du kernel !", e);
            }
        }
        private static void creerJsonProject(string name)
        {

            try
            {
                // Creer le cody json
                Projet inf = new Projet();
                inf.madeby = Environment.UserName;
                inf.version = Program.version;
                inf.created = DateTime.Now;

                string json = JsonConvert.SerializeObject(inf, Formatting.Indented);
                File.WriteAllText(Path.Combine(name, "project.json"), json);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de créer le fichier d'information du projet !", e);
            }
        }
        private static void changerDossierProjet(string name)
        {

            try
            {
                // Change le dossier courant
                Directory.SetCurrentDirectory(Path.Combine(Directory.GetCurrentDirectory(), name));
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de changer le dossier courant !", e);
            }
        }


        // ########################################################################


        // Gere les objets
        public static void gestObjet(string[] cmd)
        {
            gestItem(cmd, "base_objet.zip", "debug/data/object.json");
        }


        // Gere les librairies
        public static void gestLibrairie(string[] cmd)
        {
            gestItem(cmd, "base_librairie.zip", "debug/lib/library.json");
        }


        // Gere les composants
        public static void gestComposant(string[] cmd)
        {
            gestItem(cmd, "base_composant.zip", "debug/app/component.json");
        }


        // Gere les traits
        public static void gestTrait(string[] cmd)
        {
            gestItem(cmd, "base_trait.zip", "debug/data/trait.json");
        }


        // Gere les tests
        public static void gestTest(string[] cmd)
        {
            gestItem(cmd, "base_test.zip", "tests/test.json");
        }


        // Gere les apis
        public static void gestApi(string[] cmd)
        {
            gestItem(cmd, "base_api.zip", "debug/api/api.json");
        }



        // Gere les item
        public static void gestItem(string[] args, string archivenom, string jsoni)
        {
            if (args.Length == 1 || args.Length == 2)
            {
                // Si le projet existe
                if (Librairie.isProject() && Librairie.checkProjetVersion())
                {
                    switch (args[0])
                    {
                        case "-l":
                            if (args.Length == 1) 
                                listerItem(jsoni);
                            else 
                                Console.WriteLine("Trop d'arguments !");
                            break;

                        case "-s":
                            if (args.Length == 2)
                            {
                                string nom = Librairie.remplaceDirSep(args[1].ToLower());
                                supprimerItem(nom, jsoni);
                            }
                            else Console.WriteLine("Il manque le nom de l'élément !");
                            break;

                        case "-a":
                            if (args.Length == 2)
                            {
                                string nom = Librairie.remplaceDirSep(args[1].ToLower());
                                ajouterItem(nom, archivenom, jsoni);
                            }
                            else Console.WriteLine("Il manque le nom de l'élément !");
                            break;

                        default:
                            Console.WriteLine("Le type d'action est invalide !");
                            break;
                    }
                }
            }
            else if (args.Length > 2)
                Console.WriteLine("Problème, trop d'arguments ont été données !");
            else
                Console.WriteLine("Problème, il manque le type d'action ou le nom de l'élément !");
        }


        // Ajoute un item
        private static bool ajouterItem(string nom, string archivenom, string jsoni, string url = null)
        {
            if (url == null)
                url = Librairie.getGitBranch() + "/bases/";

            bool continu = true;
            List<Item> objs = new List<Item>();

            if (File.Exists(jsoni))
            {
                try
                {
                    string json = File.ReadAllText(jsoni);

                    if (json != "")
                    {
                        objs = JsonConvert.DeserializeObject<List<Item>>(json);

                        foreach (Item obj in objs)
                        {
                            if (obj.name == nom)
                            {
                                Console.WriteLine("Heuuu, l'élément existe déjà...");
                                continu = false;
                            }
                        }
                    }
                }
                catch (Exception e)
                {
                    Console.WriteLine("Impossible de lire la liste des éléments existant !", e);
                    continu = false;
                }
            }

            if (continu)
            {
                url += archivenom;
                if (downloadItem(archivenom, url))
                    return parcoursArchiveItem(objs, archivenom, nom, jsoni);
                else return false;
            }
            else return false;
        }
        private static bool downloadItem(string zip, string url)
        {
            try
            {
                // Prepare un client http
                WebClient client = Librairie.getProxyClient();
                client.DownloadFile(url, zip);
                return true;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de télécharger l'archive source de cet item !", e);
                return false;
            }
        }
        private static bool parcoursArchiveItem(List<Item> objs, string zip, string nom, string jsoni)
        {
            try
            {
                string[] spt = nom.Split(Path.DirectorySeparatorChar);
                string namespce_slash = ""; // \Namepace\Namespace
                string namespce_point = ""; // .Namepace.Namespace
                string back_path = ""; // ../../
                string objlow = ""; // obj
                string objup = ""; // Obj
                string nomlow = ""; // namepace\namespace\obj
                string full_dash = ""; // namepace-namespace-obj
                List<string> paths = new List<string>();
                string[] toedit = new string[] { ".php", ".js", ".less", ".json" };

                for (int i = 0; i < spt.Length - 1; i++)
                {
                    string n = spt[i];
                    string s = n.Substring(0, 1).ToUpper();
                    namespce_slash += $@"\{s}";
                    namespce_point += $@".{s}";
                    back_path += $@"../";
                    if (n.Length > 1)
                    {
                        string l = n.Substring(1).ToLower();
                        namespce_slash += l;
                        namespce_point += l;
                    }
                }
                nomlow = nom.ToLower();
                full_dash = nomlow
                    .Replace('\\', '_')
                    .Replace('/', '-');
                objlow = spt[spt.Length - 1].ToLower();
                objup = objlow.Substring(0, 1).ToUpper();
                if (objlow.Length > 1) objup += objlow.Substring(1);


                // Ouvre l'archive
                int count = 0;
                int total = 0;
                using (ZipArchive arc = ZipFile.OpenRead(zip))
                {
                    // Parcour chaque entree
                    foreach (ZipArchiveEntry ent in arc.Entries)
                    {
                        // Si c'est un fichier
                        if (ent.Name != "")
                        {
                            total++;
                            if (extraireFichierItem(ent, ref paths, toedit, nomlow, full_dash, namespce_slash, namespce_point, back_path, objlow, objup))
                                count++;
                        }
                    }
                }

                if (count > 0)
                {
                    supprimerArchiveItem(zip);
                    ajouterJsonItem(objs, paths, nom, jsoni);
                    if (count == total)
                        Console.WriteLine("L'élément a été crée.");
                    else
                        Console.WriteLine("L'élément a été partiellement crée.");
                    return true;
                }
                else
                {
                    Console.WriteLine("L'élément n'a pas été crée.");
                    return false;
                }

            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire l'archive !", e);
                return false;
            }
        }
        private static bool extraireFichierItem(ZipArchiveEntry ent, ref List<string> paths, string[] toedit, string nomlow, string full_dash, string namespce_slash, string namespce_point, string back_path,  string objlow, string objup)
        {
            try
            {
                // modele\dto\*.php --> modele\dto\namepace\namespace\obh.php
                string file = ent.FullName
                    .Replace("{NAME_LOWER}", objlow)
                    .Replace("{PATH}", nomlow.Replace('\\', '/'));
                string path = Path.GetDirectoryName(file);
                string ext = Path.GetExtension(file);


                bool continu = true;
                if (!Directory.Exists(path))
                {
                    try
                    {
                        Directory.CreateDirectory(path);

                        Console.Write("Dossier : '");
                        Message.writeIn(ConsoleColor.Magenta, path);
                        Console.WriteLine("' ajouté.");
                    }
                    catch (Exception e)
                    {
                        Console.WriteLine("'" + path + "'");
                        Message.writeExcept("Impossible d'ajouter le(s) dossier(s) !", e);
                        continu = false;
                    }
                }

                if (continu)
                {
                    ent.ExtractToFile(file, true);
                    paths.Add(file);

                    // Extrait le fichier de l'archive
                    Console.Write("Fichier : '");
                    Message.writeIn(ConsoleColor.DarkGreen, file.Replace('/', Path.DirectorySeparatorChar));
                    Console.Write("' extrait (");
                    Message.writeIn(ConsoleColor.DarkYellow, Librairie.getFileSize(file));
                    Console.WriteLine(").");

                    if (toedit.Contains(ext)) {
                        try
                        {
                            // Modifie le fichier
                            string content = File.ReadAllText(file)
                                .Replace("{NAMESPACE_SLASH}", namespce_slash.Replace(" ", "_"))
                                .Replace("{NAMESPACE_POINT}", namespce_point.Replace(" ", "_"))
                                .Replace("{NAME_LOWER}", objlow.Replace(" ", "_"))
                                .Replace("{NAME_UPPER}", objup.Replace(" ", "_"))
                                .Replace("{FULL_DASH}", full_dash)
                                .Replace("{BACK_PATH}", back_path)
                                .Replace("{USER_NAME}", Environment.UserName)
                                .Replace("{PATH}", nomlow.Replace('\\', '/'));
                            File.WriteAllText(file, content);
                            return true;
                        }
                        catch (Exception e)
                        {
                            Message.writeExcept("Impossible d'éditer le fichier !", e);
                            return false;
                        }
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire le fichier !", e);
                return false;
            }
        }
        private static void supprimerArchiveItem(string zip)
        {
            try
            {
                // Supprime l'archive
                File.Delete(zip);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de supprimer l'archive !", e);
            }
        }
        private static void ajouterJsonItem(List<Item> objs, List<string> paths, string nom, string jsoni)
        {
            try
            {
                Item obj = new Item();
                obj.name = nom;
                obj.madeby = Environment.UserName;
                obj.created = DateTime.Now;
                obj.paths = paths;
                objs.Add(obj);

                string json = JsonConvert.SerializeObject(objs, Formatting.Indented);
                File.WriteAllText(jsoni, json);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'indexé l'élément !", e);
            }
        }


        // Suprime un item
        private static bool supprimerItem(string nom, string jsoni)
        {
            if (File.Exists(jsoni))
            {
                try
                {
                    string json = File.ReadAllText(jsoni);

                    if (json != "")
                    {
                        List<Item> objs = JsonConvert.DeserializeObject<List<Item>>(json);
                        return parcoursPourSupprimerItem(objs, nom, jsoni);
                    }
                    else
                    {
                        Console.WriteLine("Heuuu, aucun élément n'est indexé...");
                        return false;
                    }
                }
                catch (Exception e)
                {
                    Message.writeExcept($"Impossible de lire la liste des éléments existant !", e);
                    return false;
                }
            }
            else
            {
                Console.WriteLine("Heuuu, aucune liste d'élément n'a été trouvée...");
                return false;
            }
        }
        private static bool parcoursPourSupprimerItem(List<Item> objs, string nom, string jsoni)
        {
            bool trouve = false;
            bool continu = true;

            foreach (Item obj in objs)
            {
                if (obj.name == nom)
                {
                    objs.Remove(obj);
                    trouve = true;
                    foreach (string file in obj.paths)
                    {
                        // Complatibilite os
                        string fcomp = Librairie.remplaceDirSep(file);

                        if (File.Exists(fcomp))
                        {
                            supprimerFichierItem(fcomp, ref continu);
                        }
                        else
                        {
                            Console.Write("Le fichier '");
                            Message.writeIn(ConsoleColor.DarkYellow, fcomp);
                            Console.WriteLine("' est indexé mais est introuvable !");
                        }
                    }
                    break;
                }
            }

            if (trouve)
            {
                if (continu)
                {
                    supprimerJsonItem(objs, jsoni);
                    return true;
                }
                else
                {
                    Console.WriteLine("L'élément a été partiellement supprimé.");
                    return false;
                }
            }
            else
            {
                Console.WriteLine("Heuuu, l'élément n'existe pas...");
                return false;
            }
        }
        private static void supprimerFichierItem(string file, ref bool continu)
        {
            try
            {
                File.Delete(file);

                Console.Write("Fichier : '");
                Message.writeIn(ConsoleColor.Red, file);
                Console.WriteLine("' supprimé.");

                string folder = Path.GetDirectoryName(file);
                if (Directory.GetFiles(folder).Length == 0 && Directory.GetDirectories(folder).Length == 0 &&
                    Path.GetDirectoryName(Directory.GetCurrentDirectory()) != Path.GetDirectoryName(folder))
                {
                    supprimerDossierItem(folder, ref continu);
                }
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de supprimer le fichier !", e);
                continu = false;
            }
        }
        private static void supprimerDossierItem(string folder, ref bool continu)
        {
            try
            {
                Directory.Delete(folder);

                Console.Write("Dossier : '");
                Message.writeIn(ConsoleColor.Magenta, folder);
                Console.WriteLine("' supprimé.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de supprimer le dossier !", e);
                continu = false;
            }
        }
        private static void supprimerJsonItem(List<Item> objs, string jsoni)
        {
            try
            {
                string json = JsonConvert.SerializeObject(objs, Formatting.Indented);
                File.WriteAllText(jsoni, json);
            }
            catch (Exception e)
            {
                Message.writeExcept($"Impossible de désindexé l'élément !", e);
            }
        }


        // Liste les item
        private static void listerItem(string jsoni)
        {
            if (File.Exists(jsoni))
            {
                try
                {
                    string json = File.ReadAllText(jsoni);

                    if (json != "")
                    {
                        List<Item> objs = JsonConvert.DeserializeObject<List<Item>>(json);
                        List<Item> trier = objs.OrderBy(o => o.name).ToList();

                        Console.WriteLine("╔══════════════════════════════════╦══════════════╦═════════════════════════╦═══════════════════╗");
                        Console.WriteLine("║ Nom                              ║ Fichier      ║ Crée le                 ║ Par               ║");
                        Console.WriteLine("╠══════════════════════════════════╩══════════════╩═════════════════════════╩═══════════════════╣");

                        int count = 0;
                        foreach (Item obj in trier)
                        {
                            Console.WriteLine("║                                                                                               ║");
                            afficherUnItem(obj);
                            Console.WriteLine("╟───────────────────────────────────────────────────────────────────────────────────────────────╢");
                            count++;
                        }

                        Message.setPos(0, Console.CursorTop - 1);

                        if (count > 0)
                        {
                            Console.WriteLine("╚═══════════════════════════════════════════════════════════════════════════════════════════════╝");
                            Console.Write("Listage terminé. Il y a ");
                            Message.writeIn(ConsoleColor.DarkYellow, Librairie.toNumberFr(count));
                            Console.WriteLine(" élément(s).");
                        }
                        else
                        {
                            Console.WriteLine("╚══════════════════════════════════╩══════════════╩═════════════════════════╩═══════════════════╝");
                            Console.WriteLine("Heuuu, il n'y a aucun élément dans ce projet...");
                        }
                    }
                    else
                    {
                        Console.WriteLine("Heuuu, aucun élément n'est indexé...");
                    }
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible de lire la liste des éléments existant !", e);
                }
            }
            else
                Console.WriteLine("Heuuu, aucune liste d'élément n'a été trouvée...");
        }
        private static void afficherUnItem(Item obj)
        {
            Message.setPos(2, Console.CursorTop - 1);
            Message.writeIn(ConsoleColor.Magenta, obj.name);

            int count2 = 0;
            foreach (string file in obj.paths)
                if (File.Exists(Librairie.remplaceDirSep(file))) count2++;

            Message.setPos(37, Console.CursorTop);
            if (count2 == obj.paths.Count)
                Console.Write(Librairie.toNumberFr(obj.paths.Count));
            else
                Message.writeIn(ConsoleColor.DarkRed, $"{Librairie.toNumberFr(count2)} ({Librairie.toNumberFr(obj.paths.Count)})");

            Message.setPos(52, Console.CursorTop);
            Console.Write(obj.created.ToString());
            Message.setPos(78, Console.CursorTop);
            Console.WriteLine(obj.madeby);
        }


        // ########################################################################

    }
}
