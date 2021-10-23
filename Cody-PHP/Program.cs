using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Text;
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
            // --------------------------
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
                                 Version {version} du 20 octobre 2021
                           Copyright © 2021 - Thibault BUSTOS (TheRake66)");

            Console.WriteLine(@"

Utilisez la commande 'aide' pour voir la liste des commandes.");
            // --------------------------

            while (true)
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

                // Recupere les inputs, trim, et remplace les doublon d'espaces
                string input = Console.ReadLine().Trim();
                string[] split = input.Replace("  ", " ").Split(' ');
                string cmd = split[0];

                if (cmd.Length > 0)
                {
                    // Retire la commande de base des arguments
                    string[] argm = split.Skip(1).ToArray();

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
    }
}
