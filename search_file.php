<?php

/**
 * @author Pooja Pareek
 * @return array file names
 * script that should find all files in the /datafiles folder with 
 * names consisting of numbers and letters of the Latin alphabet, having the .ixt extension
 * Display the names of these files, ordered by name. Using regular expressions
 */
function searchFileInDirectory($directory): Array
{
    //check if directory name passed or not
    if(empty($directory))
    {
        return "Error: No directory name passed";
    }

    // If  directory exists
    if (!is_dir($directory)) {
        echo "Directory '$directory' .does not exists.";
        exit;
    }

    // Regular expression pattern to match the file names
    $regexExp = '/^[a-zA-Z0-9]+\.ixt$/';

    // Scan directory to search files inside directory
    $scanDir = array_diff(scandir($directory), array('..', '.'));
    if(!empty($scanDir))
    {
        $filesMatching = preg_grep($regexExp, $scanDir);
        // Sort files by names
        sort($filesMatching);
        return $filesMatching;

        // return implode(',',$filesMatching); //either this with function return type String
    }
    else
    {
        return "No file exist or available files are not ixt file.";
    }
}

$directory = $argv;
if(empty($args))
{
    $directory = 'datafiles';
}
$filesList = searchFileInDirectory($directory);
print_r($filesList);

?>