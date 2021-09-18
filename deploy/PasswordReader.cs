using System;
using System.IO;
using System.Linq;

public class PasswordReader {
    private string _password = null;

    private PasswordReader() {}

    public static PasswordReader TryInOrder() {
        return new PasswordReader();
    }

    public PasswordReader ReadCommandLineArguments()
    {
        if(!string.IsNullOrWhiteSpace(_password))
            return this;

        Console.WriteLine("Försöker läsa lösenord från argument '--password <pwd>' till Main()");
        
        bool expectPwd = false;
        foreach (var arg in Environment.GetCommandLineArgs())
        {
            if (expectPwd)
            {
                _password = arg;
                break;
            }

            if (arg == "--password")
                expectPwd = true;
        }

        return this;
    }

    public PasswordReader ReadFromFileInHomeDirectory()
    {
        if(!string.IsNullOrWhiteSpace(_password))
            return this;
            
        string passwordFile = Path.Join(Environment.GetFolderPath(System.Environment.SpecialFolder.UserProfile), "elisabeth-blog-pwd.txt");
        Console.WriteLine("Försöker läsa FTP-lösenord från första raden i filen: '"+passwordFile+"'");

        _password = File.ReadLines(passwordFile).FirstOrDefault();

        return this;
    }

    public string GetPassword()
    {
        if(string.IsNullOrWhiteSpace(_password))
        {
            throw new System.Exception("ERROR - Kunde inte hämta lösenord.");
        }
        Console.WriteLine("Lösenord inläst.");
        return _password;
    }
}