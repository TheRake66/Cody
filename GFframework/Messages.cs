using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GFFramework
{
    public class Messages
    {
        
        public static void errorMess(Exception e)
        {
            Console.WriteLine("Erreur, une exception est apparue !");
            Console.WriteLine($"Message: {e.Message}");
        }


        public static void tooMuchArgs()
        {
            Console.WriteLine($"Erreur, trop d'arguments pour cette commande, consultez les arguments avec la commande 'aide' !");
        }


        public static void tooLessArgs()
        {
            Console.WriteLine($"Erreur, pas assez d'argumentspour cette commande, consultez les arguments avec la commande 'aide' !");
        }


        public static void badArgs()
        {
            Console.WriteLine($"Erreur, mauvais arguments pour cette commande, consultez les arguments avec la commande 'aide' !");
        }

    }
}
