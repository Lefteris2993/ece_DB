<?php
 
// Define variables and initialize with empty values
$barcode = $price = $name = $labeled = $categoryID = $category = "";
$barcode_err = $price_err  = $labeled_err = $categoryID_err = $name_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate Name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    }
    else if (ctype_alpha(str_replace(' ', '', $input_name)) === false) {
        $name_err = 'Name must contain letters and spaces only';
    }
    else{
        $name = $input_name;
    }

    // Validate barcode
    $input_barcode = trim($_POST["barcode"]);
    if(empty($input_barcode)){
        $barcode_err = "Please enter the barcode.";
    
    } elseif($input_barcode < 99 and $input_barcode >999){
        $barcode_err = "Please enter a valid barcode.";
    } else{
        $barcode = $input_barcode;
    }

    // Validate catID
    require_once "config.php";  
    $input_catID = trim($_POST["category"]);
    if(empty($input_catID)){
        $categoryID_err = "Please enter the barcode.";
    }
    $sql1 = "select categoryID from category where catType = '$input_catID' ";
    $result = mysqli_query($link, $sql1);
    $row = mysqli_fetch_array($result);
    if (empty($row)){
        $categoryID_err = "category type '$input_catID' does not exit";
    }
    $categoryID = $row[0];
    

    // Validate price
    $input_price = trim($_POST["price"]);
    $input_price = number_format($input_price, 2);
    if(empty($input_price)){
        $price_err = "Please enter a price.";
    } elseif($input_price < 0.01 ){
        $price_err = "Please enter a valid price.";
    } else{
        $price = $input_price;
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
                $labeled = 1;
            }
            else {
                $labeled = 0;
            }
        }
    }
    /* Validate labeled
    $input_labeled = trim($_POST["labeled"]);

    if ($input_labeled != 0 and $input_labeled != 1){
        $labeled_err = "Please enter 0 for no, 1 for yes";
    }
    else{
        $labeled = $input_labeled;
    }
    */

    // Check input errors before inserting in database
    if( empty($name_err) && empty($barcode_err) && empty($price_err) && empty($labeled_err) && empty($categoryID_err) )
    {
        // Prepare an insert statement
        // Include config file
        require_once "config.php";

        $sql = "INSERT INTO product (name,barcode,categoryID,price,labeled) VALUES (?, ?, ?, ?, ?)";

        $stmt = $link->prepare($sql) ;
        if($stmt != NULL){
            // Bind variables to the prepared statement as parameters
            
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_barcode , $param_categoryID, $param_price, $param_labeled);
            
            // Set parameters
            $param_name = $name;
            $param_barcode = $barcode;
            $param_categoryID= $categoryID;
            $param_price = $price;
            $param_labeled = $labeled;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: products.php");
                exit();
            } else{
                echo " 0 Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
        else{
            echo "Something went wrong. Please try again later!!!";
        }        
    }
    
    
    // Close connection
    require_once "config.php";
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a product to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Product name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($barcode_err)) ? 'has-error' : ''; ?>">
                            <label>barcode</label>
                            <input type="text" name="barcode" class="form-control" value="<?php echo $barcode; ?>">
                            <span class="help-block"><?php echo $barcode;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($categoryID_err)) ? 'has-error' : ''; ?>">
                            <label>category type</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
                            <span class="help-block"><?php echo $categoryID_err;?></span>
                        </div>
                        <p>Is that product labeled ?</p>
                        <label>
                            <input type="radio" name="terms" value="yes"> Yes
                        </label>
                        <label>
                            <input type="radio" name="terms" value="no" checked= "checked"> No
                        </label>
                        <br><br>
                        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="products.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
        <br>
    </div>
</body>
</html>