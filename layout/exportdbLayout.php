<html>
<head>
    <meta charset="UTF-8">
    <title>EXPORT DB</title>
    <link rel="shortcut icon" href="public/img/export.ico">
    <link rel="stylesheet" href="<?php echo __FOLDER."public/login/css/reset.css"?>">

    <link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="<?php echo __FOLDER."public/css/nice-select.css"?>">
    <link rel="stylesheet" href="<?php echo __FOLDER."public/exportdb/css/style.css"?>">
</head>

<body>
    <div class="container">
        <?php echo $content->render()?>
    </div>
</body>

	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>exportdb/js/index.js"></script>
    <script src="<?php echo __FOLDER . 'public/'?>/js/jquery.nice-select.min.js"></script>
</html>