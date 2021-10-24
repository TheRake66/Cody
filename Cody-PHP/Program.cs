using Cody_PHP.Properties;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

namespace Cody_PHP
{
    class Program
    {
        // Recupere la version du exe
        public static string version = typeof(Program).Assembly.GetName().Version.ToString();


        // Point d'entree
        static void Main(string[] args)
        {
            verifiLibrairies();
            changerTaille();
            afficherLogo();


            while (true)
            {
                afficherPrompt();

                // Recupere les inputs, trim, et remplace les doublon d'espaces
                string input = Console.ReadLine().Trim();
                string[] split = input.Replace("  ", " ").Split(' ');
                string cmd = split[0];

                if (cmd.Length > 0)
                {
                    // Retire la commande de base des arguments
                    string[] argm = split.Skip(1).ToArray();
                    changerTaille(); // Si setcursorposition est en dehors

                    // Dispatch dans les commandes
                    switch (cmd)
                    {
                        case "aide":
                            Commande.aideCom(argm);
                            break;

                        case "cd":
                            Commande.changeDir(argm);
                            break;

                        case "cls":
                            Commande.clearCons(argm);
                            break;

                        case "com":
                            Commande.gestComposant(argm);
                            break;

                        case "die":
                            Commande.quitterApp(argm);
                            break;

                        case "dl":
                            Commande.downFile(argm);
                            break;

                        case "exp":
                            Commande.openExplorer(argm);
                            break;

                        case "ls":
                            Commande.listProjet(argm);
                            break;

                        case "maj":
                            Commande.verifMAJ(argm);
                            break;

                        case "new":
                            Commande.creerProjet(argm);
                            break;

                        case "obj":
                            Commande.gestObjet(argm);
                            break;

                        case "rep":
                            Commande.openRepo(argm);
                            break;

                        case "run":
                            Commande.runProjet(argm);
                            break;

                        case "vs":
                            Commande.openVSCode(argm);
                            break;

                        case "wamp":
                            Commande.runWamp(argm);
                            break;

                        default:
                            Console.WriteLine($"Erreur, commande '{cmd}' inconnue !");
                            break;
                    }
                }
                else
                    Console.WriteLine("Aucune commande, essayer la commande 'aide'.");
            }
        }


        // Verification des librairies
        static void verifiLibrairies()
        {
            string[] lib = new string[]
            {
                "Newtonsoft.Json.dll",
                "Newtonsoft.Json.xml"
            };
            byte[][] bin = new byte[][]
            {
                Resources.Newtonsoft_Json1,
                Encoding.ASCII.GetBytes(Resources.Newtonsoft_Json)
            };
            try
            {
                Console.WriteLine("Vérification des librairies...");
                string ass = Path.GetDirectoryName(Assembly.GetEntryAssembly().Location);

                for (int i = 0; i < lib.Length; i++)
                {
                    string n = lib[i];
                    string p = Path.Combine(ass, n);

                    Console.Write("Vérification du fichier '");
                    Message.writeIn(ConsoleColor.Magenta, n);
                    Console.WriteLine("'...");

                    if (File.Exists(n))
                        Console.WriteLine("Fichier trouvé.");
                    else
                    {
                        Console.Write("Création du fichier '");
                        Message.writeIn(ConsoleColor.Green, n);
                        Console.WriteLine("'...");

                        File.WriteAllBytes(p, bin[i]);

                        Console.WriteLine("Fichier crée.");
                    }
                }

                Console.WriteLine("Librairies complètes.");
                Thread.Sleep(2000);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de créer les librairies !", e);
                Console.WriteLine();
                Console.Write("Appuyez sur une touche pour quitter...");
                Console.ReadKey();
                Environment.Exit(1);
            }
        }


        // Affiche le prompt
        static void afficherPrompt()
        {
            // Saut apres une commande
            Console.WriteLine();
            Console.WriteLine();

            // Change le prompt
            Message.writeIn(ConsoleColor.DarkRed, "┌──┤");
            Message.writeIn(ConsoleColor.Cyan, Environment.UserName);
            Message.writeIn(ConsoleColor.DarkYellow, "@");
            Message.writeIn(ConsoleColor.Blue, Environment.MachineName);
            Message.writeIn(ConsoleColor.DarkRed, "├─┤");
            Message.writeIn(ConsoleColor.DarkGreen, Directory.GetCurrentDirectory());
            Message.writeLineIn(ConsoleColor.DarkRed, "│ ");

            Message.writeIn(ConsoleColor.DarkRed, "└────►");
            Message.writeIn(ConsoleColor.DarkYellow, " $");
            Console.ResetColor();
            Console.Write(": ");
        }


        // Change la taille de la console
        static void changerTaille()
        {
            Console.SetWindowSize(
                Math.Min(130, Console.LargestWindowWidth),
                Math.Min(45, Console.LargestWindowHeight));
        }


        // Affiche le logo
        static void afficherLogo()
        {
            // Nettoie si jamais l'user l'a lancer via commande
            Console.Clear();

            // Entete
            Message.writeIn(ConsoleColor.Blue, @"

                     ██████╗                                  ██████╗ ██╗  ██╗██████╗ 
                    ██╔════╝ ██████╗ ██████╗ ██╗   ██╗        ██╔══██╗██║  ██║██╔══██╗
                    ██║     ██╔═══██╗██╔══██╗╚██╗ ██╔╝ █████╗ ██████╔╝███████║██████╔╝
                    ██║     ██║   ██║██║  ██║ ╚████╔╝  ╚════╝ ██╔═══╝ ██╔══██║██╔═══╝ 
                    ╚██████╗╚██████╔╝██████╔╝  ╚██╔╝          ██║     ██║  ██║██║     
                     ╚═════╝ ╚═════╝ ╚═════╝   ██╔╝           ╚═╝     ╚═╝  ╚═╝╚═╝
                                              ██╔╝
                                              ╚═╝
");

            Message.writeIn(ConsoleColor.DarkRed, @"
                                         ░░░▒▒▓▓ Cody-PHP ▓▓▒▒░░░");

            Message.writeLineIn(ConsoleColor.DarkYellow, $@"
                                    Version {version} du 24 octobre 2021
                              Copyright © 2021 - Thibault BUSTOS (TheRake66)");

            Console.WriteLine(@"

Utilisez la commande 'aide' pour voir la liste des commandes.");
        }

    }
}
