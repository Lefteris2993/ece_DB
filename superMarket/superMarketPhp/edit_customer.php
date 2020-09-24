<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$birthDate = $fullName = $children = $address = $city = $street = $postalCode = $points = "";
$birthDate_err = $price_err  = $children_err = $name_err = $address_err = $street_err = $postalCode_err = $city_err  = $points_err = "";
$check_yes = $check_no = $check_male = $check_female = $check_else = "";
$gender = null;
$married = null;

// Processing form data when form is submitted
if(isset($_POST["cardID"]) && !empty($_POST["cardID"])){

    $cardID = $_POST["cardID"];
        
    // Validate Name
    $input_fullName = trim($_POST["fullName"]);
    if(empty($input_fullName)){
        $name_err = "Please enter a name.";
    }
    else if (ctype_alpha(str_replace(' ', '', $input_fullName)) === false) {
        $name_err = 'Name must contain letters and spaces only';
    }
    else{
        $fullName = $input_fullName;
    }
    
    $input_points = trim($_POST["points"]);
    if(empty($input_points)){
        $points = 0;
    }
    else if ($input_points < 0 ) {
        $points_err = 'points cannot be under zero';
    }
    else{
        $points = $input_points;
    }


    // Validate birthdate
    $input_birthDate = trim($_POST["birthDate"]);
    if(empty($input_birthDate)){
        $birthDate = Null;
    }
    else if ( ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$input_birthDate)){
        $birthDate_err = "Please enter a valid date (yyyy-mm-dd)";
    }
    else{
        $birthDate = date( "Y-m-d" , strtotime($input_birthDate) );
    }

        // Validate chlidern
    $input_children = trim($_POST["children"]);
    if (empty($input_children)){
        $children = null;
    } elseif($input_children < 0 ){
        $children_err = "Please enter the number of children they have.";
    } else{
        $children = $input_children;
    }

    // Validate city name
    $input_city = trim($_POST["city"]);
    if(empty($input_city)){
        $city = NUll;
    } elseif(ctype_alpha(str_replace(' ', '', $input_city)) === false){
        $city_err = "Please enter a valid city.";
    } else{
        $city = $input_city;
    }

    // Validate street name
    $input_street = trim($_POST["street"]);
    if ($city == NULL){
        $street = NULL;
    }else{
        if(empty($input_street)){
            $street = $input_street;
        } elseif(ctype_alpha(str_replace(' ', '', $input_street)) === false){
            $street_err = "Please enter a valid street Name.";
        } else{
            $street = $input_street;
        }
    }
    

    // Validate streetNr
    $input_address = trim($_POST["address"]);
    if ($city == NULL){
        $address = NULL;
    }
    else{
        if(empty($input_address)){
            if (empty($input_street)){
                $address = $input_address;
            }else{
                $address_err = "Please enter the street number amount.";
            } 
        }else if($input_address < 0 or empty($input_street)){
            $address_err = "Please enter a positive integer value. You also need a street name if yoy want to put street number.";
        }else{
            $address = $input_address;
        }  
    }


    // Validate postal code
    $input_postalCode = trim($_POST["postalCode"]);
    if ($city == NUll){
        $postalCode = null;
    }else{
        if(empty($input_postalCode)){
            $postalCode_err = "Please enter the postalCode";     
        } elseif(!ctype_digit($input_postalCode) || $input_postalCode>99999){
            $postalCode_err = "Please enter a valid postal code";
        } else{
            $postalCode = $input_postalCode;
        }
    }



 
 if(isset($_POST['terms'])){
     //An array containing the radio input values that are allowed
     $allowedAnswers = array('yes', 'no');

     //The radio button value that the user sent us.
     $chosenAnswer = $_POST['terms'];

     //Make sure that the value is in our array of allowed values.
     if(in_array($chosenAnswer, $allowedAnswers)){

         //Check to see if the user ticked yes.
         if(strcasecmp($chosenAnswer, 'yes') == 0){
             //Set our variable to TRUE because they agreed.
             $married = 1;
         }
         else if (strcasecmp($chosenAnswer, 'no') == 0)
         {
             $married = 0;
         }
         else{
             $married = NULL;
         }
     }
 }
 
 if(isset($_POST['sex'])){
     //An array containing the radio input values that are allowed
     $allowedAnswers1 = array('male', 'female', 'else');

     //The radio button value that the user sent us.
     $chosenAnswer1 = $_POST['sex'];

     //Make sure that the value is in our array of allowed values.
     if(in_array($chosenAnswer1, $allowedAnswers1)){

         //Check to see if the user ticked yes.
         if(strcasecmp($chosenAnswer1, 'male') == 0){
             //Set our variable to TRUE because they agreed.
             $gender = 'male';
         }
         else if (strcasecmp($chosenAnswer1, 'female') == 0) {
             $gender = 'female';
         }
         else{
             $gender = NULL;
         }
     }
 }
    
    if( empty($name_err) && empty($points_err) && empty($birthDate_err) && empty($children_err) && empty($city_err) && empty($street_err) && empty($address_err) && empty($postalCode_err))
    {   
        $sql = "UPDATE customer SET fullName=?, points=?, gender=?, birthDate=?, married=?, children=?, city=?, street=?, address=?, postalCode = ? WHERE cardID = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssi",  $param_fullName, $param_points, $param_gender , $param_birthDate, $param_married, $param_children, $param_city , $param_street, $param_address, $param_postalCode, $cardID);

            // Set parameters
            $param_fullName = $fullName;
            $param_points = $points;
            $param_gender = $gender;
            $param_birthDate = $birthDate;
            $param_married = $married;
            $param_children = $children;
            $param_city = $city;
            $param_street = $street;
            $param_address = $address;
            $param_postalCode = $postalCode;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: customers.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    

    // Close connection
    mysqli_close($link);
} else{
    
    // Check existence of id parameter before processing further
    if(isset($_GET["cardID"]) && !empty(trim($_GET["cardID"]))){
        // Get URL parameter
      
        $cardID =  trim($_GET["cardID"]);

        // Prepare a select statement
        $sql = "SELECT * FROM customer  WHERE cardID = ? ";

        if($stmt = mysqli_prepare($link, $sql)){
            
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $cardID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $fullName = $row["fullName"];
                    if ($row["points"] == null){
                        $points = 0;
                    }else{
                        $points = $row["points"];
                    }
                    $gender = $row["gender"];
                    $married = $row["married"];
                    if ($row["children"] == null){
                        $children = "";
                    }else if ($row["children"] == 0) {
                        $children = 0;
                    }else{
                        $children = $row["children"];
                    }
                    if ($row["birthDate"] == null){
                        $birthDate = "";
                    }else {
                        $birthDate = $row["birthDate"];
                    }
                    if ($row["city"] == null){
                        $city = "";
                    }else {
                        $city = $row["city"];
                    }
                    if ($row["street"] == null){
                        $street = "";
                    }else {
                        $street = $row["street"];
                    }
                    if ($row["address"] == null){
                        $address = "";
                    }else {
                        $address= $row["address"];
                    }
                    $postalCode = $row["postalCode"];

                    if (strcasecmp($gender, 'male') == 0){
                        $check_male = 'checked= "checked"';
                    }
                    else if (strcasecmp($gender, 'female') == 0){
                        $check_female = 'checked= "checked"';
                    }else if(strcasecmp($gender, 'else') == 0){
                        $check_else = 'checked= "checked"';
                    }

                    if($married == 1 and $married ){
                        $check_yes = 'checked= "checked"';
                    }
                    else if($married == null) {
                       // $check_yes = 'checked= "checked"';
                    }
                    else if ($married == 0){
                        $check_no = 'checked= "checked"';
                    }
                                      
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            height:auto ;
            width: 500px;
            margin: 0 auto ;
            background-color: white;
        }
    </style>
</head>

<link rel="stylesheet" href="css/dropdown.css">.
<div id='cssmenu'>

<ul>

<li class='active'><a href='index.php'><span>Home</span></a></li>
   <li class='has-sub'><a href='#'><span>Queries</span></a>
	<ul>
        <li class='has-sub'><a href='best_couple.php' ><span>Best Couple of Products</span></a>
        
        <li class='has-sub'><a href='best_positions.php' ><span>Best Positions</span></a>
        <li class='has-sub'><a href='trusted_labeled.php' ><span>Trusted Labeled Products per Category</span></a>
        <li class='has-sub'><a href='expencive_hours.php' ><span>Highest Money Spending Hours</span></a>
        <li class='has-sub'><a href='age_groups_per_hours.php' ><span>Age Groups per Hours</span></a>
        <li class='has-sub'><a href='highest_turnover_product_by_age.php' ><span>Highest Turnover Product by Age</span></a>
        <li class='has-sub'><a href='turnover_by_week_purchase_sex.php' ><span>Turnover by Sex (single purchase)</span></a>
    </li>
    </ul>
    <li class='has-sub'><a href='#'><span>Views</span></a>
    <ul>
        <li class='has-sub'><a href='view_seles.php' ><span>Sales</span></a>
        <li class='has-sub'><a href='view_customers.php' ><span>Customers</span></a>
        </li>
    </ul>
    <li class='active'><a href='super_markets.php' ><span>Super Markets</span></a>
    <li class='active'><a href='categories.php' ><span>Categories</span></a>
    <li class='active'><a href='products.php'><span>Products</span></a></li>
    <li class='active'><a href='customers.php' ><span>Customers</span></a>
    <li class='active'><a href='sales.php' ><span>Sales</span></a>
      
</ul>
<br>
<br>
<br>
<br>
<br>
<br>


<body>
<style type="text/css">
    body{
        background-color: #cccccc;
        background-image: url('photo.png');
        background-attachment:fixed;
        background-position: left top;
   }
</style>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the customer.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Full Name</label>
                            <input type="text" name="fullName" class="form-control" value="<?php echo $fullName; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($points_err)) ? 'has-error' : ''; ?>">
                            <label>points</label>
                            <input type="text" name="points" class="form-control" value="<?php echo $points; ?>">
                            <span class="help-block"><?php echo $points_err;?></span>
                    </div>
                    <p>sex ?(yes is always an aswer)</p>
                    <label>
                        <input type="radio" name="sex" value="male" <?php echo $check_male ?>> Male
                    </label>
                    <label>
                        <input type="radio" name="sex" value="female" <?php echo $check_female ?>> Female
                    </label>
                    <label>
                        <input type="radio" name="sex" value="else"  <?php echo $check_else ?>> else
                    </label>
                    <br><br>
    
                    <div class="form-group <?php echo (!empty($birthDate_err)) ? 'has-error' : ''; ?>">
                        <label>BirthDate (Year-Month-Day)</label>
                        <input type="text" name="birthDate" class="form-control" value="<?php echo $birthDate; ?>">
                        <span class="help-block"><?php echo $birthDate_err;?></span>
                    </div>
                    
                    <p>Is this customer married?</p>
                    <label>
                        <input type="radio" name="terms" value="yes" <?php echo $check_yes ?>> Yes
                    </label>
                    <label>
                        <input type="radio" name="terms" value="no" <?php echo $check_no ?>> No
                    </label>
                    <br><br>

                    <div class="form-group <?php echo (!empty($children_err)) ? 'has-error' : ''; ?>">
                        <label>children</label>
                        <input type="text" name="children" class="form-control" value="<?php echo $children; ?>">
                        <span class="help-block"><?php echo $children_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                        <label>city</label>
                        <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
                        <span class="help-block"><?php echo $city_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($street_err)) ? 'has-error' : ''; ?>">
                        <label>Street Name</label>
                        <input type="text" name="street" class="form-control" value="<?php echo $street; ?>">
                        <span class="help-block"><?php echo $street_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                        <label>Street Number</label>
                        <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
                        <span class="help-block"><?php echo $address_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($postalCode_err)) ? 'has-error' : ''; ?>">
                        <label>Postal Code</label>
                        <input type="text" name="postalCode" class="form-control" value="<?php echo $postalCode; ?>">
                        <span class="help-block"><?php echo $postalCode_err;?></span>
                    </div>
                    <input type="hidden" name="cardID" value="<?php echo $cardID; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="customers.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>       
        </div>
        <br>
    </div>
</body>
</html>