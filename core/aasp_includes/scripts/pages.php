<?php

    /* ___           __ _           _ __    __     _     
      / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__
      / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \
      / /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
      \____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/

      -[ Created by �Nomsoft
      `-[ Original core by Anthony (Aka. CraftedDev)

      -CraftedWeb Generation II-
      __                           __ _
      /\ \ \___  _ __ ___  ___  ___  / _| |_
      /  \/ / _ \| '_ ` _ \/ __|/ _ \| |_| __|
      / /\  / (_) | | | | | \__ \ (_) |  _| |_
      \_\ \/ \___/|_| |_| |_|___/\___/|_|  \__|	- www.Nomsoftware.com -
      The policy of Nomsoftware states: Releasing our software
      or any other files are protected. You cannot re-release
      anywhere unless you were given permission.
      � Nomsoftware 'Nomsoft' 2011-2012. All rights reserved. */

    define('INIT_SITE', TRUE);
    include('../../includes/misc/headers.php');
    include('../../includes/configuration.php');
    include('../functions.php');
    global $GameServer, $GameAccount;
    $conn = $GameServer->connect();

    $GameServer->selectDB("webdb", $conn);

    # Organized Alphabeticaly

    switch ($_POST['action'])
    {
        case "addVoteLink":
        {
            $title  = mysqli_real_escape_string($conn, $_POST['title']);
            $points = mysqli_real_escape_string($conn, $_POST['points']);
            $image  = mysqli_real_escape_string($conn, $_POST['image']);
            $url    = mysqli_real_escape_string($conn, $_POST['url']);

            if (!empty($title) && !empty($points) && !empty($image) && !empty($url))
            {
                mysqli_query($conn, "INSERT INTO votingsites (title, points, image, url) VALUES('". $title ."', '". $points ."', '". $image ."', '". $url ."');");
            }

            break;
        }

        case "delete":
        {
            mysqli_query($conn, "DELETE FROM custom_pages WHERE filename='". mysqli_real_escape_string($conn, $_POST['filename']) ."';");
            return;

            break;
        }

        case "removeVoteLink":
        {
            $id = mysqli_real_escape_string($conn, $_POST['id']);

            mysqli_query($conn, "DELETE FROM votingsites WHERE id=". $id .";");

            break;
        }

        case "saveServicePrice":
        {
            $service  = mysqli_real_escape_string($conn, $_POST['service']);
            $price    = mysqli_real_escape_string($conn, $_POST['price']);
            $currency = mysqli_real_escape_string($conn, $_POST['currency']);
            $enabled  = mysqli_real_escape_string($conn, $_POST['enabled']);

            mysqli_query($conn, "UPDATE service_prices SET price=". $price .", currency='". $currency ."', enabled='". $enabled ."' WHERE service='". $service ."';");

            break;
        }

        case "saveVoteLink":
        {
            $id     = mysqli_real_escape_string($conn, $_POST['id']);
            $title  = mysqli_real_escape_string($conn, $_POST['title']);
            $points = mysqli_real_escape_string($conn, $_POST['points']);
            $image  = mysqli_real_escape_string($conn, $_POST['image']);
            $url    = mysqli_real_escape_string($conn, $_POST['url']);

            if (!empty($id))
            {
                mysqli_query($conn, "UPDATE votingsites SET title='". $title ."', points='". $points ."', image='". $image ."', url='". $url ."' 
                    WHERE id=". $id .";");
            }

            break;
        }

        case "toggle":
        {
            if ($_POST['value'] == 1)
            {
                //Enable
                mysqli_query($conn, "DELETE FROM disabled_pages WHERE filename='". mysqli_real_escape_string($conn, $_POST['filename']) ."';");
            }
            elseif ($_POST['value'] == 2)
            {
                //Disable
                mysqli_query($conn, "INSERT INTO disabled_pages values('". mysqli_real_escape_string($conn, $_POST['filename']) ."');");
            }

            break;
        }

    }