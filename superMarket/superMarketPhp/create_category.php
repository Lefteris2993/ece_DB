<?php
 
// Define variables and initialize with empty values
$name = "";
$name_err = "";

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

    

    // Check input errors before inserting in database
    if( empty($name_err) )
    {
        // Prepare an insert statement
        // Include config file
        require_once "config.php";

        $sql = "INSERT INTO category (catType) VALUES (?)";

        $stmt = $link->prepare($sql) ;
        if($stmt != NULL){
            // Bind variables to the prepared statement as parameters
            
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Set parameters
            $param_name = $name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: categories.php");
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
                            <label>Category name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        
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