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
using GFFramework.Properties;

namespace GFFramework
{
    public class Commandes
    {

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
                    }
                    catch (Exception e)
                    {
                        Messages.writeError(path, "Impossible de changer de dossier !", e);
                    }
                }
                else
                    Console.WriteLine("Erreur, le chemin spécifié n'existe pas !");
            }
            else if (cmd.Length > 1)
                Console.WriteLine("Problème, seul un chemin est attendu.");
            else
                Console.WriteLine($"Le chemin actuel est: '{Directory.GetCurrentDirectory()}'.");
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
                object lk = new object(); // lock
                bool ended = false;

                WebClient web = new WebClient();
                web.DownloadProgressChanged += (s, e) =>
                {
                    lock (lk)
                    {
                        // Arrondi, quand ca arrive a 100% ca ne declenche pas cette event, il
                        // faut donc afficher 100% un % avant
                        bool rounded = e.ProgressPercentage == 99;

                        Console.SetCursorPosition(x, y);

                        Console.ForegroundColor = ConsoleColor.Magenta;
                        Console.Write("TELECHARGEMENT ");

                        Console.ResetColor();
                        Console.Write("[");

                        // Progress
                        Console.ForegroundColor = ConsoleColor.Green;
                        float percent = e.ProgressPercentage / 3;
                        for (float i = 1; i <= 100 / 3; i++)
                            Console.Write(i <= percent ? "#" : " ");

                        Console.ResetColor();
                        Console.Write($"] {(rounded ? 100 : e.ProgressPercentage)}% ===> ");

                        Console.ForegroundColor = ConsoleColor.DarkYellow;
                        Console.Write(rounded ? e.TotalBytesToReceive : e.BytesReceived);

                        Console.ResetColor();
                        Console.Write(" octet(s) sur ");

                        Console.ForegroundColor = ConsoleColor.DarkYellow;
                        Console.Write(e.TotalBytesToReceive);

                        Console.ResetColor();
                        Console.WriteLine("...");
                    }
                };
                web.DownloadFileCompleted += (s, e) =>
                {
                    lock (lk)
                    {
                        if (e.Error == null) // Si aucune exception
                        {
                            Console.WriteLine("Téléchargement terminé.");
                        }
                        else
                        {
                            Messages.writeError(url, "Impossible de télécharger ce fichier !", e.Error);
                        }
                    }
                    ended = true;
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
                        foreach (string f in dirs)
                        {
                            // Si ca contient un index.php c'est un projet
                            if (File.Exists($@"{f}\index.php"))
                            {
                                // Calcule ne nb de fichier et la taille total
                                long[] data = Librairies.getCountAndSizeFolder(f);

                                Messages.write(Messages.Type.Projet, Path.GetFileName(f));
                                Messages.writeData(data[0]);
                                Console.Write(" fichier(s) fai(on)t ");
                                Messages.writeData(data[1]);
                                Console.WriteLine(" octet(s).");
                            }
                        }
                    }
                    else
                        Console.WriteLine("Heuuu, il n'y a aucun projet dans ce dossier...");
                }
                catch (Exception e)
                {
                    Messages.writeError(Directory.GetCurrentDirectory(), "Impossible de lister les projets !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        // Affiche l'aide
        public static void aideCom(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                // Affiche l'aide
                Console.WriteLine(
@"aide                                    Affiche l'aide globale ou l'aide d'une commande spécifique.
cd [*chemin]                            Affiche ou change le dossier courant.
cl                                      Nettoie la console.
com [projet] [-s|-a|-r|-l] [nom]        Ajoute, renomme, liste, ou supprime un composant (controleur, vue,
                                        style, script) avec le nom spécifié  pour le projet spécifié.
die                                     Quitte GFframework.
dl [url] [chemin]                       Télécharge un fichier avec l'URL spécifiée.
git [*arguments]                        Exécute la commande git avec les arguments spécifié.
cls                                     Affiche la liste des projets du dossier courant.
maj                                     Met à jour GFframework via le depot GitHub.
new [nom]                               Créer un nouveau projet avec le nom spécifié.
obj [projet] [-s|-a |-r |-l] [nom]      Ajoute, renomme, liste, ou supprime un objet (classe dto, classe
                                        dao) avec le nom spécifié pour le projet spécifié.
rep                                     Ouvre la dépôt GitHub de GFframework.

*: Argument facultatif.
");
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
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
                try { Process.Start("https://github.com/TheRake66/GFfrramework"); }
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


        // Execute une instance git
        public static void execGit(string[] cmd)
        {
            try
            {
                // Reunis chaque arguments en array vers un string
                string arlin = "";
                foreach (string a in cmd)
                    arlin += a + " ";

                // Demarre en syncrone git
                ProcessStartInfo inf = new ProcessStartInfo
                {
                    FileName = "git.exe",
                    Arguments = arlin,
                    UseShellExecute = false,
                };
                Process.Start(inf).WaitForExit();
            }
            catch (Exception e)
            {
                Messages.writeError("git", "Impossible d'exécuter la commande git !", e);
            }
        }


        // Creer un nouveau projet
        public static void creerProjet(string[] cmd)
        {
            if (cmd.Length == 1)
            {
                // Verifi si projet existe deja
                string name = cmd[0];
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

                                                Messages.writeFull(Messages.Type.Dossier, file, "Ajout du dossier.");
                                            }
                                            catch (Exception e)
                                            {
                                                Messages.writeError(file, "Impossible d'ajouter le dossier !", e);
                                            }
                                        }
                                        // Si c'est un fichier
                                        else
                                        {
                                            try
                                            {
                                                // Extrait le fichier de l'archive
                                                ent.ExtractToFile(path);

                                                Messages.write(Messages.Type.Fichier, file);
                                                Console.Write(" Extraction du fichier, ");
                                                Messages.writeData(new FileInfo(path).Length);
                                                Console.WriteLine(" octet(s) au total.");
                                            }
                                            catch (Exception e)
                                            {
                                                Messages.writeError(file, "Impossible d'extraire le fichier !", e);
                                            }
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
                                    Messages.writeError(name, "Impossible de supprimer l'archive !", e);
                                }

                                Console.WriteLine("Le projet a été crée.");
                            }
                            catch (Exception e)
                            {
                                Messages.writeError(name, "Impossible d'extraire l'archive !", e);
                            }
                        }
                        catch (Exception e)
                        {
                            Messages.writeError(name, "Impossible de créer le dossier du projet !", e);
                        }
                    }
                    catch (Exception e)
                    {
                        Messages.writeError(name, "Impossible de créer le dossier du projet !", e);
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
                    string remoteUri = "https://raw.githubusercontent.com/TheRake66/GFframework/master/version";
                    string lastversion = client.DownloadString(remoteUri);
                    string currentversion = typeof(Program).Assembly.GetName().Version.ToString();

                    // Compare les version
                    if (lastversion.Equals(currentversion))
                        Console.WriteLine("Vous êtes à jour !");
                    else
                        Console.WriteLine($"La version {lastversion} est disponible, utilisez la commande 'rep' pour la télécharger !");
                }
                catch (Exception e)
                {
                    Messages.writeError("webclient", "Impossible de vérifier les mise à jour !", e);
                }
            }
            else
                Console.WriteLine("Problème, aucun argument est attendu !");
        }


        // Gere les objets
        public static void gestObjet(string[] cmd)
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

                string dto = $@"modeles\dto\dto{upp}.php",
                     dao = $@"modeles\dao\dao{upp}.php";
                string[] ordre = { dto, dao }; // Ordre des entries de l'archive
                string[] exclu = { "dBConnex.php", "param.php" }; // Fichier exclue du listage

                // Si le projet existe
                if (Directory.Exists(projet))
                {
                    // ***************************************************
                    // Ajoute un objet
                    if (arg == "-a")
                    {
                        if (cmd.Length == 3)
                        {
                            string zip = $@"{projet}\base_objet.zip";

                            try
                            {
                                // Extrait l'archive des ressources
                                Console.WriteLine("Extraction de l'archive...");
                                File.WriteAllBytes(zip, Resources.base_objet);

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

                                            // Si l'objet existe pas
                                            if (!File.Exists(path))
                                            {
                                                try
                                                {
                                                    // Extrait l'objet
                                                    arc.Entries[i].ExtractToFile(path);

                                                    Messages.writeFull(Messages.Type.Objet, ordre[i], "Extraction du fichier terminé.");

                                                    try
                                                    {
                                                        // Modifie l'objet
                                                        File.WriteAllText(path, File.ReadAllText(path).Replace("{NAME}", upp));

                                                        Messages.write(Messages.Type.Edition, ordre[i]);
                                                        Console.Write(" Edition du fichier, ");
                                                        Messages.writeData(new FileInfo(path).Length);
                                                        Console.WriteLine(" octet(s) modifié.");
                                                    }
                                                    catch (Exception e)
                                                    {
                                                        Messages.writeError(ordre[i], "Impossible d'éditer le fichier !", e);
                                                    }
                                                }
                                                catch (Exception e)
                                                {
                                                    Messages.writeError(zip, "Impossible d'extraire le fichier !", e);
                                                }
                                            }
                                            else
                                            {
                                                Messages.writeWarn(zip, "Le fichier existe déjà !");
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
                                        Messages.writeError(zip, "Impossible de supprimer l'archive !", e);
                                    }


                                    Console.WriteLine("L'objet a été ajouté.");
                                }
                                catch (Exception e)
                                {
                                    Messages.writeError(zip, "Impossible d'ouvrir l'archive !", e);
                                }
                            }
                            catch (Exception e)
                            {
                                Messages.writeError(zip, "Impossible d'extraire l'archive !", e);
                            }
                        }
                        else if (cmd.Length < 3)
                            Console.WriteLine("Problème, le nom du nouvel objet est attendu !");
                        else
                            Console.WriteLine("Problème, seul le nom du nouvel objet est attendu !");
                    }
                    // ***************************************************
                    // Supprime un objet
                    else if (arg == "-s")
                    {
                        if (cmd.Length == 3)
                        {
                            Console.WriteLine("Suppression des fichiers...");

                            foreach (string f in ordre)
                            {
                                string path = $@"{projet}\{f}";

                                // Si l'objet exist
                                if (File.Exists(path))
                                {
                                    try
                                    {
                                        // Supprime le fichier
                                        File.Delete(path);

                                        Messages.writeFull(Messages.Type.Fichier, f, "Suppression du fichier terminé.");
                                    }
                                    catch (Exception e)
                                    {
                                        Messages.writeError(f, "Impossible de supprimer le fichier !", e);
                                    }
                                }
                                else
                                {
                                    Messages.writeWarn(f, "Impossible de trouver le fichier !");
                                }
                            }

                            Console.WriteLine("L'objet a été supprimé.");
                        }
                        else if (cmd.Length < 3)
                            Console.WriteLine("Problème, le nom d'un objet est attendu !");
                        else
                            Console.WriteLine("Problème, seul le nom d'un objet est attendu !");
                    }
                    // ***************************************************
                    // Renomme un objet
                    else if (arg == "-r")
                    {
                        if (cmd.Length == 4)
                        {
                            Console.WriteLine("Renommage des fichiers...");
                            
                            foreach (string f in ordre)
                            {
                                string path = $@"{projet}\{f}";
                                string newpath = $@"{projet}\{f.Replace(upp, newupp)}";

                                // Si l'objet existe
                                if (File.Exists(path))
                                {
                                    try
                                    {
                                        // Renomme le fichier
                                        File.Move(path, newpath);
                                        Messages.writeFull(Messages.Type.Objet, f, "Renommage du fichier terminé.");

                                        try
                                        {
                                            // Modifie l'objet
                                            File.WriteAllText(newpath, File.ReadAllText(newpath).Replace(upp, newupp));
                                            Messages.writeFull(Messages.Type.Edition, f, "Edition du fichier terminée.");
                                        }
                                        catch (Exception e)
                                        {
                                            Messages.writeError(f, "Impossible d'éditer le fichier !", e);
                                        }
                                    }
                                    catch (Exception e)
                                    {
                                        Messages.writeError(f, "Impossible de renommer le fichier !", e);
                                    }
                                }
                                else
                                {
                                    Messages.writeWarn(f, "Le fichier est introuvable.");
                                }
                            }

                            Console.WriteLine("L'objet a été renommé.");
                        }
                        else if (cmd.Length < 4)
                            Console.WriteLine("Problème, le nom d'un objet et son nouveau nom sont attendus !");
                        else
                            Console.WriteLine("Problème, seul le nom d'un objet et son nouveau nom sont attendus !");
                    }
                    // ***************************************************
                    // Liste les objets
                    else if (arg == "-l")
                    {
                        if (cmd.Length == 2)
                        {
                            try
                            {
                                // Contient le nom de l'objet en cle et un tableau en valeur
                                // [0] nombre de fichier
                                // [1] taille total
                                Dictionary<string, long[]> trouve = new Dictionary<string, long[]>();

                                foreach (string d in ordre)
                                {
                                    // Parcours chaque fichier de chaque dossier
                                    foreach (string f in Directory.GetFiles(Path.GetDirectoryName($@"{projet}\{d}")))
                                    {
                                        // Si c'est un php et que ca n'est pas un fichier exclu
                                        if (Path.GetExtension(f).ToLower() == ".php" && !exclu.Contains(Path.GetFileName(f)))
                                        {
                                            // Retire les 3 premiere lettre du fichier
                                            string obj = Path.GetFileNameWithoutExtension(f).Substring(3);

                                            // Si deja trouver
                                            if (trouve.Keys.Contains(obj))
                                            {
                                                // Inscremente les valeurs
                                                trouve[obj][0]++;
                                                trouve[obj][1] += new FileInfo(f).Length;
                                            }
                                            else
                                            {
                                                // Creer les valeurs
                                                trouve.Add(obj, new long[] { 1, new FileInfo(f).Length });
                                            }
                                        }
                                    }
                                }

                                // Affiche les resultats
                                foreach (string k in trouve.Keys)
                                {
                                    Messages.write(Messages.Type.Objet, k);
                                    Console.Write(" Objet trouvé, ");
                                    Messages.writeData(trouve[k][0]);
                                    Console.Write(" fichier(s) faisant ");
                                    Messages.writeData(trouve[k][1]);
                                    Console.WriteLine(" octet(s).");
                                }

                            }
                            catch (Exception e)
                            {
                                Messages.writeError(projet, "Impossible de lister les objets !", e);
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
                Console.WriteLine("Problème, il manque le nom du projet, le type d'action et le nom du nouvel objet !");
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

                                                    Messages.writeFull(Messages.Type.Composant, ordre[i], "Extraction du fichier terminé.");

                                                    try
                                                    {
                                                        // Modifie le composant
                                                        File.WriteAllText(path, File.ReadAllText(path).Replace("{NAME}", upp));

                                                        Messages.write(Messages.Type.Edition, ordre[i]);
                                                        Console.Write(" Edition du fichier, ");
                                                        Messages.writeData(new FileInfo(path).Length);
                                                        Console.WriteLine(" octet(s) modifié.");
                                                    }
                                                    catch (Exception e)
                                                    {
                                                        Messages.writeError(ordre[i], "Impossible d'éditer le fichier !", e);
                                                    }
                                                }
                                                catch (Exception e)
                                                {
                                                    Messages.writeError(zip, "Impossible d'extraire le fichier !", e);
                                                }
                                            }
                                            else
                                            {
                                                Messages.writeWarn(zip, "Le fichier existe déjà !");
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
                                        Messages.writeError(zip, "Impossible de supprimer l'archive !", e);
                                    }


                                    Console.WriteLine("Le composant a été ajouté.");
                                }
                                catch (Exception e)
                                {
                                    Messages.writeError(zip, "Impossible d'ouvrir l'archive !", e);
                                }
                            }
                            catch (Exception e)
                            {
                                Messages.writeError(zip, "Impossible d'extraire l'archive !", e);
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

                                        Messages.writeFull(Messages.Type.Fichier, f, "Suppression du fichier terminé.");
                                    }
                                    catch (Exception e)
                                    {
                                        Messages.writeError(f, "Impossible de supprimer le fichier !", e);
                                    }
                                }
                                else
                                {
                                    Messages.writeWarn(f, "Impossible de trouver le fichier !");
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
                                        Messages.writeFull(Messages.Type.Composant, f, "Renommage du fichier terminé.");

                                        try
                                        {
                                            // Modifie le composant
                                            File.WriteAllText(newpath, File.ReadAllText(newpath).Replace(upp, newupp));
                                            Messages.writeFull(Messages.Type.Edition, f, "Edition du fichier terminée.");
                                        }
                                        catch (Exception e)
                                        {
                                            Messages.writeError(f, "Impossible d'éditer le fichier !", e);
                                        }
                                    }
                                    catch (Exception e)
                                    {
                                        Messages.writeError(f, "Impossible de renommer le fichier !", e);
                                    }
                                }
                                else
                                {
                                    Messages.writeWarn(f, "Le fichier est introuvable.");
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
                                    Messages.write(Messages.Type.Composant, k);
                                    Console.Write(" Composant trouvé, ");
                                    Messages.writeData(trouve[k][0]);
                                    Console.Write(" fichier(s) faisant ");
                                    Messages.writeData(trouve[k][1]);
                                    Console.WriteLine(" octet(s).");
                                }

                            }
                            catch (Exception e)
                            {
                                Messages.writeError(projet, "Impossible de lister les composants !", e);
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
