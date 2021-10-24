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
@"aide                            Affiche la liste des commandes disponible.
cd [*chemin]                    Affiche ou change le dossier courant.
cls                             Nettoie la console.
com [-s|-a|-l] [nom]            Ajoute, liste, ou supprime un composant (controleur, vue, style,
                                script) avec le nom spécifié.
die                             Quitte Cody-PHP.
dl [url] [fichier]              Télécharge un fichier avec l'URL spécifiée.
exp                             Ouvre le projet dans l'explorateur de fichiers.
lib [-s|-a|-l] [nom]            Ajoute, liste, ou supprime une librairie.
                                avec le nom spécifié.
ls                              Affiche la liste des projets.
maj                             Met à jour Cody-PHP via le depot GitHub.
new [nom]                       Créer un nouveau projet avec le nom spécifié puis défini le dossier courant.
obj [-s|-a|-l] [nom]            Ajoute, liste, ou supprime un objet (classe dto, classe dao)
                                avec le nom spécifié.
rep                             Ouvre la dépôt GitHub de Cody-PHP.
run                             Ouvre le projet dans le navigateur.
vs                              Ouvre le projet dans Visual Studio Code.
wamp                            Lance WAMP Serveur et défini le dossier courant sur le www.

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
                // Si le projet existe
                if (File.Exists("project.json"))
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
                    Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody-PHP...");
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
                if (File.Exists("project.json"))
                {
                    try
                    {
                        // Ouvre dans le navigateur
                        ProcessStartInfo startInfo = new ProcessStartInfo();
                        startInfo.FileName = "code";
                        startInfo.Arguments = ".";
                        startInfo.CreateNoWindow = true;
                        startInfo.WindowStyle = ProcessWindowStyle.Hidden;

                        Process processTemp = new Process();
                        processTemp.StartInfo = startInfo;
                        processTemp.Start();
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible d'ouvrir Visual Studio Code !", e);
                    }
                }
                else
                    Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody-PHP...");
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Ouvre le projet dans le navigateur
        public static void runProjet(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Si le projet existe
                if (File.Exists("project.json"))
                {
                    // Ouvre dans le navigateur
                    string f = Path.GetFileName(Directory.GetCurrentDirectory());
                    try { Process.Start($"http://localhost/{f}"); }
                    catch { }
                }
                else
                    Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody-PHP...");
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


        // ########################################################################


        // Liste les projets du dossier courant
        public static void listProjet(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                {
                    Console.WriteLine("Récupération de la liste des dossiers...");
                    // Recupere tous les dossier du dossier courant
                    string[] dirs = Directory.GetDirectories(Directory.GetCurrentDirectory());
                    Console.WriteLine("Liste récupérée.");

                    if (dirs.Length > 0)
                    {
                        int count = 0;
                        Console.WriteLine();
                        Console.WriteLine("╔═════════════════════════════╦══════════════╦═════════════════╦═══════════════╦═════════════════════════╦════════════════╗");
                        Console.WriteLine("║ Nom                         ║ Fichier      ║ Taille          ║ Version       ║ Crée le                 ║ Par            ║");
                        Console.WriteLine("╠═════════════════════════════╩══════════════╩═════════════════╩═══════════════╩═════════════════════════╩════════════════╣");

                        foreach (string dir in dirs)
                        {
                            // Si ca contient un index.php c'est un projet
                            string f = $@"{dir}\project.json";
                            if (File.Exists(f))
                            {
                                Console.WriteLine("║                                                                                                                         ║");
                                calculerProjet(dir, f);
                                Console.WriteLine("╟─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────╢");
                                count++;
                            }
                        }

                        Console.SetCursorPosition(0, Console.CursorTop - 1);

                        if (count > 0)
                        {
                            Console.WriteLine("╚═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝");
                            Console.WriteLine();
                            Console.WriteLine("Listage terminé.");
                        }
                        else
                        {
                            Console.WriteLine("╚═════════════════════════════╩══════════════╩═════════════════╩═══════════════╩═════════════════════════╩════════════════╝");
                            Console.WriteLine();
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
            Console.SetCursorPosition(2, Console.CursorTop - 1);
            Message.writeIn(ConsoleColor.Magenta, Path.GetFileName(dir));

            // Calcule ne nb de fichier et la taille total
            try
            {
                long[] data = Librairie.getCountAndSizeFolder(dir);

                Console.SetCursorPosition(36, Console.CursorTop);
                Console.Write(data[0]);
                Console.SetCursorPosition(47, Console.CursorTop);
                Console.Write(data[1]);
            }
            catch (Exception e)
            {
                Console.WriteLine(e.Message);
                Console.SetCursorPosition(36, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                Console.SetCursorPosition(47, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
            }

            // Recupere les info de version du projet
            try
            {
                string json = File.ReadAllText(file);
                Projet inf = JsonConvert.DeserializeObject<Projet>(json);

                Console.SetCursorPosition(65, Console.CursorTop);
                Message.writeIn(inf.version == Program.version ? ConsoleColor.Green : ConsoleColor.DarkYellow, inf.version);
                Console.SetCursorPosition(81, Console.CursorTop);
                Console.Write(inf.creation.ToString());
                Console.SetCursorPosition(107, Console.CursorTop);
                Console.Write(inf.createur);
            }
            catch
            {

                Console.SetCursorPosition(65, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                Console.SetCursorPosition(81, Console.CursorTop);
                Message.writeIn(ConsoleColor.DarkRed, "Erreur");
                Console.SetCursorPosition(107, Console.CursorTop);
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

                if (!Directory.Exists(name))
                    creerDossierProjet(name);
                else
                    Console.WriteLine("Heuuu, le projet existe déjà, ou un dossier...");
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, seul le nom du nouveau projet est attendu !");
            else
                Console.WriteLine("Problème, il manque le nom du nouveau projet !");
        }
        private static void creerDossierProjet(string nom)
        {
            try
            {
                // Creer le dossier du projet
                Console.WriteLine("Création du dossier du projet...");
                Directory.CreateDirectory(nom);

                extractionArchiveProject(nom);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de créer le dossier du projet !", e);
            }
        }
        private static void extractionArchiveProject(string nom)
        {
            try
            {
                // Extrait l'archive des ressouces
                Console.WriteLine("Extraction de l'archive...");
                string zip = Path.Combine(nom, "base_projet.zip");
                File.WriteAllBytes(zip, Resources.base_projet);

                parcoursArchiveProjet(zip, nom);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire l'archive !", e);
            }
        }
        private static void parcoursArchiveProjet(string zip, string nom)
        {
            try
            {
                // Ouvre l'archive
                using (ZipArchive arc = ZipFile.OpenRead(zip))
                {
                    // Parcour chaque entree
                    foreach (ZipArchiveEntry ent in arc.Entries)
                    {
                        string path = Path.Combine(nom, ent.FullName); // projet\entry

                        // Si c'est un dossier
                        if (ent.Name == "")
                            extraireDossierProjet(path, ent.FullName);
                        // Si c'est un fichier
                        else
                            extraireFichierProjet(ent, path, ent.FullName, nom);
                    }
                }

                supprimerArchiveProjet(zip);
                creerJsonProject(nom);
                changerDossierProjet(nom);

                Console.WriteLine("Le projet a été crée.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire l'archive !", e);
            }
        }
        private static void extraireDossierProjet(string path, string file)
        {
            try
            {
                Console.Write("Dossier : '");
                Message.writeIn(ConsoleColor.Magenta, file);
                Console.WriteLine("'...");

                // Creer le dossier
                Directory.CreateDirectory(path);

                Console.WriteLine("Dossier ajouté.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'ajouter le dossier !", e);
            }
        }
        private static void extraireFichierProjet(ZipArchiveEntry ent, string path, string file, string name)
        {
            try
            {
                Console.Write("Fichier : '");
                Message.writeIn(ConsoleColor.DarkGreen, file);
                Console.WriteLine("'... ");

                // Extrait le fichier de l'archive
                ent.ExtractToFile(path);

                Console.Write("Fichier extrait pour un total de ");
                Message.writeIn(ConsoleColor.DarkYellow, new FileInfo(path).Length);
                Console.WriteLine(" octet(s).");

                // Fichiers ou l'on rajoute le nom
                string[] toedit = new string[]
                {
                    ".php",
                    ".js",
                    ".json",
                    ".less"
                };

                if (toedit.Contains(Path.GetExtension(file)))
                {
                    try
                    {
                        Console.WriteLine("Édition du fichier...");
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
        private static void supprimerArchiveProjet(string zip)
        {
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
        }
        private static void creerJsonProject(string name)
        {

            try
            {
                // Creer le cody json
                Console.WriteLine("Création du fichier d'information du projet...");

                Projet inf = new Projet();
                inf.createur = Environment.UserName;
                inf.version = Program.version;
                inf.creation = DateTime.Now;

                string json = JsonConvert.SerializeObject(inf, Formatting.Indented);
                File.WriteAllText(Path.Combine(name, "project.json"), json);

                Console.WriteLine("Fichier d'information crée.");
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
                Console.WriteLine("Changement du dossier courant...");

                Directory.SetCurrentDirectory(Path.Combine(Directory.GetCurrentDirectory(), name));

                Console.WriteLine("Dossier courant changé.");
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
            gestItem(cmd, "base_objet.zip", Resources.base_objet, "object.json");
        }

        // Gere les librairies
        public static void gestLibrairie(string[] cmd)
        {
            gestItem(cmd, "base_librairie.zip", Resources.base_librairie, "library.json");
        }

        // Gere les composants
        public static void gestComposant(string[] cmd)
        {
            gestItem(cmd, "base_composant.zip", Resources.base_composant, "component.json");
        }

        // Gere les item
        public static void gestItem(string[] cmd, string archivenom, byte[] archive, string jsoni)
        {
            if (cmd.Length == 1 || cmd.Length == 2)
            {
                // Si le projet existe
                if (File.Exists("project.json"))
                {
                    try
                    {
                        string json = File.ReadAllText("project.json");
                        Projet inf = JsonConvert.DeserializeObject<Projet>(json);
                        bool continu = true;

                        // Conflit de version
                        if (inf.version != Program.version)
                        {
                            Console.Write("Attention, ce projet est fait pour fonctionner avec la version ");
                            Message.writeIn(ConsoleColor.DarkYellow, inf.version);
                            Console.WriteLine(" de Cody-PHP.");
                            Console.Write("Vous êtes en version ");
                            Message.writeIn(ConsoleColor.Green, Program.version);
                            Console.WriteLine(", cela pourrait créer des problèmes de compatibilité, voulez vous continuer ?");

                            string rep = null;
                            do
                            {
                                Console.Write("(oui/non) : ");
                                rep = Console.ReadLine().Trim().ToLower();
                            }
                            while (rep != "oui" && rep != "non");
                            continu = rep == "oui";
                        }

                        if (continu)
                        {
                            switch (cmd[0].ToLower())
                            {
                                case "-l":
                                    if (cmd.Length == 1) listerItem(jsoni);
                                    else Console.WriteLine("Trop d'arguments !");
                                    break;

                                case "-s":
                                    if (cmd.Length == 2) supprimerItem(cmd[1].ToLower(), jsoni);
                                    else Console.WriteLine("Il manque le nom de l'élément !");
                                    break;

                                case "-a":
                                    if (cmd.Length == 2) ajouterItem(cmd[1].ToLower(), archivenom, archive, jsoni);
                                    else Console.WriteLine("Il manque le nom de l'élément !");
                                    break;

                                default:
                                    Console.WriteLine("Le type d'action est invalide !");
                                    break;
                            }
                        }
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible de lire le fichier d'information de Cody-PHP !", e);
                    }
                }
                else
                    Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody-PHP...");
            }
            else if (cmd.Length > 2)
                Console.WriteLine("Problème, trop d'arguments ont été données !");
            else
                Console.WriteLine("Problème, il manque le type d'action ou le nom de l'élément !");
        }

        // Ajoute un item
        private static void ajouterItem(string nom, string archivenom, byte[] archive, string jsoni)
        {
            if (nom != "global")
            {
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
                                if (obj.nom == nom)
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

                if (continu) extractionArchiveItem(objs, nom, archivenom, archive, jsoni);
            }
            else
                Console.WriteLine("Le nom global est reservé, impossible de l'utiliser.");
        }
        private static void extractionArchiveItem(List<Item> objs, string nom, string archivenom, byte[] archive, string jsoni)
        {
            try
            {
                // Extrait l'archive des ressouces
                Console.WriteLine("Extraction de l'archive...");
                string zip = archivenom;
                File.WriteAllBytes(zip, archive);

                parcoursArchiveItem(objs, zip, nom, jsoni);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire l'archive !", e);
            }
        }
        private static void parcoursArchiveItem(List<Item> objs, string zip, string nom, string jsoni)
        {
            try
            {
                string[] spt = nom.Split(Path.DirectorySeparatorChar);
                string namespce = ""; // \Namepace\Namespace
                string objlow = ""; // obj
                string objup = ""; // Obj
                string nomlow = nom.ToLower(); // \namepace\namespace\obj
                List<string> paths = new List<string>();

                for (int i = 0; i < spt.Length - 1; i++)
                {
                    string n = spt[i];
                    namespce += $@"\{n.Substring(0, 1).ToUpper()}";
                    if (n.Length > 1) namespce += n.Substring(1).ToLower();
                }
                objlow = spt[spt.Length - 1].ToLower();
                objup = objlow.Substring(0, 1).ToUpper();
                if (objlow.Length > 1) objup += objlow.Substring(1);

                // Ouvre l'archive
                using (ZipArchive arc = ZipFile.OpenRead(zip))
                {
                    // Parcour chaque entree
                    foreach (ZipArchiveEntry ent in arc.Entries)
                    {
                        // Si c'est un fichier
                        if (ent.Name != "")
                            extraireFichierItem(ent, ref paths, nomlow, namespce, objlow, objup);
                    }
                }

                supprimerArchiveItem(zip);
                ajouterJsonItem(objs, paths, nom, jsoni);

                Console.WriteLine("L'élément a été crée.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'extraire l'archive !", e);
            }
        }
        private static void extraireFichierItem(ZipArchiveEntry ent, ref List<string> paths, string nomlow, string namespce, string objlow, string objup)
        {
            try
            {
                // modele\dto\*.php --> modele\dto\namepace\namespace\obh.php
                string file = Path.Combine(Path.GetDirectoryName(ent.FullName), nomlow) + Path.GetExtension(ent.Name);
                string path = Path.GetDirectoryName(file);

                bool continu = true;
                if (!Directory.Exists(path))
                {
                    try
                    {
                        Console.Write("Dossier : '");
                        Message.writeIn(ConsoleColor.Magenta, path);
                        Console.WriteLine("'...");

                        Directory.CreateDirectory(path);

                        Console.WriteLine("Dossier ajouté.");
                    }
                    catch (Exception e)
                    {
                        Message.writeExcept("Impossible d'ajouter le(s) dossier(s) !", e);
                        continu = false;
                    }
                }

                if (continu)
                {
                    // Extrait le fichier de l'archive
                    Console.Write("Fichier : '");
                    Message.writeIn(ConsoleColor.DarkGreen, file);
                    Console.WriteLine("'...");

                    ent.ExtractToFile(file);
                    paths.Add(file);

                    Console.WriteLine("Fichier extrait.");

                    try
                    {
                        Console.WriteLine("Édition du fichier...");

                        // Modifie le fichier
                        string content = File.ReadAllText(file)
                            .Replace("{NAMESPACE}", namespce)
                            .Replace("{NAME_UPPER}", objup)
                            .Replace("{PATH}", nomlow.Replace('\\', '/'))
                            .Replace("{NAME_LOWER}", objlow);
                        File.WriteAllText(file, content);

                        Console.WriteLine("Fichier édité.");
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
        private static void supprimerArchiveItem(string zip)
        {
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
        }
        private static void ajouterJsonItem(List<Item> objs, List<string> paths, string nom, string jsoni)
        {
            try
            {
                Console.WriteLine("Indexation de l'élément...");
                
                Item obj = new Item();
                obj.nom = nom;
                obj.createur = Environment.UserName;
                obj.creation = DateTime.Now;
                obj.chemins = paths;
                objs.Add(obj);

                string json = JsonConvert.SerializeObject(objs, Formatting.Indented);
                File.WriteAllText(jsoni, json);

                Console.WriteLine("Elément indexé.");
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'indexé l'élément !", e);
            }
        }

        // Suprime un item
        private static void supprimerItem(string nom, string jsoni)
        {
            if (File.Exists(jsoni))
            {
                try
                {
                    string json = File.ReadAllText(jsoni);

                    if (json != "")
                    {
                        List<Item> objs = JsonConvert.DeserializeObject<List<Item>>(json);
                        parcoursPourSupprimerItem(objs, nom, jsoni);
                    }
                    else
                    {
                        Console.WriteLine("Heuuu, aucun élément n'est indexé...");
                    }
                }
                catch (Exception e)
                {
                    Message.writeExcept($"Impossible de lire la liste des éléments existant !", e);
                }
            }
            else
                Console.WriteLine("Heuuu, aucune liste d'élément n'a été trouvée...");
        }
        private static void parcoursPourSupprimerItem(List<Item> objs, string nom, string jsoni)
        {
            bool trouve = false;
            bool continu = true;

            foreach (Item obj in objs)
            {
                if (obj.nom == nom)
                {
                    objs.Remove(obj);
                    trouve = true;
                    foreach (string file in obj.chemins)
                    {
                        if (File.Exists(file))
                        {
                            supprimerFichierItem(file, ref continu);
                        }
                        else
                        {
                            Console.Write("Le fichier '");
                            Message.writeIn(ConsoleColor.DarkYellow, file);
                            Console.WriteLine("' est indexé mais est introuvable !");
                        }
                    }
                    break;
                }
            }

            if (trouve)
            {
                if (continu)
                    supprimerJsonItem(objs, jsoni);
                else
                    Console.WriteLine("L'élément a été partiellement supprimé.");
            }
            else
                Console.WriteLine("Heuuu, l'élément n'existe pas...");
        }
        private static void supprimerFichierItem(string file, ref bool continu)
        {
            try
            {
                Console.Write("Suppression du fichier '");
                Message.writeIn(ConsoleColor.Red, file);
                Console.WriteLine("'...");
                File.Delete(file);
                Console.WriteLine("Fichier supprimé.");

                string folder = Path.GetDirectoryName(file);
                if (Directory.GetFiles(folder).Length == 0 &&
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
                Console.Write("Suppression du dossier '");
                Message.writeIn(ConsoleColor.Magenta, folder);
                Console.WriteLine("'...");
                Directory.Delete(folder);
                Console.WriteLine("Dossier supprimé.");
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
                Console.WriteLine("Désindexation de l'élément...");

                string json = JsonConvert.SerializeObject(objs, Formatting.Indented);
                File.WriteAllText(jsoni, json);

                Console.WriteLine("Elément désindexé.");
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
                    Console.WriteLine("Récupération de la liste des items...");
                    string json = File.ReadAllText(jsoni);
                    Console.WriteLine("Liste récupérée.");

                    if (json != "")
                    {
                        List<Item> objs = JsonConvert.DeserializeObject<List<Item>>(json);

                        Console.WriteLine();
                        Console.WriteLine("╔═════════════════════════════╦══════════════╦═════════════════════════╦════════════════╗");
                        Console.WriteLine("║ Nom                         ║ Fichier      ║ Crée le                 ║ Par            ║");
                        Console.WriteLine("╠═════════════════════════════╩══════════════╩═════════════════════════╩════════════════╣");

                        int count = 0;
                        foreach (Item obj in objs)
                        {
                            Console.WriteLine("║                                                                                       ║");
                            affichierUnItem(obj);
                            Console.WriteLine("╟───────────────────────────────────────────────────────────────────────────────────────╢");
                            count++;
                        }

                        Console.SetCursorPosition(0, Console.CursorTop - 1);

                        if (count > 0)
                        {
                            Console.WriteLine("╚═══════════════════════════════════════════════════════════════════════════════════════╝");
                            Console.WriteLine();
                            Console.WriteLine("Listage terminé.");
                        }
                        else
                        {
                            Console.WriteLine("╚═════════════════════════════╩══════════════╩═════════════════════════╩════════════════╝");
                            Console.WriteLine();
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
                Console.WriteLine("Heuuu, aucune liste d'élément a été trouvée...");
        }
        private static void affichierUnItem(Item obj)
        {
            Console.SetCursorPosition(2, Console.CursorTop - 1);
            Message.writeIn(ConsoleColor.Magenta, obj.nom);

            int count2 = 0;
            foreach (string file in obj.chemins)
                if (File.Exists(file)) count2++;

            Console.SetCursorPosition(32, Console.CursorTop);
            if (count2 == obj.chemins.Count)
                Console.Write(obj.chemins.Count);
            else
                Message.writeIn(ConsoleColor.DarkRed, $"{count2} ({obj.chemins.Count})");


            Console.SetCursorPosition(47, Console.CursorTop);
            Console.Write(obj.creation.ToString());
            Console.SetCursorPosition(73, Console.CursorTop);
            Console.WriteLine(obj.createur);
        }


        // ########################################################################

    }
}
