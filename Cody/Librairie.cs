using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Diagnostics;
using System.Net;

namespace Cody
{
    public class Librairie
    {

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


        // Lance un processus proprement pour linux
        public static void startProcess(string name, string args = "", ProcessWindowStyle style = ProcessWindowStyle.Normal)
        {
            // Ouvre dans le navigateur
            ProcessStartInfo startInfo = new ProcessStartInfo();
            startInfo.FileName = name;
            startInfo.Arguments = args;
            startInfo.WindowStyle = style;
            startInfo.UseShellExecute = true;

            Process processTemp = new Process();
            processTemp.StartInfo = startInfo;
            processTemp.Start();
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
            if (File.Exists("project.json")) return true;
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

        
        // Telecharge une archive depuis le github
        public static bool downloadArchive(string name, string path = "")
        {
            try
            {
                // Prepare un client http
                WebClient client = getProxyClient();
                string remoteUri = $"https://github.com/TheRake66/Cody/raw/main/bases/{name}.zip";
                client.DownloadFile(remoteUri, Path.Combine(path, $"{name}.zip"));
                return true;
            }
            catch (Exception e)
            {
                Message.writeExcept("Impossible de télécharger l'archive source !", e);
                return false;
            }
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
            while (Math.Round(num, 2) > 1000 && count < unit.Length)
            {
                num /= 1024;
                count++;
            }

            return String.Format("{0:n}", num) + " " + unit[count];
        }

    }
}
