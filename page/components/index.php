<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/sweetalert.all.min.js"></script>
    <link href="/assets/css/sweetalert.min.css" rel="stylesheet" />
    <!-- 
    this code sets up the basic structure of an HTML document, includes a navigation component, and allows for dynamic content rendering. 
    Additionally, it includes the necessary JavaScript file for Bootstrap functionality. -->
    <?php
    require 'nav.php';
    ?>
</head>

<body>
    <?php echo $content ?? ''; ?>

    <script>
        // This JavaScript code defines a
        // function called "currencyRupiah"
        // that takes a numeric value "number"
        // as its parameter.Within the
        // function, it creates a new instance of the Intl.NumberFormat object with the locale set to 'id-ID'(Indonesian) and specifies the style as 'currency'
        // with the currency type as 'IDR'(Indonesian Rupiah).It then formats the provided number according to the specified currency format.This
        // function essentially converts a numeric value into a string representation formatted as Indonesian Rupiah, suitable
        // for displaying monetary values in a localized and standardized format within a user interface.
        const currencyRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number)
        }
    </script>
    <script src="/assets/js/bootstrap.bundle.js"></script>
</body>

</html>