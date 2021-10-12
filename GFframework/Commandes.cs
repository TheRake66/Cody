using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
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
                        Console.WriteLine("Message: ", e);
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
                        Console.WriteLine("Message: ", e);
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
                    Console.WriteLine("Message: ", e);
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
dl [url]            Telecharge un fichier avec l'URL specifiee.
list                Affiche la liste des projets du dossier courant.
new [nom]           Creer un nouveau projet avec le nom specifie.

*: Argument facultatif.
");
            }
            else if (cmd.Length == 1)
            {
                switch (cmd[0])
                {

                }
            }
            else
                Messages.tooMuchArgs("aide");
        }


        public static void clearCons(string[] cmd)
        {
            if (cmd.Length == 0) Console.Clear();
            else Messages.tooMuchArgs("cl");
        }


        public static void quitterApp(string[] cmd)
        {
            if (cmd.Length == 0) Environment.Exit(0);
            else Messages.tooMuchArgs("die");
        }

    }
}
