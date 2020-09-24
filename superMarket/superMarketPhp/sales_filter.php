<?php session_start(); ?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
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

<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />


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
                <div class="page-header clearfix">
                        <h2 class="pull-left">...</h2>
                        <a href="sales.php" class="btn btn-success pull-right" 
                        style="background-color: #337ab7; border-color: #337ab7;color:white;width:auto;
                        height:auto;">Try again</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    $minprice = $_SESSION['minprice'];
                    $maxprice = $_SESSION['maxprice'];
                    $minpieces = $_SESSION['minpieces'];
                    $maxpieces = $_SESSION['maxpieces'];
                    $mindate = $_SESSION["mindate"];
                    $maxdate = $_SESSION['maxdate'];
                    $payM = $_SESSION['payM'];
                    $catType = $_SESSION['catType'];
                    $marketID = $_SESSION['marketID'];

                    if (!empty($payM)){
                        $temp = $payM;
                        $payM  = "and purchase.paymentMethod = '$temp'";
                    }
                    $cat1 = $cat2 = "";
                    if (!empty($catType)){
                        $temp1 = $catType;
                        $cat1 = "(SELECT DISTINCT purchaseID from contains
                        INNER JOIN
                        (select DISTINCT barcode from category, product WHERE  product.categoryID = category.categoryID and category.catType = '$temp1') as b 
                        on contains.barcode = b.barcode) as bb,";

                        $cat2 = "= bb.purchaseID and bb.purchaseID";                    
                    }
                    $mark2 = "";
                    if (!empty($marketID)){
                        $mark2 = "and $marketID = cc.marketID";
                    }

                    $sql = "SELECT  DISTINCT
                    cc.purchaseID as purchaseID, dd.fullname as fullname, cc.marketID as marketID, cc.date_time as date_time, cc.paymentMethod as paymentMethod, cc.totalCost as totalCost, aa.total as totalQuantity, cc.points as points
                    from
                    (SELECT purchase.purchaseID, a.total, purchase.marketID from purchase 
                    INNER JOIN (select * from (SELECT DISTINCT purchaseID, SUM(quantity) over (partition by contains.purchaseID)
                    as total from contains) as niko where niko.total BETWEEN $minpieces and $maxpieces) as a 
                    on a.purchaseID = purchase.purchaseID) as aa,
                    
                    $cat1
                    
                    (SELECT * FROM purchase WHERE (purchase.date_time BETWEEN '$mindate' and '$maxdate')

                    $payM 
                    
                    and purchase.totalCost BETWEEN $minprice and $maxprice) as cc,
                    
                    (select customer.fullName, purchase.purchaseID from customer, purchase WHERE purchase.cardID = customer.cardID) as dd
                    
                    WHERE cc.purchaseID $cat2 = aa.purchaseID and cc.purchaseID = dd.purchaseID $mark2 ";

                    
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Purchase ID</th>";
                                    echo "<th>Full name</th>";
                                    echo "<th>Market ID</th>";
                                    echo "<th>date-time</th>";
                                    echo "<th>Payment Method</th>";
                                    echo "<th>Total Cost</th>";
                                    echo "<th>Total Pieces</th>";
                                    echo "<th>Total Points</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['purchaseID'] . "</td>";
                                        echo "<td>" . $row['fullname'] . "</td>";
                                        echo "<td>" . $row['marketID'] . "</td>";
                                        echo "<td>" . $row['date_time'] . "</td>";
                                        echo "<td>" . $row['paymentMethod'] . "</td>";
                                        echo "<td>" . $row['totalCost'] . "</td>";
                                        echo "<td>" . $row['totalQuantity'] . "</td>";
                                        echo "<td>" . $row['points'] . "</td>";
                                    echo "</tr>";
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
                    mysqli_close($link);
                    ?>
                    
                </div>
            </div>        
        </div>
    </div>


</body>
</html>
