using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GFFramework
{
    public class Messages
    {

        public static void tooMuchArgs(string cmd)
        {
            Console.WriteLine($"Erreur, trop d'arguments, assayez la commande 'aide {cmd}' !");
        }


        public static void tooLessArgs(string cmd)
        {
            Console.WriteLine($"Erreur, pas assez d'arguments, assayez la commande 'aide {cmd}' !");
        }


        public static void badArgs(string cmd)
        {
            Console.WriteLine($"Erreur, mauvais arguments, assayez la commande 'aide {cmd}' !");
        }

    }
}
