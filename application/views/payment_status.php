<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
</head>
<body>
    <?php

        if($paymentStatus == "SUCCESS")
        {
            echo "Ödeme işlemi başarılı şekilde gerçekleşti.";
        }
        else
        {
            echo "Bir hata oldu.";
        }

    ?>
</body>
</html>