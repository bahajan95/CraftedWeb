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

    $GameServer->selectDB("webdb");

    # Organized Alphabeticaly

    switch ($_POST['action']) 
    {
        case "dshop":
        {
            $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE account=". mysqli_real_escape_string($conn, $_POST['id']) ." AND shop='donate';");
            if (mysqli_num_rows($result) == 0)
            {
                echo "<b color='red'>No logs was found for this account.</b>";
            }
            else
            {
                ?> <table width="100%">
                    <tr>
                        <th>Item</th>
                        <th>Character</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
            <?php while ($row = mysqli_fetch_assoc($result))
                    { ?>
                    <tr>
                        <td>
                            <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                <?php echo $GameServer->getItemName($row['entry']); ?>
                            </a>
                        </td>
                        <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                        <td><?php echo $row['date']; ?></td>   
                        <td>x<?php echo $row['amount']; ?></td>
                    </tr>
                    <?php
                    }
                    echo '</table>';
            }

            break;
        }
        
        case "payments":
        {
            $result = mysqli_query($conn, "SELECT paymentstatus, mc_gross, datecreation FROM payments_log WHERE userid=". mysqli_real_escape_string($conn, $_POST['id']) .";");
            if (mysqli_num_rows($result) == 0)
            {
                echo "<b color='red'>No payments was found for this account.</b>";
            }
            else
            { ?> 
                <table width="100%">
                    <tr>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result))
                    { ?>
                    <tr>
                        <td><?php echo $row['mc_gross']; ?>$</td>
                        <td><?php echo $row['paymentstatus']; ?></td>
                        <td><?php echo $row['datecreation']; ?></td>   
                    </tr>
                    <?php }
                echo "</table>";
            }

            break;
        }

        case "search":
        {
            $input      = mysqli_real_escape_string($conn, $_POST['input']);
            $shop       = mysqli_real_escape_string($conn, $_POST['shop']); ?>
            <table width="100%">
                <tr>
                    <th>User</th>
                    <th>Character</th>
                    <th>Realm</th>
                    <th>Item</th>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>

                <?php
                //Search via character name...
                $loopRealms = mysqli_query($conn, "SELECT id FROM realms;");
                while ($row = mysqli_fetch_assoc($loopRealms))
                {
                    $GameServer->connectToRealmDB($row['id']);
                    $result = mysqli_query($conn, "SELECT guid FROM characters WHERE name LIKE '%". $input ."%';");
                    if (mysqli_num_rows($result) > 0)
                    {
                        $row    = mysqli_fetch_assoc($result);
                        $GameServer->selectDB('webdb');
                        $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE shop='". $shop ."' AND char_id=". $row['guid'] .";");

                        while ($row = mysqli_fetch_assoc($result))
                        { ?>
                            <tr class="center">
                                <td><?php echo $GameAccount->getAccName($row['account']); ?></td>
                                <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                                <td><?php echo $GameServer->getRealmName($row['realm_id']); ?></td>
                                <td>
                                    <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                        <?php echo $GameServer->getItemName($row['entry']); ?>
                                    </a>
                                </td>
                                <td><?php echo $row['date']; ?></td>
                                <td>x<?php echo $row['amount']; ?></td>
                            </tr><?php
                        }
                    }
                }
                //Search via account name
                $GameServer->selectDB("logondb");
                $result = mysqli_query($conn, "SELECT id FROM account WHERE username LIKE '%". $input ."%';");
                if (mysqli_num_rows($result) > 0)
                {
                    $row    = mysqli_fetch_assoc($result);
                    $GameServer->selectDB("webdb");
                    $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE shop='". $shop ."' AND account=". $row['id'] .";");

                    while ($row = mysqli_fetch_assoc($result))
                    { ?>
                        <tr class="center">
                            <td><?php echo $GameAccount->getAccName($row['account']); ?></td>
                            <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                            <td><?php echo $GameServer->getRealmName($row['realm_id']); ?></td>
                            <td>
                                <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                    <?php echo $GameServer->getItemName($row['entry']); ?>
                                </a>
                            </td>
                            <td><?php echo $row['date']; ?></td>
                            <td>x<?php echo $row['amount']; ?></td>
                        </tr><?php
                    }
                }

                //Search via item name
                $GameServer->selectDB('worlddb');
                $result = mysqli_query($conn, "SELECT entry FROM item_template WHERE name LIKE '%". $input ."%';");
                if (mysqli_num_rows($result) > 0)
                {
                    $row    = mysqli_fetch_assoc($result);
                    $GameServer->selectDB('webdb');
                    $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE shop='". $shop ."' AND entry=". $row['entry'] .";");

                    while ($row = mysqli_fetch_assoc($result))
                    { ?>
                        <tr class="center">
                            <td><?php echo $GameAccount->getAccName($row['account']); ?></td>
                            <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                            <td><?php echo $GameServer->getRealmName($row['realm_id']); ?></td>
                            <td>
                                <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                    <?php echo $GameServer->getItemName($row['entry']); ?>
                                </a>
                            </td>
                            <td><?php echo $row['date']; ?></td>
                            <td>x<?php echo $row['amount']; ?></td>
                        </tr><?php
                    }
                }

                //Search via date
                $GameServer->selectDB('webdb');
                $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE shop='". $shop ."' AND date LIKE '%". $input ."%';");

                while ($row = mysqli_fetch_assoc($result))
                { ?>
                    <tr class="center">
                        <td><?php echo $GameAccount->getAccName($row['account']); ?></td>
                        <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                        <td><?php echo $GameServer->getRealmName($row['realm_id']); ?></td>
                        <td>
                            <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                <?php echo $GameServer->getItemName($row['entry']); ?>
                            </a>
                        </td>
                        <td><?php echo $row['date']; ?></td>
                        <td>x<?php echo $row['amount']; ?></td>
                    </tr><?php
                }

                if ($input == "Search...")
                {
                    //View last 10 logs
                    $GameServer->selectDB('webdb');
                    $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE shop='". $shop ."' ORDER BY id DESC LIMIT 10;");

                    while ($row = mysqli_fetch_assoc($result))
                    { ?>
                        <tr class="center">
                            <td><?php echo $GameAccount->getAccName($row['account']); ?></td>
                            <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                            <td><?php echo $GameServer->getRealmName($row['realm_id']); ?></td>
                            <td>
                                <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                    <?php echo $GameServer->getItemName($row['entry']); ?>
                                </a>
                            </td>
                            <td><?php echo $row['date']; ?></td>
                            <td>x<?php echo $row['amount']; ?></td>
                        </tr><?php 
                    }
                } ?>

            </table><?php

            break;
        }

        case "vshop":
        {
            $result = mysqli_query($conn, "SELECT * FROM shoplog WHERE account=". mysqli_real_escape_string($conn, $_POST['id']) ." AND shop='vote';");
            if (mysqli_num_rows($result) == 0)
            {
                echo "<b color='red'>No logs was found for this account.</b>";
            }
            else
            { ?>
                <table width="100%">
                    <tr>
                        <th>Item</th>
                        <th>Character</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr><?php 
                    while ($row = mysqli_fetch_assoc($result))
                    { ?>
                        <tr>
                            <td>
                                <a href="http://<?php echo $GLOBALS['tooltip_href']; ?>item=<?php echo $row['entry']; ?>" title="" target="_blank">
                                    <?php echo $GameServer->getItemName($row['entry']); ?>
                                </a>
                            </td>
                            <td><?php echo $GameAccount->getCharName($row['char_id'], $row['realm_id']); ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td>x<?php echo $row['amount']; ?></td>
                        </tr><?php
                    }
                echo "</table>";
            }

            break;
        }
    }