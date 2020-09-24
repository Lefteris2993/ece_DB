<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$price = $name = $labeled = $categoryID = $check_no = $check_yes = $categoryy = "";
$price_err  = $labeled_err = $categoryID_err = $name_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["barcode"]) && !empty($_POST["barcode"])){

    $barcode = $_POST["barcode"];
    
    // Validate catID
    require_once "config.php";  
    $input_catID = trim($_POST["categoryy"]);
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
    
    
    if(empty($name_err) && empty($barcode_err) && empty($price_err) && empty($labeled_err) && empty($categoryID_err) )
    {   
        $sql = "UPDATE product SET name='$name', categoryID=$categoryID, price=$price , labeled=$labeled WHERE barcode=$barcode";
        //$sql = "UPDATE product SET 'name'=?, categoryID=?, price=? , labeled=? WHERE barcode=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $param_name, $param_categoryID, $param_price, $param_labeled, $param_barcode );

            // Set parameters
            $param_name = $name;
            $param_categoryID= $categoryID;
            $param_price = $price;
            $param_labeled = $labeled;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: products.php");
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
    if(isset($_GET["barcode"]) && !empty(trim($_GET["barcode"]))){
        // Get URL parameter
        $barcode =  trim($_GET["barcode"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM product, category  WHERE barcode =? and product.categoryID = category.categoryID";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $barcode;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $categoryy = $row["catType"];
                    $name = $row["name"];
                    $price = $row["price"];
                    $labeled = $row["labeled"];

                    if ($labeled == 0 ){
                        $check_no = 'checked= "checked"';
                    }
                    else{
                        $check_yes = 'checked= "checked"';
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
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Product name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($categoryID_err)) ? 'has-error' : ''; ?>">
                            <label>category type</label>
                            <input type="text" name="categoryy" class="form-control" value="<?php echo $categoryy; ?>">
                            <span class="help-block"><?php echo $categoryID_err;?></span>
                        </div>

                        <label>
                            <input type="radio" name="terms" value="yes"<?php echo $check_yes ?> > Yes
                        </label>
                        <label>
                            <input type="radio" name="terms" value="no" <?php echo $check_no ?> > No
                        </label>
                        <br><br>
                        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="hidden" name="barcode" value="<?php echo $barcode; ?>"/>
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