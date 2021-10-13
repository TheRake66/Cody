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

        public static void changeDir(string[] cmd)
        {
            if (cmd.Length == 1)
            {
                string path = cmd[0];

                if (Directory.Exists(path))
                {
                    try
                    {
                        Directory.SetCurrentDirectory(path);
                    }
                    catch (Exception e)
                    {
                        Console.ForegroundColor = ConsoleColor.DarkRed;
                        Console.Write("ERROR ");
                        Console.ResetColor();
                        Console.WriteLine($"{path} ===> Impossible de changer de dossier !");
                        Console.WriteLine($"Message: {e.Message}");
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


        public static void downFile(string[] cmd)
        {
            if (cmd.Length == 2)
            {
                int x = Console.CursorLeft;
                int y = Console.CursorTop;
                string url = cmd[0];
                string file = cmd[1];
                object lk = new object();
                bool ended = false;

                WebClient web = new WebClient();
                web.DownloadProgressChanged += (s, e) =>
                {
                    lock (lk)
                    {
                        bool rounded = e.ProgressPercentage == 99;

                        Console.SetCursorPosition(x, y);

                        Console.ForegroundColor = ConsoleColor.Magenta;
                        Console.Write("TELECHARGEMENT ");

                        Console.ResetColor();
                        Console.Write("[");

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
                    if (e.Error == null)
                    {
                        lock (lk)
                            Console.WriteLine("Téléchargement terminé.");
                    }
                    else
                    {
                        Console.ForegroundColor = ConsoleColor.DarkRed;
                        Console.Write("ERROR ");
                        Console.ResetColor();
                        Console.WriteLine($"{url} ===> Impossible de télécharger ce fichier !");
                        Console.WriteLine($"Message: {e.Error.Message}");
                    }
                    ended = true;
                };
                web.DownloadFileTaskAsync(url, file);

                // dl https://launcher.mojang.com/v1/objects/a16d67e5807f57fc4e550299cf20226194497dc2/server.jar aa.jar
                while (!ended || !Monitor.TryEnter(lk)) { }
            }
            else if (cmd.Length > 2)
                Console.WriteLine("Problème, seul l'url et le chemin du fichier sont attendus !");
            else
                Console.WriteLine("Problème, il manque l'url et le chemin du fichier !");
        }


        public static void listProjet(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                {
                    string[] dirs = Directory.GetDirectories(Directory.GetCurrentDirectory());

                    if (dirs.Length > 0)
                    {
                        foreach (string f in dirs)
                        {
                            if (File.Exists($@"{f}\index.php"))
                            {
                                long[] data = Librairies.getCountAndSizeFolder(f);

                                Console.ForegroundColor = ConsoleColor.Magenta;
                                Console.Write("PROJET ");

                                Console.ResetColor();
                                Console.Write($"{Path.GetFileName(f)} ===> ");

                                Console.ForegroundColor = ConsoleColor.DarkYellow;
                                Console.Write(data[0]);

                                Console.ResetColor();
                                Console.Write(" fichier(s) fai(on)t ");

                                Console.ForegroundColor = ConsoleColor.DarkYellow;
                                Console.Write(data[1]);

                                Console.ResetColor();
                                Console.WriteLine(" octet(s).");
                            }
                        }
                    }
                    else
                        Console.WriteLine("Heuuu, il n'y a aucun projet dans ce dossier...");
                }
                catch (Exception e)
                {
                    Console.ForegroundColor = ConsoleColor.DarkRed;
                    Console.Write("ERROR ");
                    Console.ResetColor();
                    Console.WriteLine($"{Directory.GetCurrentDirectory()} ===> Impossible de lister les projets !");
                    Console.WriteLine($"Message: {e.Message}");
                }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        public static void aideCom(string[] cmd)
        {
            if (cmd.Length == 0)
            {
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


        public static void clearCons(string[] cmd)
        {
            if (cmd.Length == 0)
                Console.Clear();
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        public static void openRepo(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try { Process.Start("https://github.com/TheRake66/GFfrramework"); }
                catch { }
            }
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        public static void quitterApp(string[] cmd)
        {
            if (cmd.Length == 0) 
                Environment.Exit(0);
            else
                Console.WriteLine("Problème, aucun argument n'est attendu !");
        }


        public static void execGit(string[] cmd)
        {
            try
            {
                string arlin = "";
                foreach (string a in cmd)
                    arlin += a + " ";


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
                Console.ForegroundColor = ConsoleColor.DarkRed;
                Console.Write("ERROR ");
                Console.ResetColor();
                Console.WriteLine($"git ===> Impossible d'exécuter la commande git !");
                Console.WriteLine($"Message: {e.Message}");
            }
        }


        public static void creerProjet(string[] cmd)
        {
            if (cmd.Length == 1)
            {
                string name = cmd[0];
                if (!Directory.Exists(name))
                {
                    try
                    {
                        Console.WriteLine("Création du dossier du projet...");
                        Directory.CreateDirectory(name);

                        try
                        {
                            Console.WriteLine("Extraction de l'archive...");
                            string zip = $@"{name}\projet_base.zip";
                            File.WriteAllBytes(zip, Resources.base_projet);


                            try
                            {

                                using (ZipArchive arc = ZipFile.OpenRead(zip))
                                {
                                    foreach (ZipArchiveEntry ent in arc.Entries)
                                    {
                                        string path = Path.Combine(name, ent.FullName);
                                        string file = ent.FullName.Replace('/', '\\');

                                        Console.ForegroundColor = ConsoleColor.Magenta;
                                        if (ent.FullName.EndsWith("/"))
                                        {
                                            try
                                            {
                                                Directory.CreateDirectory(path);

                                                Console.Write("DOSSIER ");

                                                Console.ResetColor();
                                                Console.WriteLine($"{file} ===> ajout du dossier.");
                                            }
                                            catch (Exception e)
                                            {
                                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                                Console.Write("ERROR ");
                                                Console.ResetColor();
                                                Console.WriteLine($"{file} ===> impossible d'ajouter le dossier.");
                                                Console.WriteLine($"Message: {e.Message}");
                                            }
                                        }
                                        else
                                        {
                                            try
                                            {
                                                ent.ExtractToFile(path);

                                                Console.Write("FICHIER ");

                                                Console.ResetColor();
                                                Console.Write($"{file} ===> extraction du fichier, ");

                                                Console.ForegroundColor = ConsoleColor.DarkYellow;
                                                Console.Write(new FileInfo(path).Length);

                                                Console.ResetColor();
                                                Console.WriteLine(" octet(s) au total.");
                                            }
                                            catch (Exception e)
                                            {
                                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                                Console.Write("ERROR ");
                                                Console.ResetColor();
                                                Console.WriteLine($"{file} ===> impossible d'extraire le fichier.");
                                                Console.WriteLine($"Message: {e.Message}");
                                            }
                                        }

                                    }
                                }


                                try
                                {
                                    Console.WriteLine("Suppression de l'archive...");
                                    File.Delete(zip);
                                }
                                catch (Exception e)
                                {
                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                    Console.Write("ERROR ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{name} ===> Impossible de supprimer l'archive !");
                                    Console.WriteLine($"Message: {e.Message}");
                                }


                                Console.WriteLine("Le projet a été crée.");
                            }
                            catch (Exception e)
                            {
                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                Console.Write("ERROR ");
                                Console.ResetColor();
                                Console.WriteLine($"{name} ===> Impossible d'extraire l'archive !");
                                Console.WriteLine($"Message: {e.Message}");
                            }
                        }
                        catch (Exception e)
                        {
                            Console.ForegroundColor = ConsoleColor.DarkRed;
                            Console.Write("ERROR ");
                            Console.ResetColor();
                            Console.WriteLine($"{name} ===> Impossible de créer le dossier du projet !");
                            Console.WriteLine($"Message: {e.Message}");
                        }
                    }
                    catch (Exception e)
                    {
                        Console.ForegroundColor = ConsoleColor.DarkRed;
                        Console.Write("ERROR ");
                        Console.ResetColor();
                        Console.WriteLine($"{name} ===> Impossible de créer le dossier du projet !");
                        Console.WriteLine($"Message: {e.Message}");
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


        public static void verifMAJ(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try
                {
                    Console.WriteLine("Vérification de la mise à jour...");

                    WebClient client = new WebClient();
                    string remoteUri = "https://raw.githubusercontent.com/TheRake66/GFframework/master/version";
                    string lastversion = client.DownloadString(remoteUri);
                    string currentversion = typeof(Program).Assembly.GetName().Version.ToString();

                    if (lastversion.Equals(currentversion))
                        Console.WriteLine("Vous êtes à jour !");
                    else
                        Console.WriteLine($"La version {lastversion} est disponible, utilisez la commande 'rep' pour la télécharger !");
                }
                catch (Exception e)
                {
                    Console.ForegroundColor = ConsoleColor.DarkRed;
                    Console.Write("ERROR ");
                    Console.ResetColor();
                    Console.WriteLine("Impossible de vérifier les mise à jour !");
                    Console.WriteLine($"Message: {e.Message}");
                }
            }
            else
                Console.WriteLine("Problème, aucun argument est attendu !");
        }


        public static void gestComposant(string[] cmd)
        {
            if (cmd.Length == 3)
            {
                string projet = cmd[0];
                string arg = cmd[1];
                string name = cmd[2];

                string upp = name.Length > 1 ?
                    name.Substring(0, 1).ToUpper() + name.Substring(1) :
                    name.ToUpper();

                if (Directory.Exists(projet))
                {
                    string con = $@"controleurs\controleur{upp}.php",
                           vue = $@"vues\vue{upp}.php",
                           scr = $@"scripts\script{upp}.js",
                           sty = $@"styles\style{upp}.css";
                    string[] ordre = { scr, vue, con, sty };

                    // ***************************************************
                    if (arg == "-a")
                    {
                        string zip = $@"{projet}\base_composant.zip";

                        try
                        {
                            Console.WriteLine("Extraction de l'archive...");
                            File.WriteAllBytes(zip, Resources.base_composant);

                            try
                            {
                                Console.WriteLine("Extraction des fichiers...");
                                using (ZipArchive arc = ZipFile.OpenRead(zip))
                                {
                                    for (int i = 0; i < arc.Entries.Count; i++)
                                    {
                                        string path = $@"{projet}\{ordre[i]}";

                                        if (!File.Exists(path))
                                        {
                                            try
                                            {
                                                arc.Entries[i].ExtractToFile(path);

                                                Console.ForegroundColor = ConsoleColor.Magenta;
                                                Console.Write("COMPOSANT ");
                                                Console.ResetColor();
                                                Console.WriteLine($"{ordre[i]} ===> extraction du fichier terminé.");


                                                try
                                                {
                                                    File.WriteAllText(path, File.ReadAllText(path).Replace("{NAME}", upp));

                                                    Console.ForegroundColor = ConsoleColor.Magenta;
                                                    Console.Write("EDITION ");
                                                    Console.ResetColor();
                                                    Console.Write($"{ordre[i]} ===> édition du fichier, ");
                                                    Console.ForegroundColor = ConsoleColor.DarkYellow;
                                                    Console.Write(new FileInfo(path).Length);
                                                    Console.ResetColor();
                                                    Console.WriteLine(" octet(s) modifié.");
                                                }
                                                catch (Exception e)
                                                {
                                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                                    Console.Write("ERROR ");
                                                    Console.ResetColor();
                                                    Console.WriteLine($"{ordre[i]} ===> impossible d'éditer le fichier.");
                                                    Console.WriteLine($"Message: {e.Message}");
                                                }
                                            }
                                            catch (Exception e)
                                            {
                                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                                Console.Write("ERROR ");
                                                Console.ResetColor();
                                                Console.WriteLine($"{ordre[i]} ===> impossible d'extraire le fichier.");
                                                Console.WriteLine($"Message: {e.Message}");
                                            }
                                        }
                                        else
                                        {
                                            Console.ForegroundColor = ConsoleColor.DarkYellow;
                                            Console.Write("ATTENTION ");
                                            Console.ResetColor();
                                            Console.WriteLine($"{ordre[i]} ===> le fichier existe déjà.");
                                        }
                                    }
                                }


                                try
                                {
                                    Console.WriteLine("Suppression de l'archive...");
                                    File.Delete(zip);
                                }
                                catch (Exception e)
                                {
                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                    Console.Write("ERROR ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{zip} ===> Impossible de supprimer l'archive !");
                                    Console.WriteLine($"Message: {e.Message}");
                                }


                                Console.WriteLine("Le composant a été ajouté.");
                            }
                            catch (Exception e)
                            {
                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                Console.Write("ERROR ");
                                Console.ResetColor();
                                Console.WriteLine($"{name} ===> Impossible d'ouvrir l'archive !");
                                Console.WriteLine($"Message: {e.Message}");
                            }
                        }
                        catch (Exception e)
                        {
                            Console.ForegroundColor = ConsoleColor.DarkRed;
                            Console.Write("ERROR ");
                            Console.ResetColor();
                            Console.WriteLine($"{zip} ===> Impossible d'extraire l'archive !");
                            Console.WriteLine($"Message: {e.Message}");
                        }
                    }
                    // ***************************************************
                    else if (arg == "-s")
                    {
                        Console.WriteLine("Suppression des fichiers...");

                        foreach (string f in ordre)
                        {
                            string path = $@"{projet}\{f}";
                            if (File.Exists(path))
                            {
                                try
                                {
                                    File.Delete(path);

                                    Console.ForegroundColor = ConsoleColor.Magenta;
                                    Console.Write("COMPOSANT ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{f} ===> suppression du fichier terminé.");
                                }
                                catch (Exception e)
                                {
                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                    Console.Write("ERROR ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{f} ===> impossible de supprimer le fichier !");
                                    Console.WriteLine($"Message: {e.Message}");
                                }
                            }
                            else
                            {
                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                Console.Write("ERROR ");
                                Console.ResetColor();
                                Console.WriteLine($"{f} ===> fichier introuvale !");
                            }
                        }

                        Console.WriteLine("Le composant a été supprimé.");
                    }
                    // ***************************************************
                    else if (arg == "-r")
                    {
                        Console.WriteLine("Le composant a été renommé.");
                    }
                    // ***************************************************
                    else if (arg == "-l")
                    {
                    }
                    // ***************************************************
                    else
                        Console.WriteLine("Le type d'action doit être '-a' pour ajouter, '-r' pour renommer, '-l' pour lister, ou '-s' pour supprimer.");
                }
                else
                    Console.WriteLine("Heuu, le projet n'existe pas...");

            }
            else if (cmd.Length > 3)
                Console.WriteLine("Problème, seul le nom du projet, le type d'action et le nom du nouvel objet sont attendus !");
            else
                Console.WriteLine("Problème, il manque le nom du projet, le type d'action et le nom du nouvel objet !");
        }


        public static void gestObjet(string[] cmd)
        {
            if (cmd.Length == 2 || cmd.Length == 3)
            {
                string projet = cmd[0];
                string arg = cmd[1];
                string name = cmd[2];

                string upp = name.Length > 1 ?
                    name.Substring(0, 1).ToUpper() + name.Substring(1) :
                    name.ToUpper();

                if (Directory.Exists(projet))
                {
                    string dto = $@"modeles\dto\dto{upp}.php",
                         dao = $@"modeles\dao\dao{upp}.php";

                    string[] ordre = { dto, dao };

                    // ***************************************************
                    if (arg == "-a")
                    {
                        string zip = $@"{projet}\base_objet.zip";

                        try
                        {
                            Console.WriteLine("Extraction de l'archive...");
                            File.WriteAllBytes(zip, Resources.base_objet);

                            try
                            {
                                Console.WriteLine("Extraction des fichiers...");
                                using (ZipArchive arc = ZipFile.OpenRead(zip))
                                {
                                    for (int i = 0; i < arc.Entries.Count; i++)
                                    {
                                        string path = $@"{projet}\{ordre[i]}";

                                        if (!File.Exists(path))
                                        {
                                            try
                                            {
                                                arc.Entries[i].ExtractToFile(path);

                                                Console.ForegroundColor = ConsoleColor.Magenta;
                                                Console.Write("OBJET ");
                                                Console.ResetColor();
                                                Console.WriteLine($"{ordre[i]} ===> extraction du fichier terminé.");

                                                try
                                                {
                                                    File.WriteAllText(path, File.ReadAllText(path).Replace("{NAME}", upp));

                                                    Console.ForegroundColor = ConsoleColor.Magenta;
                                                    Console.Write("EDITION ");
                                                    Console.ResetColor();
                                                    Console.Write($"{ordre[i]} ===> édition du fichier, ");
                                                    Console.ForegroundColor = ConsoleColor.DarkYellow;
                                                    Console.Write(new FileInfo(path).Length);
                                                    Console.ResetColor();
                                                    Console.WriteLine(" octet(s) modifié.");
                                                }
                                                catch (Exception e)
                                                {
                                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                                    Console.Write("ERROR ");
                                                    Console.ResetColor();
                                                    Console.WriteLine($"{ordre[i]} ===> impossible d'éditer le fichier.");
                                                    Console.WriteLine($"Message: {e.Message}");
                                                }
                                            }
                                            catch (Exception e)
                                            {
                                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                                Console.Write("ERROR ");
                                                Console.ResetColor();
                                                Console.WriteLine($"{zip} ===> Impossible d'extraire le fichier !");
                                                Console.WriteLine($"Message: {e.Message}");
                                            }
                                        }
                                        else
                                        {
                                            Console.ForegroundColor = ConsoleColor.DarkYellow;
                                            Console.Write("ATTENTION ");
                                            Console.ResetColor();
                                            Console.WriteLine($"{ordre[i]} ===> le fichier existe déjà.");
                                        }
                                    }
                                }


                                try
                                {
                                    Console.WriteLine("Suppression de l'archive...");
                                    File.Delete(zip);
                                }
                                catch (Exception e)
                                {
                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                    Console.Write("ERROR ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{zip} ===> Impossible de supprimer l'archive !");
                                    Console.WriteLine($"Message: {e.Message}");
                                }


                                Console.WriteLine("L'objet a été ajouté.");
                            }
                            catch (Exception e)
                            {
                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                Console.Write("ERROR ");
                                Console.ResetColor();
                                Console.WriteLine($"{name} ===> Impossible d'ouvrir l'archive !");
                                Console.WriteLine($"Message: {e.Message}");
                            }
                        }
                        catch (Exception e)
                        {
                            Console.ForegroundColor = ConsoleColor.DarkRed;
                            Console.Write("ERROR ");
                            Console.ResetColor();
                            Console.WriteLine($"{zip} ===> Impossible d'extraire l'archive !");
                            Console.WriteLine($"Message: {e.Message}");
                        }
                    }
                    // ***************************************************
                    else if (arg == "-s")
                    {
                        Console.WriteLine("Suppression des fichiers...");

                        foreach (string f in ordre)
                        {
                            string path = $@"{projet}\{f}";
                            if (File.Exists(path))
                            {
                                try
                                {
                                    File.Delete(path);

                                    Console.ForegroundColor = ConsoleColor.Magenta;
                                    Console.Write("OBJET ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{f} ===> suppression du fichier terminé.");
                                }
                                catch (Exception e)
                                {
                                    Console.ForegroundColor = ConsoleColor.DarkRed;
                                    Console.Write("ERROR ");
                                    Console.ResetColor();
                                    Console.WriteLine($"{f} ===> impossible de supprimer le fichier !");
                                    Console.WriteLine($"Message: {e.Message}");
                                }
                            }
                            else
                            {
                                Console.ForegroundColor = ConsoleColor.DarkRed;
                                Console.Write("ERROR ");
                                Console.ResetColor();
                                Console.WriteLine($"{f} ===> fichier introuvale !");
                            }
                        }

                        Console.WriteLine("L'objet a été supprimé.");
                    }
                    // ***************************************************
                    else if (arg == "-r")
                    {
                        Console.WriteLine("L'objet a été renommé.");
                    }
                    // ***************************************************
                    else if (arg == "-l")
                    {
                    }
                    // ***************************************************
                    else
                        Console.WriteLine("Le type d'action doit être '-a' pour ajouter, '-r' pour renommer, '-l' pour lister, ou '-s' pour supprimer.");
                }
                else
                    Console.WriteLine("Heuu, le projet n'existe pas...");

            }
            else if (cmd.Length > 3)
                Console.WriteLine("Problème, seul le nom du projet, le type d'action et le nom du nouvel objet sont attendus !");
            else
                Console.WriteLine("Problème, il manque le nom du projet, le type d'action et le nom du nouvel objet !");
        }

    }
}
