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

define('HEADING_TITLE', 'Email Address Exporter');
define('TEXT_EMAIL_EXPORT_FORMAT', 'Export File Format:');
define('TEXT_PLEASE_SELECT_AUDIENCE', 'Select a audience list:');
define('TEXT_EMAIL_EXPORT_FILENAME', 'Export Filename:');
define('TEXT_EMAIL_EXPORT_SAVETOFILE','Save to server? (otherwise download to another location)');
define('TEXT_EMAIL_EXPORT_DEST','File location: ');
define('ERROR_PLEASE_SELECT_AUDIENCE','Error: Please select an audience list to export');
define('TEXT_INSTRUCTIONS','<h3>Instructions</h3>
<p>You can use this tool to export your newsletter subscribers (and others) list to a CSV or TXT file for subsequent import into an third-party emailing tool.</p>
<ol>
<li>Choose your export format.</li>
<li>Choose a recipient list.</li>
<li>Enter a filename. Use a file extension that corresponds to the chosen filetype (must end as .csv / .txt / .htm / .xml).</li>
<li>Click Save to proceed.</li>
<li>Check the box to save to the /logs folder, or just click Save to download to another location.</li>
</ol>');
