<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$sq = $address = $city = $street = $postalCode = "";
$sq_err = $address_err = $street_err = $postalCode_err = $city_err  = "";

// Processing form data when form is submitted
if(isset($_POST["marketID"]) && !empty($_POST["marketID"])){

    $marketID = $_POST["marketID"];
        




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
            $postalCode = null ;     
        } elseif(!ctype_digit($input_postalCode) || $input_postalCode>99999){
            $postalCode_err = "Please enter a valid postal code";
        } else{
            $postalCode = $input_postalCode;
        }
    }


    // sqr metre
    $input_sq = trim($_POST["sq"]);
    if(empty($input_sq)){
        $sq = NUll;
    } elseif($input_sq < 0){
        $sq_err = "Please enter a valid city.";
    } else{
        $sq = $input_sq;
    }

 
    
    if( empty($sq_err) && empty($city_err) && empty($street_err) && empty($address_err) && empty($postalCode_err))
    {   
        $sql = "UPDATE market SET sqrMeters=?, city=?, street=?, address=?, postalCode = ? WHERE marketID = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi",  $param_sq, $param_city , $param_street, $param_address, $param_postalCode, $marketID);

            // Set parameters
            $param_sq = $sq;
            $param_city = $city;
            $param_street = $street;
            $param_address = $address;
            $param_postalCode = $postalCode;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: super_markets.php");
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
    if(isset($_GET["marketID"]) && !empty(trim($_GET["marketID"]))){
        // Get URL parameter
      
        $marketID =  trim($_GET["marketID"]);

        // Prepare a select statement
        $sql = "SELECT * FROM market  WHERE marketID = ? ";

        if($stmt = mysqli_prepare($link, $sql)){
            
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $marketID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                   


                    if ($row["sqrMeters"] == null){
                        $sq = "";
                    }else {
                        $sq = $row["sqrMeters"];
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
                    if ($row["postalCode"] == null){
                        $postalCode = "";
                    }else {
                        $postalCode= $row["postalCode"];
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
                    <p>Please edit the input values and submit to update the market.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

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


                        <div class="form-group <?php echo (!empty($sq_err)) ? 'has-error' : ''; ?>">
                            <label>Square Metres (m^2) </label>
                            <input type="text" name="sq" class="form-control" value="<?php echo $sq; ?>">
                            <span class="help-block"><?php echo $sq_err;?></span>
                        </div>

                    <input type="hidden" name="marketID" value="<?php echo $marketID; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="super_markets.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>       
        </div>
        <br>
    </div>
</body>
</html>