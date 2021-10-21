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

        // Point d'entree
        static void Main(string[] args)
        {
            // --------------------------
            // Nettoie si jamais l'user l'a lancer via commande
            Console.Clear(); 

            // Entete
            Messages.writeIn(ConsoleColor.Blue, @"

                     ██████╗ ██████╗ ██████╗ ██╗   ██╗     ██████╗ ██╗  ██╗██████╗ 
                    ██╔════╝██╔═══██╗██╔══██╗╚██╗ ██╔╝     ██╔══██╗██║  ██║██╔══██╗
                    ██║     ██║   ██║██║  ██║ ╚████╔╝█████╗██████╔╝███████║██████╔╝
                    ██║     ██║   ██║██║  ██║  ╚██╔╝ ╚════╝██╔═══╝ ██╔══██║██╔═══╝ 
                    ╚██████╗╚██████╔╝██████╔╝   ██║        ██║     ██║  ██║██║     
                     ╚═════╝ ╚═════╝ ╚═════╝    ╚═╝        ╚═╝     ╚═╝  ╚═╝╚═╝                                                              
");

            Messages.writeIn(ConsoleColor.DarkRed, @"
                                      ░░░▒▒▓▓ Cody-PHP ▓▓▒▒░░░");

            Messages.writeLineIn(ConsoleColor.DarkYellow, @"
                                 Version 1.0.0.0 du 20 octobre 2021
                           Copyright © 2021 - Thibault BUSTOS (TheRake66)");

            Console.ResetColor();
            Console.WriteLine(@"
 Utilisez la commande 'aide' pour voir la liste des commandes.");
            // --------------------------

            while (true)
            {
                // Saut apres une commande
                Console.WriteLine();
                Console.WriteLine();

                // Change le prompt
                Messages.writeIn(ConsoleColor.DarkRed, "┌──┤");
                Messages.writeIn(ConsoleColor.Cyan, Environment.UserName);
                Messages.writeIn(ConsoleColor.DarkYellow, "@");
                Messages.writeIn(ConsoleColor.Blue, Environment.MachineName);
                Messages.writeIn(ConsoleColor.DarkRed, "├─┤");
                Messages.writeIn(ConsoleColor.DarkGreen, Directory.GetCurrentDirectory());
                Messages.writeLineIn(ConsoleColor.DarkRed, "│ ");

                Messages.writeIn(ConsoleColor.DarkRed, "└────►");
                Messages.writeIn(ConsoleColor.DarkYellow, " $");
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
                            Commandes.aideCom(argm);
                            break;

                        case "cd":
                            Commandes.changeDir(argm);
                            break;

                        case "cls":
                            Commandes.clearCons(argm);
                            break;

                        case "com":
                            Commandes.gestComposant(argm);
                            break;

                        case "die":
                            Commandes.quitterApp(argm);
                            break;

                        case "dl":
                            Commandes.downFile(argm);
                            break;

                        case "exp":
                            Commandes.openExplorer(argm);
                            break;

                        case "ls":
                            Commandes.listProjet(argm);
                            break;

                        case "maj":
                            Commandes.verifMAJ(argm);
                            break;

                        case "new":
                            Commandes.creerProjet(argm);
                            break;

                        case "obj":
                            Commandes.gestObjet(argm);
                            break;

                        case "rep":
                            Commandes.openRepo(argm);
                            break;

                        case "srv":
                            Commandes.gestServeur(argm);
                            break;

                        case "vs":
                            Commandes.openVSCode(argm);
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
