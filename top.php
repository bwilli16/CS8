// 11/27: this is just so you can see the metadata I have. Feel free to edit.

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>We Need A Name</title>
        <meta name="author" content="Blake, Erin, & Sarah">
        <meta charset="utf-8">
        <meta name="description" content="Baking, cooking and nutrition hacks for college students/young adults on a budget">
        <link rel="stylesheet" href="../css/project.css" type="text/css" media="screen">
    <?php
    $debug=false;

    if(isset($_GET["debug"])){
        $debug=true;
    }
    
    //%%%%%%%%%%%%%%%%%%%
    //PATH SETUP
    $domain ='//';
    $server=htmlentities($_SERVER['SERVER_NAME'],ENT_QUOTES, 'UTF-8');
    $domain .=$server;

        if ($debug){
            print '<p>php Self: ' . $phpSelf;
            print '<p>Path Parts<pre>';
            print_r($path_parts);
            print '</pre></p>';
        }
    
    //%%%%%%%%
    //include all libraries
        print PHP_EOL . '<!-- include libraries -->' . PHP_EOL;
        require_once('lib/security.php');

        if ($path_parts['filename']=="form"){
            print PHP_EOL.'<!-- include form libraries -->'.PHP_EOL;
            include 'lib/validation-functions.php';
            include 'lib/mail-message.php';
        }
        
        print PHP_EOL.'<!-- finished including libraries -->'.PHP_EOL;
        ?>
    </head>
</html>
