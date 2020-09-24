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
                        <h2 class="pull-left">Highest turnover products by age (top 3)</h2>
                    </div>
                    <h2 class="pull-left">Age range 0 to 18</h2>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT DISTINCT  product.name, product.barcode, kidpart as kidturn FROM product,
                    (SELECT *, sum(totalprice) over (partition by barcode, kid) * kid as kidpart, sum(totalprice) over (partition by barcode, ergenis) * ergenis as ergpart, sum(totalprice) over (partition by barcode, mesilikas) *mesilikas as mespart, sum(totalprice) over (partition by barcode, barbas) * barbas as barpart  FROM
                    (SELECT b.barcode, b.totalprice, b.age, age < 19 as kid, age > 18 and age < 31 as ergenis, age >30 and age < 61 as mesilikas, age > 60 as barbas FROM
                    (SELECT c.barcode, c.totalprice, YEAR(CURRENT_DATE) - YEAR(customer.birthDate) as age FROM customer, 
                    (SELECT purchase.cardID, a.barcode, a.quantity * product.price as totalprice FROM product, purchase, 
                    (SELECT purchaseID, barcode, quantity FROM contains) as a
                    WHERE a.purchaseID = purchase.purchaseID and product.barcode = a.barcode) as c
                    WHERE customer.cardID = c.cardID) as b) as d) as e
                    WHERE product.barcode = e.barcode 
                    ORDER BY kidturn DESC LIMIT 3";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Name</th>";
                                    echo "<th>Barcode</th>";
                                    echo "<th>Turnover</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['barcode'] . "</td>";
                                        echo "<td>" . number_format((float)$row['kidturn'], 2, '.', '')." €"  . "</td>";
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

                        <h2 class="pull-left">Age range 19 to 30</h2>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT DISTINCT  product.name, product.barcode, ergpart as ergturn FROM product,
                    (SELECT *, sum(totalprice) over (partition by barcode, kid) * kid as kidpart, sum(totalprice) over (partition by barcode, ergenis) * ergenis as ergpart, sum(totalprice) over (partition by barcode, mesilikas) *mesilikas as mespart, sum(totalprice) over (partition by barcode, barbas) * barbas as barpart  FROM
                    (SELECT b.barcode, b.totalprice, b.age, age < 19 as kid, age > 18 and age < 31 as ergenis, age >30 and age < 61 as mesilikas, age > 60 as barbas FROM
                    (SELECT c.barcode, c.totalprice, YEAR(CURRENT_DATE) - YEAR(customer.birthDate) as age FROM customer, 
                    (SELECT purchase.cardID, a.barcode, a.quantity * product.price as totalprice FROM product, purchase, 
                    (SELECT purchaseID, barcode, quantity FROM contains) as a
                    WHERE a.purchaseID = purchase.purchaseID and product.barcode = a.barcode) as c
                    WHERE customer.cardID = c.cardID) as b) as d) as e
                    WHERE product.barcode = e.barcode 
                    ORDER BY ergturn DESC LIMIT 3";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Name</th>";
                                    echo "<th>Barcode</th>";
                                    echo "<th>Turnover</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['barcode'] . "</td>";
                                        echo "<td>" . number_format((float)$row['ergturn'], 2, '.', '') ." €"  . "</td>";
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

                    <h2 class="pull-left">Age range 31 to 60</h2>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT DISTINCT  product.name, product.barcode, mespart as mesturn FROM product,
                    (SELECT *, sum(totalprice) over (partition by barcode, kid) * kid as kidpart, sum(totalprice) over (partition by barcode, ergenis) * ergenis as ergpart, sum(totalprice) over (partition by barcode, mesilikas) *mesilikas as mespart, sum(totalprice) over (partition by barcode, barbas) * barbas as barpart  FROM
                    (SELECT b.barcode, b.totalprice, b.age, age < 19 as kid, age > 18 and age < 31 as ergenis, age >30 and age < 61 as mesilikas, age > 60 as barbas FROM
                    (SELECT c.barcode, c.totalprice, YEAR(CURRENT_DATE) - YEAR(customer.birthDate) as age FROM customer, 
                    (SELECT purchase.cardID, a.barcode, a.quantity * product.price as totalprice FROM product, purchase, 
                    (SELECT purchaseID, barcode, quantity FROM contains) as a
                    WHERE a.purchaseID = purchase.purchaseID and product.barcode = a.barcode) as c
                    WHERE customer.cardID = c.cardID) as b) as d) as e
                    WHERE product.barcode = e.barcode 
                    ORDER BY mesturn DESC LIMIT 3";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Name</th>";
                                    echo "<th>Barcode</th>";
                                    echo "<th>Turnover</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['barcode'] . "</td>";
                                        echo "<td>" . number_format((float)$row['mesturn'], 2, '.', '') ." €"  . "</td>";
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

                    <h2 class="pull-left">Age range 61 +</h2>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT DISTINCT  product.name, product.barcode, barpart as barturn FROM product,
                    (SELECT *, sum(totalprice) over (partition by barcode, kid) * kid as kidpart, sum(totalprice) over (partition by barcode, ergenis) * ergenis as ergpart, sum(totalprice) over (partition by barcode, mesilikas) *mesilikas as mespart, sum(totalprice) over (partition by barcode, barbas) * barbas as barpart  FROM
                    (SELECT b.barcode, b.totalprice, b.age, age < 19 as kid, age > 18 and age < 31 as ergenis, age >30 and age < 61 as mesilikas, age > 60 as barbas FROM
                    (SELECT c.barcode, c.totalprice, YEAR(CURRENT_DATE) - YEAR(customer.birthDate) as age FROM customer, 
                    (SELECT purchase.cardID, a.barcode, a.quantity * product.price as totalprice FROM product, purchase, 
                    (SELECT purchaseID, barcode, quantity FROM contains) as a
                    WHERE a.purchaseID = purchase.purchaseID and product.barcode = a.barcode) as c
                    WHERE customer.cardID = c.cardID) as b) as d) as e
                    WHERE product.barcode = e.barcode 
                    ORDER BY barturn DESC LIMIT 3";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Name</th>";
                                    echo "<th>Barcode</th>";
                                    echo "<th>Turnover</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['barcode'] . "</td>";
                                        echo "<td>" . number_format((float)$row['barturn'], 2, '.', '') ." €"  . "</td>";
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