<?php

    session_start();

    if (file_exists("../core/includes/classes/validator.php"))
    {
        include_once "../core/includes/classes/validator.php";

        $validator = new Validator(array('st' => 'int'), array('st'), array('st'));

        if ($validator->validate($_GET))
        {
            $_GET = $validator->sanatize($_GET);
            $step = $_GET['st'];
        }
    }
    elseif (is_numeric($_GET['st']))
    {
        $step = $_GET['st'];
    }
    else 
    {
        header("Location: ./index.php?error=url_error");
    }

    $steps = array
    (
        1 => 'Database Connection & General Info',
        2 => 'Configuration File',
        3 => 'Create database & Write configuration file',
        4 => 'Updates',
        5 => 'Adding your first realm',
        6 => 'Finished'
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript" src="other.js"></script>
        <meta charset="utf-8">
        <title>CraftedWeb Installer</title>

        <style type="text/css">

            ::selection{ background-color: #06C; color: #fff; }
            ::moz-selection{ background-color: #06C; color: #fff; }
            ::webkit-selection{ background-color: #06C; color: #fff; }

            body { background-color: #efefef; margin: 40px; font: 13px/20px normal Helvetica, Arial, sans-serif; color: #4F5155;}

            a {color: #003399;background-color: transparent;font-weight: normal;}

            h1 {color: #0e86cb;background-color: transparent;border-bottom: 1px solid #D0D0D0;font-size: 19px;font-weight: normal;margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;}

            code {font-family: Consolas, Monaco, Courier New, Courier, monospace;font-size: 12px;background-color: #f9f9f9;border: 1px solid #D0D0D0;
                  color: #002166;display: block;margin: 14px 0 14px 0;padding: 12px 10px 12px 10px;}

            #content{margin: 0 15px 0 15px;}

            #main_box{width: 950px; margin: 50px;border: 1px solid #D0D0D0;-webkit-box-shadow: 0 0 8px #D0D0D0; -webkit-box-align:center; -moz-box-align:center;}

            hr { border-top: 1px solid #D0D0D0; border-bottom: none; border-left: none; border-right: none; }

            #steps { font-size: 11px; }

            input[type="submit"] { height: 32px; border:none; background-color: #f9f9f9;
                                   padding-bottom: 5px; padding-left: 18px;  padding-right: 18px; cursor:pointer; -moz-border-radius: 1px; border-radius: 1px;        
                                   padding-top: 2px; top: -4px; border: 1px solid #ccc;
                                   font-family: 'Calibri', 'newCalibri', 'Arial'; color:#0e86cb;  }
            input[type="submit"]:hover { background-color:#fff; }
        </style>
    </head>
    <body>
    <center>
    <div id="main_box">
        <h1>Installation &raquo; Step <?php echo $step; ?> ( <?php echo $steps[$step]; ?> )</h1>

        <div id="content">
            <?php include "./steps/". $step .".php"; ?>

            <div id="info"></div>
        </div>
    </div>
    </center>
</body>
</html>
<script type="text/javascript" src="scripts.js"></script>