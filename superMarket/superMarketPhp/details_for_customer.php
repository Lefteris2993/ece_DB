<?php
session_start();
$marketID =  0;
if (!empty($_SESSION['customer'])){
    $customer = $_SESSION['customer'];

}
else{
    $customer =  trim($_GET["cardID"]);
    $_SESSION["customer"] = $customer;
}


if (!empty($_SESSION['market'])){
    $marketID = $_SESSION['market'];
    $_SESSION["market"] = NULL;


}
// Processing form data when form is submitted

require_once "config.php";
$sql = "SELECT * FROM goesto , purchase WHERE purchase.cardID = $customer and purchase.marketID = goesto.marketID and purchase.cardID = goesto.cardID and purchase.marketID = $marketID";
            if ($result = mysqli_query($link, $sql)){
                $hours = array();
                $dataPoints = array();
                for ($i = 0; $i < 24; $i++){
                    array_push($hours, 0);
                }
                while($row = mysqli_fetch_array($result)){
                    $a = $row['date_time'];
                    $a = strtotime($a);
                    $a = idate('H', $a);
                    $hours[$a] = $hours[$a] + 1;
                }
                for ($i = 0; $i < 24; $i++){
                    array_push($dataPoints,array("y" => $hours[$i], "label" => "$i"));
                }
                mysqli_free_result($result);
            } else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                    // Include config file
                    $marketID = $_POST['valekati'];
                    
                    
                    $_SESSION["market"] = $marketID;
                    header('Refresh: 0');
        
    
            }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            height:auto ;
            width: 800px;
            margin: 0 auto ;
            background-color: white;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<?php
    $_SESSION['auth'] = 0;
?>

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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Top 10 products</h2>
                        <a href="customers.php" class="btn btn-success pull-right" 
                        style="background-color: #337ab7; border-color: #337ab7;color:white;width:auto;
                        height:auto;">go back</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                  

                    // Attempt select query execution
                    $sql = "SELECT product.name, product.barcode, top.total FROM product,
                    (SELECT  DISTINCT barcode, total from 
                    (SELECT contains.barcode, sum(contains.quantity) over (partition by contains.barcode) as total FROM
                    contains INNER JOIN (SELECT purchaseID FROM purchase where purchase.cardID = $customer) as a 
                    on contains.purchaseID = a.purchaseID) as qsifsa ORDER BY total DESC LIMIT 10) as top WHERE top.barcode = product.barcode";


                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Product name</th>";
                                    echo "<th>barcode</th>";
                                    echo "<th>Total amount</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['barcode'] . "</td>";
                                        echo "<td>" . $row['total'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    ?>


                    <div class="page-header clearfix">
                        <h2 class="pull-left">Markets visited (Total: <?php
                    require_once "config.php";
                    $sql = "SELECT market.marketID, city, street from market 
                    INNER JOIN (SELECT * from goesto WHERE cardID = $customer) as a
                    on a.marketID = market.marketID";
                    if($result = mysqli_query($link, $sql)){;
                        echo "<td>" . mysqli_num_rows($result) . "</td>";
                        mysqli_free_result($result);
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    ?>) </h2>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT market.marketID, city, street from market 
                    INNER JOIN (SELECT * from goesto WHERE cardID = $customer) as a
                    on a.marketID = market.marketID";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>MarketID</th>";
                                    echo "<th>City</th>";
                                    echo "<th>Street</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['marketID'] . "</td>";
                                        echo "<td>" . $row['city'] . "</td>";
                                        echo "<td>" . $row['street'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
                    ?>
                    
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Market  visited time-table</h2>
                    </div>
                  

                    <div class="form-group">
                            <label for="valekati">Choose Market</label>
                            <select id="valekati" class="form-control" name="valekati">
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
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default pull-left"></a>  
                    
                    
                   
                    <script>
                        window.onload = function () {
                        
                        var chart = new CanvasJS.Chart("chartContainer", {
                            title: {
                                text: "Hours they visit"
                            },
                            axisY: {
                                title: "times"
                            },
                            data: [{
                                type: "stepLine",
                                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                            }]
                        });
                        chart.render();
                        
                        }
                    </script>
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

                    <div class="page-header clearfix">
                        <h2 class="pull-left">Average sales per week: <?php
                    require_once "config.php";
                    $sql = "SELECT *, sum(totalCost) /  COUNT(*) as mean FROM
                    (SELECT purchase.totalCost, purchase.purchaseID
                    FROM purchase
                    WHERE purchase.cardID = $customer and DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) <= DATE(purchase.date_time) and DATE(purchase.date_time) <= CURRENT_DATE) as a";
                    if($result = mysqli_query($link, $sql)){
                        while($row = mysqli_fetch_array($result)){
                            echo '<td>' . number_format((float)$row['mean'], 2, '.', ''). " € on average" . '</td>';
                        }
                        
                        mysqli_free_result($result);   
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
                    ?>  </h2>
                    </div>
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Average sales per month: <?php
                    require_once "config.php";
                    $sql = "SELECT *, sum(totalCost) /  COUNT(*) as mean FROM
                    (SELECT purchase.totalCost, purchase.purchaseID
                    FROM purchase
                    WHERE purchase.cardID = $customer and DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY) <= DATE(purchase.date_time) and DATE(purchase.date_time) <= CURRENT_DATE) as a";
                    if($result = mysqli_query($link, $sql)){
                        while($row = mysqli_fetch_array($result)){
                            echo '<td>' . number_format((float)$row['mean'], 2, '.', ''). " € on average" . '</td>';
                        }
                        
                        mysqli_free_result($result);   
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
                    mysqli_close($link);
                    ?>  </h2>
                    </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>