<?php
    $user = "Randy";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Echo Sample</title>
    
</head>
<body>
    <h1>Welcome  <?php echo $user ?>!</h1>
    <p>
        <?php
            echo "<strong>";
            echo date("H:i:s");
            echo "</strong>";
        ?>
    </p>
    <p>
        <?php
            $count = 0;
            while ($count < 10) {
                echo $count . "<br>";
                $count++;
            }
            $count = 0;
        ?>
    </p>
</body>
</html>