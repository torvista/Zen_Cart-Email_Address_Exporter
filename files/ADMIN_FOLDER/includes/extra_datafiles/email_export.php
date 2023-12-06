<?php

declare(strict_types=1);
/**
 * Plugin Email Address Exporter
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link https://www.zen-cart.com/downloads.php?do=file&id=6
 * @link https://github.com/torvista/Zen_Cart-Email_Address_Exporter
 * @version $Id: 2023 Dec 06 torvista $
 */

define('FILENAME_EMAIL_EXPORT', 'email_export');
define('BOX_TOOLS_EMAIL_EXPORT', 'Email Address Exporter');

// change destination here for path when using "save to file on server"
if (defined('DIR_FS_LOGS')) {
    define('DIR_FS_EMAIL_EXPORT',DIR_FS_LOGS . '/');
} else {
    define('DIR_FS_EMAIL_EXPORT',DIR_FS_CATALOG . 'images/uploads/');
}
