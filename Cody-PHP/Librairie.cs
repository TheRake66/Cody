using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Diagnostics;

namespace Cody_PHP
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
                Console.WriteLine("Heuu, le dossier courant n'est pas un projet de Cody-PHP...");
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

    }
}
