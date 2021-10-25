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
        public static void startProcess(string name, string args = "")
        {
            // Ouvre dans le navigateur
            ProcessStartInfo startInfo = new ProcessStartInfo();
            startInfo.FileName = name;
            startInfo.Arguments = args;
            startInfo.WindowStyle = ProcessWindowStyle.Hidden;
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

    }
}
