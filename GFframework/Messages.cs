using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace GFFramework
{
    public class Messages
    {

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


        public static void writeData(string data)
        {
            Console.ForegroundColor = ConsoleColor.DarkYellow;
            Console.Write(data);
            Console.ResetColor();
        }


        public static void writeData(long data)
        {
            writeData(data.ToString());
        }


        public static void write(Type type, string info)
        {
            entete(ConsoleColor.Magenta, type, info);
        }


        public static void writeFull(Type type, string info, string message)
        {
            entete(ConsoleColor.Magenta, type, info);
            Console.WriteLine($" {message}");
        }


        public static void writeWarn(string info, string message)
        {
            entete(ConsoleColor.DarkYellow, Type.Attention, info);
            Console.WriteLine($" {message}");
        }


        public static void writeError(string info, string message, Exception e)
        {
            entete(ConsoleColor.DarkRed, Type.Erreur, info);
            Console.WriteLine($" {message}");
            Console.WriteLine($"Message: {e.Message}");
        }

    }
}
