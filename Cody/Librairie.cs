using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Diagnostics;
using System.Net;
using Newtonsoft.Json;

namespace Cody
{
    public class Librairie
    {
        // Si on a deja accepte le warining de version anterieur
        private static bool acceptWarn = false;


        // Decoupe un string en argument avec guillemet
        public static string[] lineToArgs(string line)
        {
            char[] cs = line.ToCharArray();
            bool qt = false;
            for (int i = 0; i < cs.Length; i++)
            {
                char c = cs[i];
                if (c == '"')
                    qt = !qt;
                if (!qt && c == ' ')
                    cs[i] = '\n';
            }
            string[] r = (new string(cs)).Split('\n');
            for (int i = 0; i < r.Length; i++)
                r[i] = r[i].Replace("\"", "");
            return r;
        }


        // Recupere en recursif le nombre et la taille total des fichier
        // d'un dossier et ses sous dossiers
        public static long[] getCountAndSizeFolder(string path)
        {
            long[] data = { 0, 0 };

            foreach (string f in Directory.GetFiles(path))
            {
                data[0]++;

                FileInfo info = new FileInfo(f);
                data[1] += info.Length;
            }

            foreach (string d in Directory.GetDirectories(path))
            {
                long[] recursive = getCountAndSizeFolder(d);
                data[0] += recursive[0];
                data[1] += recursive[1];
            }

            return data;
        }


        // Install un package npm
        public static bool installNpmPackage(string pkgName)
        {
            try
            {
                Console.Write("Installation de '");
                Message.writeIn(ConsoleColor.DarkYellow, pkgName);
                Console.WriteLine("' via npm...");
                Process p2 = startProcess("npm", "i \"" + pkgName + "\" -g", ProcessWindowStyle.Hidden);
                p2.WaitForExit();
                return true;
            }
            catch (Exception e)
            {
                Message.writeExcept("Erreur lors de l'installation du paquet npm '" + pkgName + "' !", e);
                return false;
            }
        }


        // Lance et attends la commande npm
        public static bool runNpmCmd(string pkgName, string args)
        {
            try
            {
                Process p = startProcess(pkgName, args, ProcessWindowStyle.Hidden);
                p.WaitForExit();
                return p.ExitCode == 0;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible d'exécuter le package npm '" + pkgName + "' !", e);
                return false;
            }
        }


        // Lance un processus proprement pour linux
        public static Process startProcess(string name, string args = "", ProcessWindowStyle style = ProcessWindowStyle.Normal, bool redirectOutPut = false)
        {
            // Ouvre dans le navigateur
            ProcessStartInfo startInfo = new ProcessStartInfo();
            startInfo.FileName = name;
            startInfo.Arguments = args;
            startInfo.WindowStyle = style;
            if (redirectOutPut)
            {
                startInfo.UseShellExecute = false;
                startInfo.RedirectStandardOutput = true;
                startInfo.RedirectStandardError = true;
                startInfo.RedirectStandardInput = true;
            }
            else
            {
                startInfo.UseShellExecute = true;
            }

            Process processTemp = new Process();
            processTemp.StartInfo = startInfo;
            processTemp.Start();

            return processTemp;
        }


        // Retourne la sortie d'un process
        public static string[] outputProcess(string name, string args = "")
        {
            Process p = startProcess(name, args, ProcessWindowStyle.Hidden, true);
            p.WaitForExit();
            return new string[] { p.ExitCode.ToString(), p.StandardOutput.ReadToEnd() };
        }


        // Remplace les slash par le bon separateur
        public static string remplaceDirSep(string path)
        {
            return path
                    .Replace('/', Path.DirectorySeparatorChar)
                    .Replace('\\', Path.DirectorySeparatorChar);
        }


        // Vérifi si c'est un projet cody
        public static bool isProject()
        {
            // Si le projet existe
            if (File.Exists("project.json")) 
                return true;
            else
            {
                Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody...");
                return false;
            }
        }


        // Vérifi si c'est le dossier un projet cody
        public static bool isFolderProject(string path)
        {
            return File.Exists(Path.Combine(path, "project.json"));
        }

        
        // Demande une confimation
        public static bool inputYesNo()
        {
            string rep;
            do
            {
                Console.Write("(oui/non) : ");
                rep = Console.ReadLine().Trim().ToLower();
            }
            while (rep != "oui" && rep != "non");
            return rep == "oui";
        }


        // Prepare un client http avec un proxy par defaut
        public static WebClient getProxyClient()
        {
            IWebProxy prox = WebRequest.DefaultWebProxy;
            prox.Credentials = CredentialCache.DefaultCredentials;
            return new WebClient { Proxy = prox };
        }


        // Verifi la version du projet
        public static bool checkProjetVersion()
        {
            try
            {
                string json = File.ReadAllText("project.json");
                Projet inf = JsonConvert.DeserializeObject<Projet>(json);
                bool continu = true;

                // Conflit de version
                if (inf.version != Program.version && !Librairie.acceptWarn)
                {
                    Console.Write("Attention, ce projet est fait pour fonctionner avec la version ");
                    Message.writeIn(ConsoleColor.DarkYellow, inf.version);
                    Console.WriteLine(" de Cody.");
                    Console.Write("Vous êtes en version ");
                    Message.writeIn(ConsoleColor.Green, Program.version);
                    Console.WriteLine(", cela pourrait créer des problèmes de compatibilité, voulez vous continuer ?");

                    continu = inputYesNo();
                    Librairie.acceptWarn = continu;
                }

                return continu;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de lire le fichier d'information de Cody !", e);
                return false;
            }
        }


        // Retourne le lien vers le depot en fonction de la branche
        public static string getGitBranch()
        {
            return "https://raw.githubusercontent.com/TheRake66/Cody/" + (Program.config.modeBeta ? "dev" : "main");
        }


        // Convertit un nombre en format fr
        public static string toNumberFr(int num)
        {
            return String.Format("{0:n0}", num);
        }


        // Convertit un nombre en unite de memoire
        public static string toNumberMem(double num)
        {
            string[] unit = new string[] { "o", "Ko", "Mo", "Go", "To" };
            int count = 0;
            while (Math.Round(num, 0) >= 1000 && count < unit.Length - 1)
            {
                num /= 1024;
                count++;
            }

            return String.Format("{0:n}", num) + " " + unit[count];
        }


        // Retourne la taille d'un fichier en formater
        public static string getFileSize(string file)
        {
            try
            {
                return toNumberMem(new FileInfo(file).Length);
            }
            catch
            {
                return "??? o";
            }
        }
    }
}
