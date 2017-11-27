6: form (subscribe)
<?php
$debug=true;
include 'top.php';
//%%%%%
//
//SECTION: 1 initialize variables
//
//SECTION: 1a
//print array to check if form is working
//if ($debug){
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
$name="";
$phone="";
$email = "bwilli16@uvm.edu";
$address="";
$city="";

//list box
$state="VT";

//radio button
$gender="Female";

//check box
$newsletter=true;
$volunteer=false;
$product=false;
        
        
//%%%%%%%%%%%%%%%%%%%%%%%
//
//SECTION: 1d form error flags
//
// Initialize error flags, one for each form element
// we validate in the order they appear in sec 1c.
$nameERROR=false;
$phoneERROR=false;
$addressERROR=false;
$cityERROR=false;
$stateERROR=false;
$emailERROR = false;
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
$dataRecord=array();
//
//have we mailed the info to the user?
$mailed=false;
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
$name=htmlentities($_POST["txtName"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$name;

$phone=htmlentities($_POST["txtPhone"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$phone;

$address=htmlentities($_POST["txtAddress"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$address;
$city=htmlentities($_POST["txtCity"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$city;
    
$email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[]=$email;
    
$gender=htmlentities($_POST["radGender"],ENT_QUOTES,"UTF-8");
    $dataRecord[]=$gender;
    
$dataRecord[]=$state;  
    
    if (isset($_POST["chkNewsletter"])){
            $newsletter=true;
            $totalChecked++;
        } else {
            $newsletter=false;
        }
        $dataRecord[]=$newsletter;
        
        if (isset($_POST["chkVolunteer"])) {
            $volunteer=true;
            $totalChecked++;
        }else{
            $volunteer=false;
        }
        $dataRecord[]=$volunteer;
        
        if (isset($_POST["chkProduct"])) {
            $product=true;
            $totalChecked++;
        }else{
            $product=false;
        }
        $dataRecord[]=$product;
    
    ////%%%%%%%%%%%%%%%%%%%%%%%
    //
    //SECTION: 2c Validation
    //
    // Validation check. Check each value for possible errors, empty,
    // or not what we expect. An IF block is needed for each elt checked (see 1c and 1d).
    // If blocks should also be in order that elts appear on form, so the error 
    // messages appear in correct order. errorMsg will be desplayed on form. see 
    // wec 3b. Error flag $emailERROR will be used in sec 3c.
    if ($name==""){
        $errorMsg[]="Please enter your full name";
        $nameError=true;
    } elseif(!verifyAlphaNum($name)){
        $errorMsg[]="Your name appears to have extra character.";
        $nameERROR=true;
    }

    if ($phone==""){
        $errorMsg[]="Please enter your phone number";
        $phoneError=true;
    } elseif(!verifyAlphaNum($phone)){
        $errorMsg[]="Please enter your number without extra symbols such as dashes.";
        $phoneError=true;
    }
    if ($address==""){
        $errorMsg[]="Please enter your address";
        $addressError=true;
    } elseif(!verifyAlphaNum($address)){
        $errorMsg[]="Your address appears to have extra character.";
        $addressError=true;
    }
    if ($city==""){
        $errorMsg[]="Please enter your city";
        $cityError=true;
    } elseif(!verifyAlphaNum($city)){
        $errorMsg[]="Your city appears to have extra character.";
        $cityError=true;
    }
    
   
if($gender !="Male" AND $gender !="Female" AND $gender !="Other"){
            $errorMsg[]="Please choose a gender";
            $genderERROR=true;
        }
        if ($totalChecked < 1){
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
    $subject = 'Changing the Earth: ';
    
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
    
    <form action="<?php print $phpSelf; ?>"
          id="frmRegister"
          method="post">

        <fieldset class="contact">
            <legend>Contact Information</legend>
            <p>
                <label class="required text-field" for="txtName">Name</label>
                <input autofocus
                       <?php if ($nameERROR) print 'class="mistake"';?>
                       id="txtName"
                       maxlength="45"
                       name="txtName"
                       onfocus="this.select()"
                       placeholder="Enter your full name"
                       tabindex="100"
                       type="text"
                       value="<?php print $name; ?>"
                >
            </p>
             <p>
                <label class="required text-field" for="txtPhone">Phone number</label>
                <input 
                       <?php if ($phoneERROR) print 'class="mistake"';?>
                       id="txtPhone"
                       maxlength="20"
                       name="txtPhone"
                       onfocus="this.select()"
                       placeholder="8020001234"
                       tabindex="100"
                       type="text"
                       value="<?php print $phone; ?>"
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
                
                <p>
                <label class="required text-field" for="txtAddress">Address</label>
                <input 
                       <?php if ($addressERROR) print 'class="mistake"';?>
                       id="txtAddress"
                       maxlength="100"
                       name="txtAddress"
                       onfocus="this.select()"
                       placeholder="1 Main St"
                       tabindex="100"
                       type="text"
                       value="<?php print $address; ?>"
                >
                </p>
                <p>
                <label class="required text-field" for="txtCity">City</label>
                <input 
                       <?php if ($cityERROR) print 'class="mistake"';?>
                       id="txtCity"
                       maxlength="100"
                       placeholder="Burlington"
                       name="txtCity"
                       onfocus="this.select()"
                       tabindex="100"
                       type="text"
                       value="<?php print $city; ?>"
                >  
            </p>
            <p>
                <label class="required listbox">State</label>
            <select>
                <option value="AK">AK</option>
                <option value="AL">AL</option>
                <option value="AR">AR</option>
                <option value="AZ">AZ</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="IA">IA</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="MA">MA</option> 
                <option value="MD">MD</option> 
                <option value="ME">ME</option> 
                <option value="MI">MI</option> 
                <option value="MN">MN</option> 
                <option value="MO">MO</option> 
                <option value="MS">MS</option> 
                <option value="MT">MT</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="NE">NE</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NV">NV</option>
                <option value="NY">NY</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VA">VA</option>
                <option value="VT" selected>VT</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WI">WI</option>
                <option value="WY">WY</option>
</select>
                 
            </p>
            </fieldset> <!-- ends contact -->
            <fieldset class="checkbox <?php if ($activityERROR) print ' mistake'; ?>">
            <legend>Email Subscriptions:</legend>
                <p>
                    <label class="check-field">
                        <input <?php if ($newsletter) print " checked "; ?>
                            id="chkNewsletter"
                            name="chkNewsletter"
                            tabindex="420"
                            type="checkbox"
                            value="Newsletter"> Weekly newsletter</label>
                </p>
                
                <p>
                    <label class="check-field">
                        <input <?php if ($volunteer) print " checked "; ?>
                            id="chkVolunteer"
                            name="chkVolunteer"
                            tabindex="430"
                            type="checkbox"
                            value="Volunteer"> Volunteering opportunities update </label>
                </p>
                
                <p>
                    <label class="check-field">
                        <input <?php if ($product) print " checked "; ?>
                            id="chkProduct"
                            name="chkProduct"
                            tabindex="430"
                            type="checkbox"
                            value="Product"> Product information and discounts</label>
                </p>
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
            
        <fieldset class="buttons">
            
            <input class="button" id="btnSubmit" name="btnSubmit" tabindex="900" type="submit" value="Register">
        </fieldset>
            
            
    </form>
    
<?php
    } //end body submit
?>
    
</article>

<?php include 'footer.php'; ?>

</body>
</html>
