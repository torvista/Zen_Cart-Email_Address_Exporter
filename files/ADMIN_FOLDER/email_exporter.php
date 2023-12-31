<?php

declare(strict_types=1);
/**
 * Plugin Email Address Exporter
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link https://www.zen-cart.com/downloads.php?do=file&id=6
 * @link https://github.com/torvista/Zen_Cart-Email_Address_Exporter
 * @version $Id: 2023 Dec 12 torvista $
 */

require 'includes/application_top.php';

// change destination here for path when using "save to file on server"
if (!defined('DIR_FS_EMAIL_EXPORTER')) define('DIR_FS_EMAIL_EXPORTER', DIR_FS_CATALOG . 'images/uploads/');

$query_name = '';

$action = ($_GET['action'] ?? '');

$NL="
"; // NOTE: The line break above is INTENTIONAL!

$available_export_formats[0] = ['id' => '0', 'text' => 'CSV'];
$available_export_formats[1] = ['id' => '1', 'text' => 'TXT'];
$available_export_formats[2] = ['id' => '2', 'text' => 'HTML'];
$available_export_formats[3] = ['id' => '3', 'text' => 'XML'];
$save_to_file_checked = (isset($_POST['savetofile']) && zen_not_null($_POST['savetofile']) ? $_POST['savetofile'] : 1);
$post_format = (isset($_POST['format']) && zen_not_null($_POST['format']) ? $_POST['format'] : 1 );
$format = $available_export_formats[$post_format]['text'];
$file = ($_POST['filename'] ?? 'email_addresses.csv');
if (!preg_match('/.*\.(csv|txt|html?|xml)$/', $file)) $file .= '.txt';

if ($action != '') {
    switch ($action) {
        case 'save':
            global $db;

            if ($format === 'CSV') {
                $FIELDSTART = '"';
                $FIELDEND = '"';
                $FIELDSEPARATOR = ',';
                $LINESTART = '';
                $LINEBREAK = "\n";
            }
            if ($format === 'TXT') {
                $FIELDSTART = '';
                $FIELDEND = '';
                $FIELDSEPARATOR = "\t";
                $LINESTART = '';
                $LINEBREAK = "\n";
            }
            if ($format === 'HTML') {
                $FIELDSTART = '<td>';
                $FIELDEND = '</td>';
                $FIELDSEPARATOR = '';
                $LINESTART = '<tr>';
                $LINEBREAK = '</tr>' . $NL;
            }

            if (isset($_POST['audience_selected'])) {
                $query_name = $_POST['audience_selected'];
                if (is_array($_POST['audience_selected'])) $query_name = $_POST['audience_selected']['text'];
            }
            if ($query_name === '') {
                $messageStack->add('Please select an option', 'error');
                break;
            }
            /**
             * CUSTOMIZATION STEP 1:
             * 1. You must edit (or add to) the queries in the query_builder table if you want to add more fields to the extracted data.
             *    Look up your query_name (since this matches the pulldown in your admin), and update the query_string with the correct updated SQL query.
             *    Once the query in query_builder has been updated and tested, the following section of code will automatically
             *    bring in the right data for use in later steps.
             */
            $audience_select = get_audience_sql_query($query_name, 'email_exporter');
            if (empty($audience_select['query_string'])) {
                $messageStack->add_session('No such query.', 'error');
                zen_redirect(zen_href_link(FILENAME_EMAIL_EXPORTER));
            }
            $query_string = $audience_select['query_string'];
            $audience = $db->Execute($query_string);
            $records = $audience->RecordCount();
            if ($records === 0) {
                $messageStack->add_session('No Records Found.', 'error');
            } else { //process records
                $i = 0;

                // make a <table> tag if HTML output
                if ($format === 'HTML') {
                    $exporter_output = '<table border="1">' . $NL;
                } else {
                    $exporter_output = '';
                }

                /**
                 * CUSTOMIZATION STEP 2:
                 * 2. You must add your field name to this list.
                 *    Notice how head heading here involves two lines: FIELDSTART, then the heading, then FIELDEND, followed by line for the FIELDSEPARATOR if it's not the last field being output.
                 *    Be sure to follow the same pattern.
                 *    Best to only use letters/numbers and underscores. No other punctuation.
                 */

                // add column headers if CSV or HTML format
                if ($format === 'CSV' || $format === 'HTML') {
                    $exporter_output .= $LINESTART;
                    $exporter_output .= $FIELDSTART . 'customers_email_address' . $FIELDEND;
                    $exporter_output .= $FIELDSEPARATOR;
                    $exporter_output .= $FIELDSTART . 'customers_firstname' . $FIELDEND;
                    $exporter_output .= $FIELDSEPARATOR;
                    $exporter_output .= $FIELDSTART . 'customers_lastname' . $FIELDEND;
                    $exporter_output .= $FIELDSEPARATOR;
                    $exporter_output .= $FIELDSTART . 'company_name' . $FIELDEND;
                    $exporter_output .= $FIELDSEPARATOR;
                    $exporter_output .= $FIELDSTART . 'customers_telephone' . $FIELDEND;
                    $exporter_output .= $LINEBREAK;
                }
                // headers - XML
                if ($format === 'XML') {
                    $exporter_output .= '<?xml version="1.0" encoding="' . CHARSET . '"?>' . "\n";
                }

                // output real data
                while (!$audience->EOF) {
                    $i++;

                    /**
                     * CUSTOMIZATION STEP 3:
                     * 3. Add the new field to the output.
                     *    The field's data is represented as: $audience->fields['FIELD_NAME_HERE'], as seen in the existing fields below.
                     *    Be sure to add it for both the XML format and the non-XML format, for consistency.  Again, follow the pattern.
                     */
                    $audience->fields['entry_company'] = !empty($audience->fields['entry_company']) ? $audience->fields['entry_company'] : '';
                    $audience->fields['customers_telephone'] = !empty($audience->fields['customers_telephone']) ? $audience->fields['customers_telephone'] : '';
                    if ($format === 'XML') {
                        $exporter_output .= "<address_book>\n";
                        $exporter_output .= "  <contact>\n";
                        $exporter_output .= "    <firstname>" . $audience->fields['customers_firstname'] . "</firstname>\n";
                        $exporter_output .= "    <lastname>" . $audience->fields['customers_lastname'] . "</lastname>\n";
                        $exporter_output .= "    <email_address>" . $audience->fields['customers_email_address'] . "</email_address>\n";
                        $exporter_output .= "    <company>" . $audience->fields['entry_company'] . "</company>\n";
                        $exporter_output .= "    <telephone>" . $audience->fields['customers_telephone'] . "</telephone>\n";
                        $exporter_output .= "  </contact>\n";
                    } else {  // output non-XML data-format
                        $exporter_output .= $LINESTART;
                        $exporter_output .= $FIELDSTART . $audience->fields['customers_email_address'] . $FIELDEND;
                        $exporter_output .= $FIELDSEPARATOR;
                        $exporter_output .= $FIELDSTART . $audience->fields['customers_firstname'] . $FIELDEND;
                        $exporter_output .= $FIELDSEPARATOR;
                        $exporter_output .= $FIELDSTART . $audience->fields['customers_lastname'] . $FIELDEND;
                        $exporter_output .= $FIELDSEPARATOR;
                        $exporter_output .= $FIELDSTART . $audience->fields['entry_company'] . $FIELDEND;
                        $exporter_output .= $FIELDSEPARATOR;
                        $exporter_output .= $FIELDSTART . $audience->fields['customers_telephone'] . $FIELDEND;
                        $exporter_output .= $LINEBREAK;
                    }

                    $audience->MoveNext();
                }

                if ($format === 'HTML') {
                    $exporter_output .= $NL . '</table>';
                }
                if ($format === 'XML') {
                    $exporter_output .= "</address_book>\n";
                }

                // theoretically, $i should === $records at this point.

                // status message
                $messageStack->add($records . ' Processed.', 'success');

                // begin streaming file contents
                if ($save_to_file_checked != 1) { // not saving to a file, so do regular output
                    if ($format === 'CSV' || $format === 'TXT' || $format === 'XML') {
                        if ($format === 'CSV' || $format === 'TXT') {
                            $content_type = 'text/x-csv';
                        } else { //XML
                            $content_type = 'text/xml; charset=' . CHARSET;
                        }
                        if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
                            header('Content-Type: application/octetstream');
//            header('Content-Type: '.$content_type);
//              header('Content-Disposition: inline; filename="' . $file . '"');
                            header('Content-Disposition: attachment; filename=' . $file);
                            header('Expires: Mon, 26 Jul 2001 05:00:00 GMT');
                            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                            header('Cache-Control: must_revalidate, post-check=0, pre-check=0');
                            header('Pragma: public');
                            header('Cache-control: private');
                        } else {
                            header('Content-Type: application/x-octet-stream');
//            header('Content-Type: '.$content_type);
                            header('Content-Disposition: attachment; filename=' . $file);
                            header('Expires: Mon, 26 Jul 2001 05:00:00 GMT');
                            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                            header('Pragma: no-cache');
                        }
                        echo $exporter_output;
                        exit;
                    } else {
                        echo $exporter_output;
                        exit;
                    }
                } else { //write to file
                    //open output file for writing
                    $f = fopen(DIR_FS_EMAIL_EXPORTER . $file, 'w');
                    fwrite($f,$exporter_output);
                    fclose($f);
                    unset($f);
                } // endif $save_to_file
            } //end if $records for processing not 0

            zen_redirect(zen_href_link(FILENAME_EMAIL_EXPORTER));
            break;
    }  //end switch / case

}  //endif $action
?>
<!doctype html>
<html <?php
echo HTML_PARAMS; ?>>
<head>
    <?php
    require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
</head>
<body>
<!-- header //-->
<?php
require DIR_WS_INCLUDES . 'header.php'; ?>
<!-- header_eof //-->

<!-- body //-->
<p class="pageHeading"><?php echo HEADING_TITLE; ?>
    <!-- body_text //-->
    <?php
    echo zen_draw_form('export', FILENAME_EMAIL_EXPORTER, 'action=save', 'post');//, 'onsubmit="return check_form(export);"'); ?>
<div>
    <p><?php
        echo TEXT_EMAIL_EXPORTER_FORMAT; ?><br>
        <?php
        echo zen_draw_pull_down_menu('format', $available_export_formats, $format); ?></p>

    <p><?php
        echo TEXT_PLEASE_SELECT_AUDIENCE; ?><br>
        <?php
        echo zen_draw_pull_down_menu('audience_selected', get_audiences_list('email_exporter'), $query_name) ?></p>

    <p><?php
        echo TEXT_EMAIL_EXPORTER_FILENAME; ?><br>
        <?php
        echo zen_draw_input_field('filename', htmlspecialchars($file, ENT_COMPAT, CHARSET), ' size="60"'); ?></p>

    <p><label><?php echo zen_draw_checkbox_field('savetofile', '1', $save_to_file_checked); ?> <?php echo TEXT_EMAIL_EXPORTER_SAVETOFILE; ?></label><br>
        <?php echo TEXT_EMAIL_EXPORTER_DEST . ' ' . DIR_FS_EMAIL_EXPORTER; ?></p>

    <p><button type="submit" class="btn btn-primary"><?php
            echo IMAGE_SAVE; ?></button>
    </p>
</div>
<?php
echo '</form>'; ?>
<?php echo TEXT_INSTRUCTIONS; ?>
<p>Improve this and report issues <a target="_blank" href="https://github.com/torvista/Zen_Cart-Email_Address_Exporter/issues">here</a>.</p>
<!-- body_text_eof //-->

<!-- body_eof //-->

<!-- footer //-->
<?php
require DIR_WS_INCLUDES . 'footer.php'; ?>
<!-- footer_eof //-->
</body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
