<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Suggestion</title>
</head>
<body>
    <div class="prod-suggestion">
        <?php
            require_once('config/mysql_connect.php');

            $current_prod = $_SESSION['prod_id'];

            $query = "SELECT prod_id, prod_img, prod_name, prod_price FROM products WHERE prod_type = 'Video Game' 
                AND prod_avail = 'In Stock' AND prod_id <> $current_prod ORDER BY date_added desc LIMIT 5";
                
            $result = @mysqli_query($dbc, $query);
            
            if($result) {
                //echo '<h3 style="font-family: Verdana; text-align:Center">Suggested Items</h3><a href="instock.php" style="float:left;">< In Stock</a><a class="selection_link" href="rental_solution.php" style="float:right;">Rental ></a><hr>';
                echo '<center><h4>People also buy</h4></center>';
                echo '<table align="center" cellspacing="1" cellpadding="8" style="overflow-x:auto;"><tr>';
                
                for($i=0, $max = 5; $row = mysqli_fetch_array($result, MYSQLI_NUM);) {
                    if($i++ % $max == 0)
                            echo '<tr>';
                        
                    echo '<td><center><a href="product_page.php?prod_id=' . $row[0] .'"><img src="' . $row[1] . '" width="170px" height="250px"/><br />' . $row[2] . '<br />RM ' . number_format($row[3], 2) . '</a></center></td>';
                    $_GET["prod_id"] = $row[0];
                    
                    if ($i % $max == 0)
                        echo '</tr>';
                }
                echo '</table><br />';
                mysqli_close($dbc);
            }else{
                printf("Error suggestion:", mysqli_error($dbc));
            }
        ?>
    </div>
</body>
</html>