<?php

class FileAgent {

    //get file path, try to read and return file content
    static function getFileContents($filePath){
        try{ // Try to open the file, read and return the contents
            if (file_exists($filePath)) {
                //Handle
                if($handle = fopen($filePath,'r')){//can open file
                    //Read
                    $fileSize = filesize($filePath);
                    if($fileSize){//there are some contents
                        $contents = fread($handle,$fileSize);
                    }else{
                        throw new Exception("FileAgent: There is no content!");
                    }
                    //Close
                    fclose($handle);
                    // return contents
                    return $contents;
                }else{// file cannot open
                    throw new Exception("FileAgent: File cannot open!");
                }
            }else{ // file name is invalid
                throw new Exception("FileAgent: File name does not exist!");
            }
        }
        catch (Exception $e){ // Cannot open file, write an error and die.
            echo $e->getMessage();
            die();
        }
    }
}

?>