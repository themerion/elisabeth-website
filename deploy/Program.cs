using System;
using System.Net;
using System.IO;
using System.Linq;

namespace deploy
{
    class Program
    {
        static void Main(string[] args)
        {
            var sourceRoot = GetWebsiteRootFolder();

            var indexPath = Path.Join(sourceRoot, "index.html");
            var resourcesRoot = Path.Join(sourceRoot, "frontpage-resources/");
            Console.WriteLine("Utgår från: " + sourceRoot);
            Console.WriteLine("Laddar upp resurser från: " + resourcesRoot);

            string password = AquirePassword();

            // Upload Resources
            foreach(var file in Directory.EnumerateFiles(resourcesRoot))
            {
                UploadFile(file, "frontpage-resources", Path.GetFileName(file), password);
            }

            // Upload index.html => font-page.php
            UploadFile(indexPath, "wp-content/themes/phlox", "front-page.php", password);
        }

        public static string AquirePassword() {
            string passwordFile = Path.Join(Environment.GetFolderPath(System.Environment.SpecialFolder.UserProfile), "elisabeth-blog-pwd.txt");
            Console.WriteLine("Läser FTP-lösenord från första raden i filen: '"+passwordFile+"'");
            string pwd = File.ReadLines(passwordFile).FirstOrDefault();

            if(string.IsNullOrWhiteSpace(pwd))
                throw new Exception("Kunde inte läsa lösenord.");
            return pwd;
        }

        public static void UploadFile(string sourcePath, string destinationFolder, string destinationFileName, string password)
        {
            var url = "ftp://ftp.kreativapsykologen.com/public_html/"
                     + ClearTrailingSlash(destinationFolder)
                     + "/" + destinationFileName;

            var request = (FtpWebRequest)WebRequest.Create(url);
            request.Method = WebRequestMethods.Ftp.UploadFile;
            request.Credentials = new NetworkCredential("deploy@kreativapsykologen.com",password);

            byte[] fileContents = File.ReadAllBytes(sourcePath);

            Stream requestStream = request.GetRequestStream();
            requestStream.Write(fileContents, 0, fileContents.Length);
            requestStream.Close();

            var response = (FtpWebResponse)request.GetResponse();
            
            if(response.StatusCode == FtpStatusCode.ClosingData)
            {
                Console.WriteLine("Uploaded file '"+sourcePath+"' at '"+destinationFolder+"/"+destinationFileName+"'");
            }
            else
            {
                Console.WriteLine("ERROR uploading '"+sourcePath+"' at '"+destinationFolder+"/"+destinationFileName+"'");
                Console.WriteLine("Status: {0}", response.StatusDescription);
                response.Close();
            }
        }

        public static string GetWebsiteRootFolder() {
            var executingPath = System.AppContext.BaseDirectory;
            
            var keepIndex = 0;
            var firstForward = executingPath.IndexOf("/");
            var firstBackward = executingPath.IndexOf("\\");
            if(firstForward > -1 && firstForward > firstBackward) {
                keepIndex = firstForward+1;
            } else {
                keepIndex = firstBackward+1;
            }

            var path = executingPath.Substring(0,keepIndex);
            var folders = executingPath.Substring(keepIndex).Split('/','\\');

            foreach(var part in folders) {
                if(part == "deploy")
                    break;
                path = Path.Join(path, part);
            }
            return path;
        }

        public static string ClearTrailingSlash(string s)
        {
            var last = s[s.Length-1];
            if(last == '/' || last == '\\')
            {
                return s.Substring(0, s.Length-1);
            }
            return s;
        }
    }
}
