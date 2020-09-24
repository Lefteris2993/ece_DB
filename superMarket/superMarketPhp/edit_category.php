<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = "";
$name_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["categoryID"]) && !empty($_POST["categoryID"])){

    $categoryID = $_POST["categoryID"];
    


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


    
    if(empty($name_err))
    {   
        $sql = "UPDATE category SET catType='$name' WHERE categoryID=$categoryID";
        //$sql = "UPDATE product SET 'name'=?, categoryID=?, price=? , labeled=? WHERE barcode=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_name, $param_categoryID );

            // Set parameters
            $param_name = $name;


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: categories.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
        else {
            echo $sql;
        }
    }

    

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["categoryID"]) && !empty(trim($_GET["categoryID"]))){
        // Get URL parameter
        $categoryID =  trim($_GET["categoryID"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM category  WHERE categoryID =?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $categoryID;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value

                    $name = $row["catType"];


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
                            <label>Category name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        
                        <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="categories.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>       
        </div>
        <br>
    </div>
</body>
</html>