using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Cody_PHP
{
    public class Message
    {
        // Ecrit une exception
        public static void writeExcept(string content, Exception e)
        {
            Console.WriteLine(content);
            Console.Write("Message : ");
            writeLineIn(ConsoleColor.DarkRed, e.Message);
        }


        // Ecrit dans une certaines couleur
        public static void writeIn(ConsoleColor color, long content)
        {
            writeIn(color, content.ToString());
        }


        // Ecrit dans une certaines couleur
        public static void writeIn(ConsoleColor color, string content)
        {
            Console.ForegroundColor = color;
            Console.Write(content);
            Console.ResetColor();
        }


        // Ecrit dans une certaines couleur avec saut de ligne
        public static void writeLineIn(ConsoleColor color, string content)
        {
            writeIn(color, content);
            Console.WriteLine();
        }


        // Ecrit dans une certaines couleur avec saut de ligne
        public static void writeLineIn(ConsoleColor color, long content)
        {
            writeLineIn(color, content.ToString());
        }
    }
}
