using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Cody_PHP
{
    public class Messages
    {

        // Enum des type d'entete
        public enum Type
        {
            Objet,
            Composant,
            Attention,
            Dossier,
            Fichier,
            Projet,
            Edition,
            Erreur
        }


        // TYPE - info           ===> 
        private static void entete(ConsoleColor color, Type type, string info)
        {
            Console.ForegroundColor = color;
            switch (type)
            {
                case Type.Objet:
                    Console.Write("OBJET");
                    break;

                case Type.Composant:
                    Console.Write("COMPOSANT");
                    break;

                case Type.Projet:
                    Console.Write("PROJET");
                    break;

                case Type.Edition:
                    Console.Write("EDITION");
                    break;

                case Type.Attention:
                    Console.Write("ATTENTION");
                    break;

                case Type.Erreur:
                    Console.Write("ERREUR");
                    break;

                case Type.Dossier:
                    Console.Write("DOSSIER");
                    break;

                case Type.Fichier:
                    Console.Write("FICHIER");
                    break;
            }

            Console.ResetColor();
            Console.SetCursorPosition(10, Console.CursorTop);
            Console.Write($" - {info}");

            Console.SetCursorPosition(50, Console.CursorTop);
            Console.Write($"===> ");
        }


        // Affiche un text en jaune
        public static void writeData(string data)
        {
            writeIn(ConsoleColor.DarkYellow, $" {data} ");
        }


        // Affiche un int en jaune
        public static void writeData(long data)
        {
            writeData(data.ToString());
        }


        // Affiche une entete simple
        public static void write(Type type, string info)
        {
            entete(ConsoleColor.Magenta, type, info);
        }


        // Affiche une entete plus un text en blanc
        public static void writeFull(Type type, string info, string message)
        {
            entete(ConsoleColor.Magenta, type, info);
            Console.WriteLine($"{message}");
        }


        // Affiche une entete attention plus un text en blanc
        public static void writeWarn(string info, string message)
        {
            entete(ConsoleColor.DarkYellow, Type.Attention, info);
            Console.WriteLine($"{message}");
        }


        // Affiche une entete erreur plus un text en blanc
        public static void writeError(string info, string message, Exception e)
        {
            entete(ConsoleColor.DarkRed, Type.Erreur, info);
            Console.WriteLine($"{message}");
            Console.WriteLine($"Message: {e.Message}");
        }







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
