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
using Cody_PHP.Properties;
using Newtonsoft.Json;

namespace Cody_PHP
{
    public class Commande
    {

        // Affiche l'aide
        public static void aideCom(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Affiche l'aide
                Console.WriteLine(
@"aide                                        Affiche la liste des commandes disponible.
cd [*chemin]                                Affiche ou change le dossier courant.
cls                                         Nettoie la console.
com [-s|-a|-r|-l] [nom] [nouveau nom]       Ajoute, renomme, liste, ou supprime un composant (controleur, vue, style,
                                            script) avec le nom spécifié.
die                                         Quitte Cody-PHP.
dl [url] [fichier]                          Télécharge un fichier avec l'URL spécifiée.
exp                                         Ouvre le projet dans l'explorateur de fichiers.
ls                                          Affiche la liste des projets.
maj                                         Met à jour Cody-PHP via le depot GitHub.
new [nom]                                   Créer un nouveau projet avec le nom spécifié.
obj [-s|-a |-r |-l] [nom] [nouveau nom]     Ajoute, renomme, liste, ou supprime un objet (classe dto, classe dao)
                                            avec le nom spécifié.
rep                                         Ouvre la dépôt GitHub de Cody-PHP.
vs                                          Ouvre le projet dans Visual Studio Code.
wamp                                        Lance WAMP Serveur et défini le dossier courant sur le www.

*: Argument facultatif.");
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
                Console.WriteLine($"Le dossier courant est : '{Directory.GetCurrentDirectory()}'.");
        }


        // Telecharge un fichier
        public static void downFile(string[] cmd)
        {
            if (cmd.Length == 2)
            {
                // Recupere les args
                string url = cmd[0];
                string file = cmd[1];
                // Prepapre l'animation
                int x = Console.CursorLeft;
                int y = Console.CursorTop;
                int x_barre = x + 1;
                int y_barre = y + 1;
                int x_byte = x + 53;
                long total_byte = 0;
                object lk = new object(); // lock
                bool ended = false;
                
                Console.WriteLine(
@"▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄
█                                                  █
▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀");

                Action<int, long, long> display_barre = (percent, receceid, total) =>
                {
                    // Progress
                    Console.SetCursorPosition(x_barre, y_barre);
                    Console.ForegroundColor = ConsoleColor.DarkGreen;
                    // Pas de percent / 2, le modulo est plus rapide que la division flotante
                    for (float i = 0; i < percent; i++)
                        if (i % 2 == 0) Console.Write("▓");
                    Console.ResetColor();

                    Console.SetCursorPosition(x_byte, y_barre);
                    Console.Write($"{percent}% ");
                    Message.writeIn(ConsoleColor.DarkYellow, receceid);
                    Console.Write(" octet(s) sur ");
                    Message.writeIn(ConsoleColor.DarkYellow, total);
                    Console.Write("...");
                };

                WebClient web = new WebClient();
                web.DownloadProgressChanged += (s, e) =>
                {
                    lock (lk)
                    {
                        // Progress
                        display_barre(e.ProgressPercentage, e.BytesReceived, e.TotalBytesToReceive);
                        if (total_byte == 0) total_byte = e.TotalBytesToReceive;
                        // Pour les tests
                        // dl https://launcher.mojang.com/v1/objects/a16d67e5807f57fc4e550299cf20226194497dc2/server.jar server.jar
                    }
                };

                web.DownloadFileCompleted += (s, e) =>
                {
                    lock (lk)
                    {
                        if (e.Error == null) // Si aucune exception
                        {
                            // Progress complete
                            display_barre(100, total_byte, total_byte);
                            Console.SetCursorPosition(x, y + 3);
                            Console.WriteLine("Téléchargement terminé.");
                        }
                        else
                        {
                            Console.SetCursorPosition(x, y + 3);
                            Message.writeExcept("Impossible de télécharger ce fichier !", e.Error);
                        }

                        ended = true;
                    }
                };

                // Telecharge en asyncrone
                web.DownloadFileTaskAsync(url, file);

                // Attends la fin et de delockage
                while (!ended || !Monitor.TryEnter(lk)) { }
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
                try { Process.Start("https://github.com/TheRake66/Cody-PHP"); }
                catch { }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Ferme l'app
        public static void quitterApp(string[] cmd)
        {
            if (cmd.Length == 0) 
                Environment.Exit(0); // Ferme avec un code 0
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Verifi les mise a jour
        public static void verifMAJ(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                {
                    Console.WriteLine("Vérification de la mise à jour...");

                    // Prepare un client http
                    WebClient client = new WebClient();
                    string remoteUri = "https://raw.githubusercontent.com/TheRake66/Cody-PHP/master/version";
                    string lastversion = client.DownloadString(remoteUri);

                    // Compare les version
                    if (lastversion.Equals(Program.version))
                        Console.WriteLine("Vous êtes à jour !");
                    else
                        Console.WriteLine($"La version {lastversion} est disponible, utilisez la commande 'rep' pour la télécharger !");
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible de vérifier les mise à jour !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument est attendu !");
        }


        // Ouvre dans l'explorateur
        public static void openExplorer(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                { 
                    // Ouvre dans le navigateur
                    Process.Start("explorer.exe", Directory.GetCurrentDirectory()); 
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible d'ouvrir l'explorateur !", e);
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
                try
                {
                    // Ouvre dans le navigateur
                    Process.Start("code", ".");
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible d'ouvrir Visual Studio Code !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Gere wamp
        public static void runWamp(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                {
                    bool founded = false;
                    string path = "";
                    string[] folder = new string[] { "wamp64", "wamp" };
                    string[] name = new string[] { "WAMP 64-bit", "WAMP" };

                    foreach (DriveInfo drive in DriveInfo.GetDrives())
                    {
                        if (drive.DriveType == DriveType.Fixed
                            && drive.IsReady)
                        {
                            Console.Write("Lecteur : '");
                            Message.writeIn(ConsoleColor.DarkYellow, drive.Name);
                            Console.WriteLine("'...");

                            for (int i = 0; i < folder.Length; i++)
                            {
                                string f = $@"{drive.Name}{folder[i]}";
                                Console.WriteLine($"Vérification du dossier {name[i]}...");
                                if (Directory.Exists(f))
                                {
                                    path = f;
                                    founded = true;
                                    break;
                                }
                            }

                            if (founded) break;
                        }
                    }

                    if (founded)
                    {
                        // Change le dossier
                        try
                        {
                            Directory.SetCurrentDirectory($@"{path}\www");
                            Console.WriteLine("Chemin WAMP trouvé.");
                        }
                        catch (Exception e)
                        {
                            Message.writeExcept("Impossible de définir le dossier WAMP !", e);
                        }
                        // Lance wamp
                        try
                        {
                            Process.Start($@"{path}\wampmanager.exe");
                            Console.WriteLine("WAMP lancé.");
                        }
                        catch (Exception e)
                        {
                            Message.writeExcept("Impossible de lancer WAMP !", e);
                        }
                    }
                    else
                        Console.WriteLine("Aucun dossier WAMP.");
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible de récupérer la liste des lecteur !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


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
                        Console.WriteLine("Nom                      Fichier   Taille         Version        Crée le                  Par");
                        Console.WriteLine("-------------------------------------------------------------------------------------------------------------");

                        foreach (string dir in dirs)
                        {
                            // Si ca contient un index.php c'est un projet
                            string f = $@"{dir}\cody.json";
                            if (File.Exists(f))
                            {
                                Console.SetCursorPosition(0, Console.CursorTop);
                                Message.writeIn(ConsoleColor.Magenta, Path.GetFileName(dir));

                                // Calcule ne nb de fichier et la taille total
                                try
                                {
                                    long[] data = Librairie.getCountAndSizeFolder(dir);

                                    Console.SetCursorPosition(25, Console.CursorTop);
                                    Console.Write(data[0]);
                                    Console.SetCursorPosition(35, Console.CursorTop);
                                    Console.Write(data[1]);
                                }
                                catch (Exception e)
                                {
                                    Console.WriteLine(e.Message);
                                    Console.SetCursorPosition(25, Console.CursorTop);
                                    Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                                    Console.SetCursorPosition(35, Console.CursorTop);
                                    Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                                }

                                // Recupere les info de version du projet
                                try
                                {
                                    string json = File.ReadAllText(f);
                                    Information inf = JsonConvert.DeserializeObject<Information>(json);

                                    Console.SetCursorPosition(50, Console.CursorTop);
                                    Message.writeIn(inf.version == Program.version ? ConsoleColor.Green : ConsoleColor.DarkYellow, inf.version);
                                    Console.SetCursorPosition(65, Console.CursorTop);
                                    Console.Write(inf.creation.ToString());
                                    Console.SetCursorPosition(90, Console.CursorTop);
                                    Console.Write(inf.createur);
                                }
                                catch
                                {

                                    Console.SetCursorPosition(50, Console.CursorTop);
                                    Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                                    Console.SetCursorPosition(65, Console.CursorTop);
                                    Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                                    Console.SetCursorPosition(90, Console.CursorTop);
                                    Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                                }

                                Console.WriteLine();
                                count++;
                            }
                        }

                        if (count > 0)
                            Console.WriteLine("Listage terminé.");
                        else
                            Console.WriteLine("Heuuu, il n'y a aucun projet dans ce dossier...");
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


        // Creer un nouveau projet
        public static void creerProjet(string[] cmd)
        {
            if (cmd.Length == 1)
            {
                // Verifi si projet existe deja
                string name = cmd[0];

                // Fichiers ou l'on rajoute le nom
                string[] toedit = new string[]
                {
                    "index.php",
                    @"vues\accueil.php",
                    "database.json"
                };

                if (!Directory.Exists(name))
                {
                    try
                    {
                        // Creer le dossier du projet
                        Console.WriteLine("Création du dossier du projet...");
                        Directory.CreateDirectory(name);

                        try
                        {
                            // Extrait l'archive des ressouces
                            Console.WriteLine("Extraction de l'archive...");
                            string zip = $@"{name}\projet_base.zip";
                            File.WriteAllBytes(zip, Resources.base_projet);

                            try
                            {
                                // Ouvre l'archive
                                using (ZipArchive arc = ZipFile.OpenRead(zip))
                                {
                                    // Parcour chaque entree
                                    foreach (ZipArchiveEntry ent in arc.Entries)
                                    {
                                        string path = Path.Combine(name, ent.FullName); // projet\entry
                                        string file = ent.FullName.Replace('/', '\\');

                                        // Si c'est un dossier
                                        if (ent.FullName.EndsWith("/"))
                                        {
                                            try
                                            {
                                                // Creer le dossier
                                                Directory.CreateDirectory(path);

                                                Console.Write("Dossier : '");
                                                Message.writeIn(ConsoleColor.Magenta, file);
                                                Console.WriteLine("'. Dossier ajouté.");
                                            }
                                            catch (Exception e)
                                            {
                                                Message.writeExcept("Impossible d'ajouter le dossier !", e);
                                            }
                                        }
                                        // Si c'est un fichier
                                        else
                                        {
                                            try
                                            {
                                                // Extrait le fichier de l'archive
                                                ent.ExtractToFile(path);
                                                ;
                                                Console.Write("Fichier : '");
                                                Message.writeIn(ConsoleColor.DarkGreen, file);
                                                Console.Write("'. Extraction du fichier, ");
                                                Message.writeIn(ConsoleColor.DarkYellow, new FileInfo(path).Length);
                                                Console.WriteLine(" octet(s) au total.");

                                                if (toedit.Contains(file))
                                                {
                                                    try
                                                    {
                                                        // Modifie le fichier
                                                        File.WriteAllText(path, File.ReadAllText(path).Replace("{PROJECT_NAME}", name));
                                                        Console.WriteLine("Édition du fichier terminé.");
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
                                    }
                                }

                                try
                                {
                                    // Supprime l'archive
                                    Console.WriteLine("Suppression de l'archive...");
                                    File.Delete(zip);
                                    Console.WriteLine("Archive supprimée.");
                                }
                                catch (Exception e)
                                {
                                    Message.writeExcept("Impossible de supprimer l'archive !", e);
                                }

                                try
                                {
                                    // Creer le cody json
                                    Console.WriteLine("Création du fichier d'information pour Cody-PHP...");

                                    Information inf = new Information();
                                    inf.createur = Environment.UserName;
                                    inf.version = Program.version;
                                    inf.creation = DateTime.Now;

                                    string json = JsonConvert.SerializeObject(inf, Formatting.Indented);
                                    File.WriteAllText($@"{name}\cody.json", json);

                                    Console.WriteLine("Fichier d'information crée.");
                                }
                                catch (Exception e)
                                {
                                    Message.writeExcept("Impossible de créer le fichier d'information pour Cody-PHP !", e);
                                }

                                Console.WriteLine("Le projet a été crée.");
                            }
                            catch (Exception e)
                            {
                                Message.writeExcept("Impossible d'extraire l'archive !", e);
                            }
                        }
                        catch (Exception e)
                        {
                            Message.writeExcept("Impossible de créer le dossier du projet !", e);
                        }
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible de créer le dossier du projet !", e);
                    }
                }
                else
                    Console.WriteLine($"Heuuu, le projet existe déjà, ou un dossier...");
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, seul le nom du nouveau projet est attendu !");
            else
                Console.WriteLine("Problème, il manque le nom du nouveau projet !");
        }


        // ########################################################################


        // Gere les objets
        public static void gestObjet(string[] cmd)
        {
            if (cmd.Length >= 1 && cmd.Length <= 3)
            {
                // Recupere tous les arguments possible
                string projet = cmd[0];
                string arg = cmd[1];

                string name = cmd.Length >= 3 ? cmd[2] : "";
                string upp = name.Length > 1 ?
                    name.Substring(0, 1).ToUpper() + name.Substring(1) :
                    name.ToUpper();

                string newname = cmd.Length >= 4 ? cmd[3] : "";
                string newupp = newname.Length > 1 ?
                    newname.Substring(0, 1).ToUpper() + newname.Substring(1) :
                    newname.ToUpper();

                string dto = $@"modeles\dto\dto{upp}.php",
                     dao = $@"modeles\dao\dao{upp}.php";
                string[] ordre = { dto, dao }; // Ordre des entries de l'archive
                string[] exclu = { "dBConnex.php", "param.php" }; // Fichier exclue du listage

                // Si le projet existe
                if (File.Exists("cody.json"))
                {
                }
                else
                    Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody-PHP...");
            }
            else if (cmd.Length > 4 )
                Console.WriteLine("Problème, trop d'arguments ont été données !");
            else
                Console.WriteLine("Problème, il manque le type d'action, le nom, et le nouveau nom du nouvel objet !");
        }

















































        // Gere les objets
        public static void gestComposant(string[] cmd)
        {
            if (cmd.Length >= 2 && cmd.Length <= 4)
            {
                // Recupere tous les arguments possible
                string projet = cmd[0];
                string arg = cmd[1];

                string name = cmd.Length >= 3 ? cmd[2] : "";
                string upp = name.Length > 1 ?
                    name.Substring(0, 1).ToUpper() + name.Substring(1) :
                    name.ToUpper();

                string newname = cmd.Length >= 4 ? cmd[3] : "";
                string newupp = newname.Length > 1 ?
                    newname.Substring(0, 1).ToUpper() + newname.Substring(1) :
                    newname.ToUpper();

                string con = $@"controleurs\controleur{upp}.php",
                          vue = $@"vues\vue{upp}.php",
                          scr = $@"scripts\script{upp}.js",
                          sty = $@"styles\style{upp}.css";
                string[] ordre = { scr, vue, con, sty }; // Ordre des entries de l'archive
                string[] exclu = 
                { 
                    "controleurPrincipal.php",
                    "haut.php",
                    "bas.php",
                    "haut.css",
                    "bas.css",
                    "haut.js",
                    "bas.js"
                }; // Fichier exclue du listage

                // Si le projet existe
                if (Directory.Exists(projet))
                {
                    // ***************************************************
                    // Ajoute un composant
                    if (arg == "-a")
                    {
                        if (cmd.Length == 3)
                        {
                            string zip = $@"{projet}\base_composant.zip";

                            try
                            {
                                // Extrait l'archive des ressources
                                Console.WriteLine("Extraction de l'archive...");
                                File.WriteAllBytes(zip, Resources.base_composant);

                                try
                                {
                                    // Ouvre l'archive
                                    Console.WriteLine("Extraction des fichiers...");
                                    using (ZipArchive arc = ZipFile.OpenRead(zip))
                                    {
                                        // Parcours l'archive
                                        for (int i = 0; i < arc.Entries.Count; i++)
                                        {
                                            string path = $@"{projet}\{ordre[i]}";

                                            // Si le composant existe pas
                                            if (!File.Exists(path))
                                            {
                                                try
                                                {
                                                    // Extrait le composant
                                                    arc.Entries[i].ExtractToFile(path);

                                                    Message.writeFull(Message.Type.Composant, ordre[i], "Extraction du fichier terminé.");

                                                    try
                                                    {
                                                        // Modifie le composant
                                                        File.WriteAllText(path, File.ReadAllText(path).Replace("{NAME}", upp));

                                                        Message.write(Message.Type.Edition, ordre[i]);
                                                        Console.Write("Edition du fichier,");
                                                        Message.writeData(new FileInfo(path).Length);
                                                        Console.WriteLine("octet(s) modifié.");
                                                    }
                                                    catch (Exception e)
                                                    {
                                                        Message.writeError(ordre[i], "Impossible d'éditer le fichier !", e);
                                                    }
                                                }
                                                catch (Exception e)
                                                {
                                                    Message.writeError(zip, "Impossible d'extraire le fichier !", e);
                                                }
                                            }
                                            else
                                            {
                                                Message.writeWarn(zip, "Le fichier existe déjà !");
                                            }
                                        }
                                    }


                                    try
                                    {
                                        // Supprime l'archive
                                        Console.WriteLine("Suppression de l'archive...");
                                        File.Delete(zip);
                                    }
                                    catch (Exception e)
                                    {
                                        Message.writeError(zip, "Impossible de supprimer l'archive !", e);
                                    }


                                    Console.WriteLine("Le composant a été ajouté.");
                                }
                                catch (Exception e)
                                {
                                    Message.writeError(zip, "Impossible d'ouvrir l'archive !", e);
                                }
                            }
                            catch (Exception e)
                            {
                                Message.writeError(zip, "Impossible d'extraire l'archive !", e);
                            }
                        }
                        else if (cmd.Length < 3)
                            Console.WriteLine("Problème, le nom du nouveau composant est attendu !");
                        else
                            Console.WriteLine("Problème, seul le nom du nouveau composant est attendu !");
                    }
                    // ***************************************************
                    // Supprime un composant
                    else if (arg == "-s")
                    {
                        if (cmd.Length == 3)
                        {
                            Console.WriteLine("Suppression des fichiers...");

                            foreach (string f in ordre)
                            {
                                string path = $@"{projet}\{f}";

                                // Si le composant exist
                                if (File.Exists(path))
                                {
                                    try
                                    {
                                        // Supprime le fichier
                                        File.Delete(path);

                                        Message.writeFull(Message.Type.Fichier, f, "Suppression du fichier terminé.");
                                    }
                                    catch (Exception e)
                                    {
                                        Message.writeError(f, "Impossible de supprimer le fichier !", e);
                                    }
                                }
                                else
                                {
                                    Message.writeWarn(f, "Impossible de trouver le fichier !");
                                }
                            }

                            Console.WriteLine("Le composant a été supprimé.");
                        }
                        else if (cmd.Length < 3)
                            Console.WriteLine("Problème, le nom d'un composant est attendu !");
                        else
                            Console.WriteLine("Problème, seul le nom d'un composant est attendu !");
                    }
                    // ***************************************************
                    // Renomme un composant
                    else if (arg == "-r")
                    {
                        if (cmd.Length == 4)
                        {
                            Console.WriteLine("Renommage des fichiers...");
                            
                            foreach (string f in ordre)
                            {
                                string path = $@"{projet}\{f}";
                                string newpath = $@"{projet}\{f.Replace(upp, newupp)}";

                                // Si le composant existe
                                if (File.Exists(path))
                                {
                                    try
                                    {
                                        // Renomme le fichier
                                        File.Move(path, newpath);
                                        Message.writeFull(Message.Type.Composant, f, "Renommage du fichier terminé.");

                                        try
                                        {
                                            // Modifie le composant
                                            File.WriteAllText(newpath, File.ReadAllText(newpath).Replace(upp, newupp));
                                            Message.writeFull(Message.Type.Edition, f, "Edition du fichier terminée.");
                                        }
                                        catch (Exception e)
                                        {
                                            Message.writeError(f, "Impossible d'éditer le fichier !", e);
                                        }
                                    }
                                    catch (Exception e)
                                    {
                                        Message.writeError(f, "Impossible de renommer le fichier !", e);
                                    }
                                }
                                else
                                {
                                    Message.writeWarn(f, "Le fichier est introuvable.");
                                }
                            }

                            Console.WriteLine("Le composant a été renommé.");
                        }
                        else if (cmd.Length < 4)
                            Console.WriteLine("Problème, le nom d'un composant et son nouveau nom sont attendus !");
                        else
                            Console.WriteLine("Problème, seul le nom d'un composant et son nouveau nom sont attendus !");
                    }
                    // ***************************************************
                    // Liste les composant
                    else if (arg == "-l")
                    {
                        if (cmd.Length == 2)
                        {
                            try
                            {
                                // Contient le nom du composant en cle et un tableau en valeur
                                // [0] nombre de fichier
                                // [1] taille total
                                Dictionary<string, long[]> trouve = new Dictionary<string, long[]>();

                                foreach (string d in ordre)
                                {
                                    // Parcours chaque fichier de chaque dossier
                                    foreach (string f in Directory.GetFiles(Path.GetDirectoryName($@"{projet}\{d}")))
                                    {
                                        // Si c'est un php ou un js ou un css et que ca n'est pas un fichier exclu
                                        if (new string[] { ".php", ".js", ".css" }.Contains(Path.GetExtension(f).ToLower()) && !exclu.Contains(Path.GetFileName(f)))
                                        {
                                            // Retire les x premiere lettre du fichier par rapport ou nom du dossier
                                            int sub =  Path.GetDirectoryName(d).Length - 1;
                                            string com = Path.GetFileNameWithoutExtension(f).Substring(sub);

                                            // Si deja trouver
                                            if (trouve.Keys.Contains(com))
                                            {
                                                // Inscremente les valeurs
                                                trouve[com][0]++;
                                                trouve[com][1] += new FileInfo(f).Length;
                                            }
                                            else
                                            {
                                                // Creer les valeurs
                                                trouve.Add(com, new long[] { 1, new FileInfo(f).Length });
                                            }
                                        }
                                    }
                                }

                                // Affiche les resultats
                                foreach (string k in trouve.Keys)
                                {
                                    Message.write(Message.Type.Composant, k);
                                    Console.Write("Composant trouvé,");
                                    Message.writeData(trouve[k][0]);
                                    Console.Write("fichier(s) faisant");
                                    Message.writeData(trouve[k][1]);
                                    Console.WriteLine("octet(s).");
                                }

                            }
                            catch (Exception e)
                            {
                                Message.writeError(projet, "Impossible de lister les composants !", e);
                            }
                        }
                        else
                            Console.WriteLine("Problème, aucun argument n'est attendu !");
                    }
                    // ***************************************************
                    else
                        Console.WriteLine("Le type d'action doit être '-a' pour ajouter, '-r' pour renommer, '-l' pour lister, ou '-s' pour supprimer.");
                }
                else
                    Console.WriteLine("Heuu, le projet n'existe pas...");
            }
            else if (cmd.Length > 4 )
                Console.WriteLine("Problème, trop d'arguments ont été données !");
            else
                Console.WriteLine("Problème, il manque le nom du projet, le type d'action et le nom du nouveu composant !");
        }

    }
}
