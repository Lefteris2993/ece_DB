<?php
session_start();
// Define variables and initialize with empty values
$mindate = $maxdate = $minprice = $maxprice = $minpieces = $maxpieces = "";
$mindate_err = $maxdate_err = $minprice_err = $maxprice_err = $minpieces_err = $maxpieces_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){


    


       // Validate min DATE 
        $input_mindate = trim($_POST["mindate"]);
        if (empty($input_mindate)){
            $mindate = "0000-00-00 00:00:00";
        }
        else if ( ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$input_mindate)){
            $mindate_err = "Please enter a valid minmum date (yyyy-mm-dd)";
        } else{
            $mindate = $input_mindate." 00:00:00";
        }


        // max date
        $input_maxdate = trim($_POST["maxdate"]);
        if (empty($input_maxdate)){
            require_once "config.php";
            $sql = "select max(date_time) from purchase";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            if (empty($row)){
                $maxprice_err = "it cant be  maxdate !!";
            }  
            $maxdate = $row[0];
            mysqli_free_result($result);
        }
        else if ( ! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$input_maxdate)){
            $maxdate_err = "Please enter a valid maximum date (yyyy-mm-dd)";
        } else{
            $maxdate = $input_maxdate." 00:00:00";
        }   
         
        // Validate min price
        $input_minprice = trim($_POST["minprice"]);
        if (empty($input_minprice)){
            $minprice = 0;
        }
        else if(!is_numeric ($input_minprice) or $input_minprice < 0){
            $minprice_err = "Please enter minimum price.";
        } else{
            $minprice = $input_minprice;
        }


        // max price
        $input_maxprice = trim($_POST["maxprice"]);
        if (empty($input_maxprice)){
            require_once "config.php";
            $sql = "select max(totalCost) as maxp from purchase";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            if (empty($row)){
                $maxprice_err = "it cant be !!";
            }  
            $maxprice = $row[0];
            mysqli_free_result($result);
        }
        else if(!is_numeric ($input_maxprice) or $input_maxprice < 0){
            $maxprice_err = "Please enter maximum price.";
        } else{
            $maxprice = $input_maxprice;
        }


        // Validate min pieces
        $input_minpieces = trim($_POST["minpieces"]);
        if (empty($input_minpieces)){
            $minpieces = 0;
        }
        else if(!is_numeric ($input_minpieces) or $input_minpieces < 0){
            $minpieces_err = "Please enter minimum pieces.";
        } else{
            $minpieces = $input_minpieces;
        }


        // max pieces
        $input_maxpieces = trim($_POST["maxpieces"]);
        if (empty($input_maxpieces)){
            require_once "config.php";
            $sql = "select max(niko.total)  from (SELECT DISTINCT purchaseID, SUM(quantity) over (partition by contains.purchaseID)
            as total from contains) as niko";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            if (empty($row)){
                $maxpieces_err = "it cant be 2 !!";
            }  
            $maxpieces = $row[0];
            mysqli_free_result($result);
        }
        else if(!is_numeric ($input_maxpieces) or $input_maxpieces < 0){
            $maxpieces_err = "Please enter maximum price.";
        } else{
            $maxpieces = $input_maxpieces;
        }

        $marketID = $_POST['inputState3'];

        // category
            $catType = $_POST['inputState2'];



        // payment method
            $payM = $_POST['inputState1'];



        // Check to see sales
        if( empty($minprice_err) && empty($maxprice_err) && empty($minpieces_err) && empty($maxpieces_err) && empty($mindate_err) && empty($maxdate_err))
        {   mysqli_close($link);
            $_SESSION["minprice"] = $minprice;
            $_SESSION["catType"] = $catType;
            $_SESSION["maxprice"] = $maxprice;
            $_SESSION["minpieces"] = $minpieces;
            $_SESSION["maxpieces"] = $maxpieces;
            $_SESSION["mindate"] = $mindate;
            $_SESSION["maxdate"] = $maxdate;
            $_SESSION["payM"] = $payM;
            $_SESSION["marketID"] = $marketID;
            

            header("location: sales_filter.php");
            exit();       
        }
        else{
            $mindate = $maxdate = $minprice = $maxprice = $minpieces = $maxpieces = "";
            
        }
    
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
                        <h2>view sales</h2>
                    </div>
                    <p>Please fill this form to view the sales filered.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                            <label for="inputState3">Super Market</label>
                            <select id="inputState3" class="form-control" name="inputState3">
                            <?php  require_once "config.php";
                    
                                // Attempt select query execution
                                $sql = "SELECT marketID from market";
                                $options = "<option selected> </option>";
                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                            while($row = mysqli_fetch_array($result)){
                                                $options = $options ."<option>" . $row['marketID'] . "</option>"; 
                                            }
                                        // Free result set
                                        mysqli_free_result($result);
                                    } else{
                                        echo "<p class='lead'><em>No records were found.</em></p>";
                                    }
                                } else{
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                                }
                                echo $options;
                              ?> 
                            </select>
                            </div>
                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group <?php echo (!empty($mindate_err)) ? 'has-error' : ''; ?>">
                                    <label>Minmum Date</label>
                                    <input type="text" name="mindate" class="form-control" value="<?php echo $mindate; ?>">
                                    <span class="help-block"><?php echo $mindate_err;?></span>
                                    </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                    <div class="form-group <?php echo (!empty($maxdate_err)) ? 'has-error' : ''; ?>">
                                    <label>Maximum Date</label>
                                    <input type="text" name="maxdate" class="form-control" value="<?php echo $maxdate; ?>">
                                    <span class="help-block"><?php echo $maxdate_err;?></span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>

                            <div class="form-group col-md-6">
                                <div class="form-group <?php echo (!empty($minprice_err)) ? 'has-error' : ''; ?>">
                                    <label>Minmum Price</label>
                                    <input type="text" name="minprice" class="form-control" value="<?php echo $minprice; ?>">
                                    <span class="help-block"><?php echo $minprice_err;?></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="form-group <?php echo (!empty($maxprice_err)) ? 'has-error' : ''; ?>">
                                    <label>Maximum Price</label>
                                    <input type="text" name="maxprice" class="form-control" value="<?php echo $maxprice; ?>">
                                    <span class="help-block"><?php echo $maxprice_err;?></span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-group <?php echo (!empty($minpieces_err)) ? 'has-error' : ''; ?>">
                                    <label>Minmum pieces</label>
                                    <input type="text" name="minpieces" class="form-control" value="<?php echo $minpieces; ?>">
                                    <span class="help-block"><?php echo $minpieces_err;?></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="form-group <?php echo (!empty($maxpieces_err)) ? 'has-error' : ''; ?>">
                                    <label>Maximum pieces</label>
                                    <input type="text" name="maxpieces" class="form-control" value="<?php echo $maxpieces; ?>">
                                    <span class="help-block"><?php echo $maxpieces_err;?></span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                            </div>
                            <br>
                            <br>
                            <br>
            
                            <div class="form-group">
                            <label for="inputState1">Payment method</label>
                            <select id="inputState1" class="form-control" name="inputState1">
                                <option selected> </option>
                                <option>cash</option>
                                <option>card</option>
                            </select>
                            </div>
                            <div class="form-group">
                            <label for="inputState2">Category type</label>
                            <select id="inputState2" class="form-control" name="inputState2">
                            <?php  require_once "config.php";
                    
                                // Attempt select query execution
                                $sql = "SELECT catType from category";
                                $options = "<option selected> </option>";
                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                            while($row = mysqli_fetch_array($result)){
                                                $options = $options ."<option>" . $row['catType'] . "</option>"; 
                                            }
                                        // Free result set
                                        mysqli_free_result($result);
                                    } else{
                                        echo "<p class='lead'><em>No records were found.</em></p>";
                                    }
                                } else{
                                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                                }
                                echo $options;
                              ?> 
                            </select>
                            </div>
                            <input type="submit" class="btn btn-primary pull-right" value="Submit">
                            <a href="index.php" class="btn btn-default pull-left">Cancel</a>  
                    </form>
                </div>
            </div>        
        </div>
        <br>
    </div>
</body>
</html>