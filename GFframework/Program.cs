using System;
using System.Collections.Generic;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GFFramework
{
    class Program
    {

        static void Main(string[] args)
        {
            // --------------------------
            Console.Clear();

            Console.ForegroundColor = ConsoleColor.DarkYellow;
            Console.Write(@"
   _____ ______ __                                             _    
  / ____|  ____/ _|                                           | |   
 | |  __| |__ | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 | | |_ |  __||  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 | |__| | |   | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <     v1.0.0.0
  \_____|_|   |_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\    (gff)
");

            Console.ForegroundColor = ConsoleColor.DarkGray;
            Console.Write(@"
                        Source: https://github.com/TheRake66/GFfrramework
                        Copyright: Thibault BUSTOS (TheRake66) - © 2021
");

            Console.ForegroundColor = ConsoleColor.DarkRed;
            Console.Write(@"
 Garrido Fernand framework est un framework français dédié au développement du 
 site WEB en PHP/JavaScript/HTML/CSS oriente objet en MVC avec un assortiment 
 d'outils et de librairies (sécurité, base de données, formulaire, etc.).

");

            Console.ResetColor();
            // --------------------------

            while (true)
            {
                Console.WriteLine();

                Console.ForegroundColor = ConsoleColor.Green;
                Console.Write(Environment.UserName + "@" + Environment.MachineName);
                Console.ResetColor();
                Console.Write(":");
                Console.ForegroundColor = ConsoleColor.Blue;
                Console.Write("~");
                Console.ResetColor();
                Console.Write("$ ");

                string input = Console.ReadLine();
                string[] split = input.Replace("  ", " ").Split(' ');

                if (split.Length > 0)
                {
                    string cmd = split[0];
                    string[] argm = split.Skip(1).ToArray();

                    switch (cmd)
                    {
                        case "new":
                            Commandes.creerProjet(argm);
                            break;

                        case "dl":
                            Commandes.downFile(argm);
                            break;

                        case "list":
                            Commandes.listProjet(argm);
                            break;

                        case "cd":
                            Commandes.changeDir(argm);
                            break;

                        case "die":
                            Commandes.quitterApp();
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
