using Cody.Properties;
using Newtonsoft.Json;
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

namespace Cody
{
    class Program
    {

        // Recupere la version du exe
        public static string version = typeof(Program).Assembly.GetName().Version.ToString();
        public static Configuration config = new Configuration();
        public static string configFile = Path.Combine(Path.GetDirectoryName(Assembly.GetExecutingAssembly().Location), "configuration.json");


        // Point d'entree
        static void Main(string[] args)
        {
            // Affiche le logo
            afficherLogo();
            // Verifi les mise a jour
            Commande.checkUpdate(true);
            // Charge la config
            chargerConfig();


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

                        case "lib":
                            Commande.gestLibrairie(argm);
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

                        case "pkg":
                            Commande.gestPackage(argm);
                            break;

                        case "rep":
                            Commande.openRepo(argm);
                            break;

                        case "run":
                            Commande.runProjet(argm);
                            break;

                        case "tra":
                            Commande.gestTrait(argm);
                            break;

                        case "vs":
                            Commande.openVSCode(argm);
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


        // Affiche le logo
        static void afficherLogo()
        {
            // Nettoie si jamais l'user l'a lancer via commande
            Console.Clear();
            // Change le titre de la console
            Console.Title = $"({version}) Cody";
            Console.OutputEncoding = Encoding.UTF8;

            // Entete
            Console.SetCursorPosition(0, 0);
            Message.writeIn(ConsoleColor.Blue, @"
                                       ▄▄▄▄▄▄▄                          ▄▄
                                     ▄████████▌▐▄                       ██
                                    ████▀▀▀▀▀▀ ▀▀                       ██
                                   ▐███ ▐████████▌    ▄▄▄▄▄▄      ▄▄▄▄▄▄██   ▄▄       ▄▄
                                   ███▌ ▄▄▄▄▄▄▄▄▄▄   ████████    █████████  ▐██▌     ▐██▌
                                   ███▌ ▀▀▀▀▀▀▀▀▀▀  ▐██▀  ▀██▌  ▐██▀  ▀███   ▐██▌   ▐██▌
                                   ▐███ ▐████████▌  ██▌    ▐██  ██▌    ▐██    ▐██▌ ▐██▌
                                    ████▄▄▄▄▄▄ ▄▄   ▐██▄  ▄██▌  ▐██▄  ▄██▌     ▐██▄██▌
                                     ▀████████▌▐▀    ████████    ████████       ▐███▌
                                       ▀▀▀▀▀▀▀        ▀▀▀▀▀▀      ▀▀▀▀▀▀        ███▀
                                                                               ███
                                                                              ███
                                                                            ▄███
                                                                            ▀▀▀
");
            // Entete
            Console.SetCursorPosition(0, 15);
            Message.writeIn(ConsoleColor.DarkRed, @"
                                                    ░░░▒▒▓▓ Cody ▓▓▒▒░░░");
            Message.writeLineIn(ConsoleColor.DarkYellow, $@"
                                          ~ Version {version} du 10 novembre 2021 ~
                                     ~ Copyright © 2021 - Thibault BUSTOS (TheRake66) ~");

            Console.WriteLine(@"


Utilisez la commande 'aide' pour voir la liste des commandes.
");
        }


        // Gere la configuration de cody
        static void chargerConfig()
        {
            AppDomain.CurrentDomain.ProcessExit += (s, e) => actualiserConfig();

            if (File.Exists(Program.configFile))
            {
                bool continu = true;

                try
                {
                    Program.config = JsonConvert.DeserializeObject<Configuration>(
                        File.ReadAllText(Program.configFile));
                }
                catch (Exception e)
                {
                    Message.writeExcept("Impossible de charger la configuration de Cody !", e);
                    continu = false;
                }

                if (continu) appliquerConfig();
            }
        }
        static void appliquerConfig()
        {
            try
            {
                if (Directory.Exists(Program.config.dernierChemin)) Directory.SetCurrentDirectory(Program.config.dernierChemin);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'appliquer la configuration de Cody !", e);
            }
        }
        static void actualiserConfig()
        {
            bool continu = true;
            try
            {
                Program.config.dernierChemin = Directory.GetCurrentDirectory();
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'actualiser la configuration de Cody !", e);
                continu = false;
            }

            if (continu) sauvegarderConfig();
        }
        static void sauvegarderConfig()
        {
            try
            {
                File.WriteAllText(
                    Program.configFile,
                    JsonConvert.SerializeObject(Program.config, Formatting.Indented));
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de sauvegarder la configuration de Cody !", e);
            }
        }

    }
}
