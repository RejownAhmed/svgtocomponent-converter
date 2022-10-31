<?php
try {
    if (!isset($_FILES['files'])) {
        throw new RuntimeException("Please Upload Files.");
    }
    $uniqueNumber = md5(uniqid(rand()));
    $zip = new ZipArchive();
    if (mkdir("./uploads/$uniqueNumber/iconPaths/icons", 077,true)) {
        $pathExporter ="./uploads/$uniqueNumber/iconPaths/index.js"; 
        $fp = fopen($pathExporter, "w");//Create the exporter js file
        $date = date("d-m-Y");
        fwrite($fp, "/*\n**Exported by Rejown Ahmed Zisan\n**Date: $date\n*/\n");
        fclose($fp);
    }else{
        throw new RuntimeException("Some Error Occured!");

    };


    $copyBaseFile = copy("./baseFiles/vueBase.vue", "./uploads/$uniqueNumber/iconBase.vue");
    $dom = new DOMDocument();
    foreach ($_FILES["files"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $file = $_FILES["files"]["tmp_name"][$key];
            // basename() may prevent filesystem traversal attacks;
            // further validation/sanitation of the filename may be appropriate
            $name = basename($_FILES["files"]["name"][$key]);
            // DO NOT TRUST $_FILES['files']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file);

            if ($mimeType == "image/svg+xml") {//Allow only SVG
                $iconName = $_FILES["files"]["name"][$key];//Name with default ext
                $iconName = explode('.', $iconName)[0];//Name
                $extIconName = explode('.', $iconName)[0].".vue";// Name with new ext
                //Read and get the main path
                $fc = file_get_contents($file); //Get file contents
                $dom->loadXml($fc); //Since svg is XML 
                $child = $dom->getElementsByTagName('g')[0];//here you have an corresponding object
                $xml = $dom->saveXML($child); //xml will contain xml string
                // var_dump(htmlspecialchars($xml));
                $iconFile ="./uploads/$uniqueNumber/iconPaths/icons/$extIconName"; 
                $fp = fopen($iconFile, "w");
                $content = "<template>\n\t$xml\t\n</template>";
                fwrite($fp, $content);
                fclose($fp);
                create_index_file($iconName, $uniqueNumber);//Create the main index file
    
            }
        }
    }
    
} catch (RuntimeException $e) {

    echo $e->getMessage();
    
}

function create_index_file($name, $uniqueNumber){
    $exName = explode("-", $name);
    $importName = $exName[0];
    foreach ($exName as $key => $value) {
        if($key > 0 ){
            $importName .= ucfirst($value);
        };
    };
    var_dump($importName) ;
    echo "<br>";
    $exporter ="./uploads/$uniqueNumber/iconPaths/index.js"; 
    $content = file_get_contents($exporter);
    $content .= "export { default as $importName } from './icons/$name.vue';\n";
    file_put_contents($exporter, $content);

}

function download(){

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="files[]" id="" multiple="multiple">
        <button type="submit">Submit</button>
    </form>
</body>
</html>