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
        // La configuration en objet
        public static Configuration config = new Configuration();
        // Le fichier configuration, dans le dossier du exe
        public static string configFile = Path.Combine(
            Path.GetDirectoryName(Assembly.GetExecutingAssembly().Location), 
            "configuration.json");
        // Si un chemin est en argument on applique pas celui de la config
        private static bool pathInArgument = false;


        // Point d'entree
        static void Main(string[] args)
        {
            // Gere les arguments
            gererArguments(args);
            // Affiche le logo
            afficherLogo();
            // Verifi les mise a jour
            Commande.checkUpdate(true);
            // Charge la config
            chargerConfig();


            while (true)
            {
                // Affiche le prompt
                afficherPrompt();

                // Recupere les inputs, trim, et remplace les doublon d'espaces
                string input = Console.ReadLine().Trim();
                string[] split = Librairie.lineToArgs(input);
                dispatchCmd(split);
            }
        }


        // Affiche le logo
        static void afficherLogo()
        {
            // Nettoie si jamais l'user l'a lancer via commande
            Console.Clear();
            // Change le titre de la console
            Console.Title = $"({version}) Cody";
            Console.OutputEncoding = Encoding.UTF8;

            if (Console.WindowHeight > 10)
            {
                // Entete
                Message.setPos(0, 0);
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
                Message.setPos(0, 15);
                Message.writeIn(ConsoleColor.DarkRed, @"
                                                    ░░░▒▒▓▓ Cody ▓▓▒▒░░░");
                Message.writeLineIn(ConsoleColor.DarkYellow, $@"
                                             ~ Version {version} du 4 avril 2022 ~
                                     ~ Copyright © " + DateTime.Now.Year + " - Thibault BUSTOS (TheRake66) ~");

                Console.WriteLine(@"


Utilisez la commande 'aide' pour voir la liste des commandes.
");
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
            if (Program.config.modeBeta)
            {
                Message.writeIn(ConsoleColor.DarkRed, "├─┤");
                Message.writeIn(ConsoleColor.DarkYellow, "Mode développeur");
            }
            Message.writeLineIn(ConsoleColor.DarkRed, "│ ");
            Message.writeIn(ConsoleColor.DarkRed, "└────►");
            Message.writeIn(ConsoleColor.DarkYellow, " $");
            Console.ResetColor();
            Console.Write(": ");
        }


        // Dispatch dans les commandes
        static void dispatchCmd(string[] line)
        {
            string cmd = line[0];

            if (cmd.Length > 0)
            {
                // Retire la commande de base des arguments
                string[] argm = line.Skip(1).ToArray();

                switch (cmd)
                {
                    case "aide":
                        Commande.aideCom(argm);
                        break;

                    case "build":
                        Commande.buildProject(argm);
                        break;

                    case "bye":
                        Commande.quitterApp(argm);
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

                    case "dev":
                        Commande.devMode(argm);
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

                    case "stop":
                        Commande.stopProjet(argm);
                        break;

                    case "tes":
                        Commande.gestTest(argm);
                        break;

                    case "tra":
                        Commande.gestTrait(argm);
                        break;

                    case "unit":
                        Commande.runUnit(argm);
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


        // Gere les arguments
        static void gererArguments(string[] args)
        {
            // Charge le path en argument
            if (args.Length > 0)
            {
                string path = args[0];
                bool continu = true;
                if (path == ".")
                {
                    try
                    {
                        string curr = Directory.GetCurrentDirectory();
                        path = curr;
                    }
                    catch
                    {
                        continu = false;
                    }
                }
                if (continu && Directory.Exists(path))
                {
                    try
                    {
                        Directory.SetCurrentDirectory(path);
                        Program.pathInArgument = true;
                    }
                    catch { }
                }
            }
        }


        // Gere la configuration de cody
        static void chargerConfig()
        {
            AppDomain.CurrentDomain.ProcessExit += (s, e) => sauvegarderConfig();

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
                if (!Program.pathInArgument)
                    if (Directory.Exists(Program.config.lastPath)) 
                        Directory.SetCurrentDirectory(Program.config.lastPath);
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'appliquer la configuration de Cody !", e);
            }
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
