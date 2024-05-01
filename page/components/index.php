<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js
"></script>
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css
" rel="stylesheet" />
<!-- 
    this code sets up the basic structure of an HTML document, includes a navigation component, and allows for dynamic content rendering. 
    Additionally, it includes the necessary JavaScript file for Bootstrap functionality. -->
    <?php
    require 'nav.php';
    ?>
</head>

<body>
    <?php echo $content ?? ''; ?>

    <script src="/assets/js/bootstrap.bundle.js"></script>
</body>

</html>