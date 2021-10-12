using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

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
                        Console.WriteLine("Erreur, impossible de changer le dossier !");
                        Console.WriteLine($"Message: {e.Message}");
                    }
                }
                else
                    Console.WriteLine("Erreur, le chemin specifie n'existe pas !");
            }
            else if (cmd.Length > 1)
                Messages.tooMuchArgs("cd");
            else
                Console.WriteLine($"Le chemin actuel est: '{Directory.GetCurrentDirectory()}'.");
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

                    }
                    catch (Exception e)
                    {
                        Console.WriteLine("Erreur, impossible de creer le projet !");
                        Console.WriteLine($"Message: {e.Message}");
                    }
                }
                else
                    Console.WriteLine($"Heuuu, le projet {name} existe deja, ou un dossier...");
            }
            else if (cmd.Length > 1)
                Messages.tooMuchArgs("new");
            else
                Messages.tooLessArgs("new");
        }


        public static void downFile(string[] cmd)
        {
            if (cmd.Length == 2)
            {
                try
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
                            Console.SetCursorPosition(x, y);
                            Console.Write("[");
                            Console.ForegroundColor = ConsoleColor.Green;

                            float percent = e.ProgressPercentage / 3;
                            for (float i = 1; i <= 100 / 3; i++)
                                Console.Write(i <= percent ? "#" : " ");

                            Console.ResetColor();
                            Console.WriteLine($"] {(e.ProgressPercentage == 99 ? 100 : e.ProgressPercentage)}% : Telechargement en cours...");
                        }
                    };
                    web.DownloadFileCompleted += (s, e) =>
                    {
                        lock (lk)
                        {
                            Console.WriteLine("Telechargement termine.");
                            ended = true;
                        }
                    };
                    web.DownloadFileTaskAsync(url, file);


                    // dl https://launcher.mojang.com/v1/objects/a16d67e5807f57fc4e550299cf20226194497dc2/server.jar aa.jar
                    while (!ended || !Monitor.TryEnter(lk)) { }
                }
                catch (Exception e)
                {
                    Console.WriteLine("Erreur, impossible telecharger le fichier !");
                    Console.WriteLine($"Message: {e.Message}");
                }
            }
            else if (cmd.Length > 2)
                Messages.tooMuchArgs("dl");
            else
                Messages.tooLessArgs("dl");
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
                                Console.Write($"{Path.GetFileName(f)} ==> ");

                                Console.ForegroundColor = ConsoleColor.DarkYellow;
                                Console.Write(data[0]);

                                Console.ResetColor();
                                Console.Write(" fichier(s) font ");

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
                    Console.WriteLine("Erreur, impossible de recuperer la liste des projets !");
                    Console.WriteLine($"Message: {e.Message}");
                }
            }
            else
                Messages.tooMuchArgs("list");
        }


        public static void aideCom(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                Console.WriteLine(
@"aide [*commande]    Affiche l'aide globale ou l'aide d'une commande specifique.
cd [*chemin]        Affiche ou change le dossier courant.
cl                  Nettoie la console.
die                 Quitte GFframework.
dl [url] [chemin]   Telecharge un fichier avec l'URL specifiee.
git                 Ouvre la depot GitHub de GFframework.
list                Affiche la liste des projets du dossier courant.
maj                 Met a jour GFframework via le depot GitHub.
new [nom]           Creer un nouveau projet avec le nom specifie.

*: Argument facultatif.
");
            }
            else 
                Messages.tooMuchArgs("aide");
        }


        public static void clearCons(string[] cmd)
        {
            if (cmd.Length == 0)
                Console.Clear();
            else 
                Messages.tooMuchArgs("cl");
        }


        public static void openGit(string[] cmd)
        {
            if (cmd.Length == 0)
            {
                try { Process.Start("https://github.com/TheRake66/GFfrramework"); }
                catch { }
            }
            else 
                Messages.tooMuchArgs("git");
        }


        public static void quitterApp(string[] cmd)
        {
            if (cmd.Length == 0) 
                Environment.Exit(0);
            else 
                Messages.tooMuchArgs("die");
        }


        public static void verifMAJ(string[] cmd)
        {
        }

    }
}
