<?php
$debug=true;
include 'top.php';
//%%%%%
//
//SECTION: 1 initialize variables
//
//SECTION: 1a
//print array to check if form is working
if ($debug){
    print '<p>Post Array:</p><pre>';
    print_r($_POST);
    print '</pre>';
//}
   
//%%%%%%%%%%%%%%%%%%%%%%%
//    
//SECTION: 1b Security
//
// define security variable to be used in sec 2A
$thisURL = $domain . $phpSelf;
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 1c form variables
//
// initialize variables, one for each form element
// in the order they appear on the form.
//for contact info
$firstname="";
$lastname="";
//list box
$findus="Search engine";
//radio button
$gender="Male";
//check box
$cooking=true;
$baking=false;
$nutrition=false;
        
        
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 1d form error flags
//
// Initialize error flags, one for each form element
// we validate in the order they appear in sec 1c.
$firstnameERROR=false;
$lastnameERROR=false;
$emailERROR = false;
$findusERROR=false;
$genderERROR=false;
$subscribeERROR=false;
$totalChecked=0;
////%%%%%%%%%%%%%%%%%%%%%%%
//
////SECTION: 1e misc variables
//
//  create array to store any error msgs filled in 2d, displayed in 3c.
$errorMsg = array();
//array used to store form values to be written to a .csv
$dataRecord = array();
//
//have we mailed the info to the user?
$mailed = false;
//
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2 Process for when form is submitted
//
if (isset($_POST["btnSubmit"])) {
    
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2a Security
//    
    if (!securityCheck($thisURL)) {
        $msg = '<p>Sorry you cannot access this page. ';
        $msg.= 'Security breach detected and reported.</p>';
        die($msg);
    }
    
    
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2b Clean data
// remove any potential JavaScript or html code from form input.
// Best to follow same order as declared in sec 1c.
$firstname = htmlentities($_POST["txtFirstName"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$firstname;
    
$lastname = htmlentities($_POST["txtLastName"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$lastname;
    
$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[]=$email;
    
$findus = htmlentities($_POST["lstFindUs"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $findus; 
    
$gender = htmlentities($_POST["radGender"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$gender;  
    
    if (isset($_POST["chkCooking"])){
            $cooking=true;
            $totalChecked++;
        } else {
            $cooking=false;
        }
        $dataRecord[]=$cooking;
        
        if (isset($_POST["chkBaking"])) {
            $baking=true;
            $totalChecked++;
        }else{
            $baking=false;
        }
        $dataRecord[]=$baking;
        
        if (isset($_POST["chkNutrition"])) {
            $nutrition=true;
            $totalChecked++;
        }else{
            $nutrition=false;
        }
        $dataRecord[]=$nutrition;
    
    ////%%%%%%%%%%%%%%%%%%%%%%%
    //
    //SECTION: 2c Validation
    //
    // Validation check. Check each value for possible errors, empty,
    // or not what we expect. An IF block is needed for each elt checked (see 1c and 1d).
    // If blocks should also be in order that elts appear on form, so the error 
    // messages appear in correct order. errorMsg will be desplayed on form. see 
    // wec 3b. Error flag $emailERROR will be used in sec 3c.
    if ($firstname=="") {
        $errorMsg[]="Please enter your first name";
        $firstnameERROR=true;
    } elseif(!verifyAlphaNum($firstname)) {
        $errorMsg[]="Your first name appears to have extra character(s).";
        $firstnameERROR=true;
    }
    
    if ($lastname=="") {
        $errorMsg[]="Please enter your last name";
        $lastnameERROR=true;
    } elseif(!verifyAlphaNum($lastname)) {
        $errorMsg[]="Your last name appears to have extra character(s).";
        $lastnameERROR=true;
    }
    
    if($gender !="Male" AND $gender !="Female" AND $gender !="Other") {
            $errorMsg[]="Please choose a gender";
            $genderERROR=true;
        }
    if ($totalChecked < 1) {
            $errorMsg[]="Please choose at least one subscription";
            $subscribeERROR=true;
        }
    ///////// end addition
    
    if ($email == "") {
        $errorMsg[] = 'Please enter your email address';
        $emailERROR = true;
    } elseif (!verifyEmail($email)) {
        $errorMsg[] = 'Your email address appears to be incorrect.';
        $emailERROR = true;
    }
    
////%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2d Process Form-Passed validation
// Process for when form passes validation (errorMsg array is empty)
//
    if (!$errorMsg) {
        if ($debug)
            print PHP_EOL.'<p>Form is valid</p>';
    
////%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2e Save data- passed validation
//
//save data to .csv
    $myFolder='../data/';
    
    $myFileName='registration';
    
    $fileExt='.csv';
    
    $filename=$myFolder.$myFileName.$fileExt;
    if($debug) print PHP_EOL.'<p>filename is '.$filename;
    
    //open file for append
    $file=fopen($filename,'a');
//write the form information
    fputcsv($file,$dataRecord);
    
//close file
    fclose($file);
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2f Create message
//
// build a message to display on screen in sec 3a and to mail to person filling
// out the form (sec 2g).
    $message='<h3>Thank you for registering! We will be in contact with you shortly with more information.</h3>';
    
    foreach($_POST as $htmlName => $value){
        
        $message .='<p>';
        //breaks up form names into words. ex: 
        //txtFirstName becomes First Name
        $camelCase = preg_split('/(?=[A-Z])/', substr($htmlName, 3));
        
      
        foreach($camelCase as $oneWord){
            $message .= $oneWord . ' ';
        }
        $message .= ' = ' . htmlentities($value, ENT_QUOTES, "UTF-8").'</p>';
    
    }
       
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 2g Mail to user
//
// process for mailing a msg which contains the form's data
// msg was built in sec 2f.
    $to = $email; //to the person who filled out the form
    $cc = '';
    $bcc = '';
    
    $from = 'bwilli16@uvm.edu';
    
    //subject of email should be relevant to form
    $subject = 'Thank you for subscribing to (blog name here)!';
    
    $mailed=sendMail($to,$cc,$bcc,$from,$subject,$message);
    
    
    }//end form is valid
    
} //ends if form was submitted
//
//
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 3 Display form
//
?>

<article id="main">
    
    <?php
    //%%%%%%%%%%%%%%%%%%%%%%%
    //
    //SECTION: 3a
    //
    // First time coming to form or there are errors to be displayed
    // in form.
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)){
        print '<h2> Thank you for providing information.</h2>';
        
        print '<p> For your records a copy of this data has ';
        if (!$mailed){
            print "not ";
        }     
        print 'been sent:</p>';
        print '<p>To: '.$email.'</p>';
        
        print $message;
    } else{
    print '<h2>Register Today</h2>';
    print '<p class="form-heading">Contribute to our research.</p>';
    //%%%%%%%%%%%%%%%%%%%%%%%
    //
    //SECTION: 3b error msgs
    //
    //display any error msgs before we print form
    
    if ($errorMsg){
        print '<div id="errors">'.PHP_EOL;
        print '<h2>Your form has the following mistakes that need to be fixed.</h2>' . PHP_EOL;
        print '<ol>'.PHP_EOL;
        
        foreach($errorMsg as $err){
            print '<li>'.$err.'</li>'.PHP_EOL;
        }
        print '</ol>'.PHP_EOL;
        print '</div>'.PHP_EOL;
        }
    
    ////%%%%%%%%%%%%%%%%%%%%%%%
    //
    //SECTION: 3c html form
    //
    /* Dispaly the html form. note the action is to this same page.
    // $php self defined in top.php
    //
    */
    ?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Subscribe to our blog</title>
    </head>
    <body>
    <form action="<?php print $phpSelf; ?>"
          id="frmRegister"
          method="post">

        <fieldset class="contact">
            <legend>Contact Information</legend>
            <p>
                <label class="required text-field" for="txtFirstName">First Name</label>
                <input autofocus
                       <?php if ($firstnameERRORnameERROR) print 'class="mistake"';?>
                       id="txtFirstName"
                       maxlength="45"
                       name="txtFirstName"
                       onfocus="this.select()"
                       placeholder="Enter your first name"
                       tabindex="100"
                       type="text"
                       value="<?php print $firstname; ?>"
                >
            </p>
            <p>
                <label class="required text-field" for="txtLastName">Last Name</label>
                    <input autofocus
                           <?php if ($lastnameERROR) print 'class="mistake"'; ?>
                           id="txtLastName"
                           maxlength="45"
                           name="txtLastName"
                           onfocus="this.select()"
                           placeholder="Enter your last name"
                           tabindex="100"
                           type="text"
                           value="<?php print $lastname; ?>"
                    >
            </p>
            <p>
                <label class="required text-field" for="txtEmail">Email</label>
                    <input
                         <?php if ($emailERROR) print 'class="mistake"'; ?>
                         id="txtEmail"
                         maxlength="50"
                         name="txtEmail"
                         onfocus="this.select()"
                         placeholder="Enter a valid email address"
                         tabindex="120"
                         type="text"
                         value="<?php print $email; ?>"        
                    >
                </p>
            </fieldset> <!-- ends contact -->    
                
            <fieldset>
            <label class="required listbox <?php if ($findusERROR) print ' mistake'; ?>">How did you find us?</label>
            <select id="lstFindUs"
                            name="lstFindUs"
                        tabindex="520">
                                <option <?php if($findus=="Search engine") print " selected"; ?>
                                    value="Search engine">Google or other search engine</option>
                                
                                <option <?php if($findus=="Other website") print " selected"; ?>
                                    value="Other website">Link from other website</option>
                                <option <?php if($findus=="Ad") print " selected"; ?>
                                    value="Ad">Advertisement</option>
                                
                                <option <?php if($findus=="Social media") print " selected"; ?>
                                    value="Social media">Social media</option>
                                <option <?php if($findus=="Friend/family") print " selected"; ?>
                                    value="Friend/family">Friend or family member</option>
                                
                                <option <?php if($findus=="Publication") print " selected"; ?>
                                    value="Publication">Online or print publication</option>
            </select>
            </fieldset>
            <fieldset class="radio <?php if ($genderERROR) print ' mistake'; ?>">
            <legend>What is your gender?</legend>
            <p>
                <label class="radio-field">
                    <input type="radio"
                           id="radGenderMale"
                           name="radGender"
                           value="Male"
                           tabindex="572"
                           <?php if ($gender == "Male") echo ' checked="checked" '; ?> >
                Male</Label>
            </p>
            
            <p>
                <label class="radio-field">
                    <input type="radio"
                           id="radGenderFemale"
                           name="radGender"
                           value="Female"
                           tabindex="592"
                           <?php if ($gender=="Female") echo ' checked="checked" '; ?> >
                Female</label>
            </p>
            
            <p>
                <label class="radio-field">
                    <input type="radio"
                           id="radGenderOther"
                           name="radGender"
                           value="Other"
                           tabindex="572"
                           <?php if ($gender == "Male") echo ' checked="checked" '; ?> >
                Other</Label>
            </p>
            </fieldset>
            
            <fieldset class="checkbox <?php if ($activityERROR) print ' mistake'; ?>">
            <legend>Check all types of content you want to subscribe to:</legend>
                <p>
                    <label class="check-field">
                        <input <?php if ($cooking) print " checked "; ?>
                            id="chkCooking"
                            name="chkCooking"
                            tabindex="420"
                            type="checkbox"
                            value="Cooking">Cooking Recipes</label>
                </p>
                
                <p>
                    <label class="check-field">
                        <input <?php if ($baking) print " checked "; ?>
                            id="chkBaking"
                            name="chkBaking"
                            tabindex="430"
                            type="checkbox"
                            value="Baking">Baking Recipes</label>
                </p>
                
                <p>
                    <label class="check-field">
                        <input <?php if ($nutrition) print " checked "; ?>
                            id="chkNutrition"
                            name="chkNutrition"
                            tabindex="430"
                            type="checkbox"
                            value="Nutrition">Nutrition tips & tricks</label>
                </p>
        </fieldset>
   
        <fieldset class="buttons">
            
            <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Register">
        </fieldset>       
    </form>
    
<?php
    } //end body submit
?>    
<?php include 'footer.php'; ?>

</body>
</html>
