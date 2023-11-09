<?php

/**
 * @author Pooja Pareek
 * @return array file names
 * downloading the page https://www.wikipedia.org/, extract headings, abstracts, pictures, 
 * links from the page part with sections, save it in the wiki_sections table
 * db structure id integer, auto-incremental date_created in year-month-day format hours:minutes:seconds title 
 * string no more than 230 characters url string no more than 240 characters, unique picture
 * string no more than 240 characters, unique abstract string no more than 256 characters, unique
 */

function downloadPageandSave($url='')
{
    $pageContent = file_get_contents('https://www.wikipedia.org/');

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($pageContent);
    $sections = $dom->getElementsByTagName('div');
    
    // print_r($sections); die;
    foreach ($sections as $section) 
    {
        $testData = 'testdata';
        $dateCreated = date("Y-m-d H:i:s");
        $picture = '';
        
        if($section->getAttribute('class') == 'other-project-text')
        {
            $texts = $section->getElementsByTagName('span');
            $title = '';
            foreach ($texts as $text) 
            {
                $title = $text->textContent;
                break;
            }

        }

        if($section->getAttribute('class') == 'other-projects')
        {
            $links = $section->getElementsByTagName('a');
            $urls = '';
            foreach ($links as $link) 
            {
                $urls = $link->getAttribute('href');
                break;
            }
        }

        $db = OpenCon();
       
        $sql = "INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$date_created, $title, $url, $picture, $abstract]);

    }
}


/**
 * Method to create db connection
 */
function OpenCon()
{
    //db configuration
    $servername = "localhost";
    $username = "username";
    $password = "password";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
        echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    
}

function createWikiSectionSchema()
{
    $db = OpenCon();
    $db->exec('CREATE TABLE IF NOT EXISTS wiki_sections (
        id INT PRIMARY KEY AUTO_INCREMENT,
        date_created DATETIME,
        title VARCHAR(230) NOT NULL,
        url VARCHAR(240) NOT NULL,
        picture VARCHAR(240) NOT NULL,
        abstract VARCHAR(256) NOT NULL,
        UNIQUE(url),
        UNIQUE(picture),
        UNIQUE(abstract),
    )');
}

downloadPageandSave();

?>