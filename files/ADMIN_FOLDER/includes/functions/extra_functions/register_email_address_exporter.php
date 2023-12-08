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

//Register module into admin menu system
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

if (!zen_page_key_exists('emailExport')) {
    zen_register_admin_page('emailExport', 'BOX_TOOLS_EMAIL_EXPORT', 'FILENAME_EMAIL_EXPORT', '', 'tools', 'Y');
}

// other tweaking to add slightly more flexibility when exporting email addresses
/* FOR REFERENCE
$result = $db->Execute("SELECT query_string from " . TABLE_QUERY_BUILDER . " where query_name = 'All Customers'");
if ($result->fields['query_string'] == 'select customers_email_address, customers_firstname, customers_lastname from TABLE_CUSTOMERS order by customers_lastname, customers_firstname, customers_email_address') {
    $db->Execute("UPDATE " . TABLE_QUERY_BUILDER . " set query_string='select customers_firstname, customers_lastname, customers_email_address, c.*, a.* from TABLE_CUSTOMERS c, TABLE_ADDRESS_BOOK a WHERE c.customers_id = a.customers_id AND c.customers_default_address_id = a.address_book_id ORDER BY customers_lastname, customers_firstname, customers_email_address' where query_name = 'All Customers'");
}
$result = $db->Execute("SELECT query_string from " . TABLE_QUERY_BUILDER . " where query_name = 'All Newsletter Subscribers'");
if ($result->fields['query_string'] == "select customers_firstname, customers_lastname, customers_email_address from TABLE_CUSTOMERS where customers_newsletter = '1'") {
    $db->Execute("UPDATE " . TABLE_QUERY_BUILDER . " set query_string='select customers_firstname, customers_lastname, customers_email_address, c.*, a.* from TABLE_CUSTOMERS c, TABLE_ADDRESS_BOOK a WHERE c.customers_id = a.customers_id AND c.customers_default_address_id = a.address_book_id AND customers_newsletter = 1' where query_name = 'All Newsletter Subscribers'");
}*/

//add email_exporter to query_category
$db->Execute(
    'UPDATE ' . TABLE_QUERY_BUILDER . ' SET query_category = "email,email_exporter" WHERE query_category = "email,"'
);
$db->Execute(
    'UPDATE ' . TABLE_QUERY_BUILDER . ' SET query_category = "email,newsletters,email_exporter" WHERE query_category = "email,newsletters"'
);

//modify "All Customers"
$result = $db->Execute('SELECT query_string FROM ' . TABLE_QUERY_BUILDER . ' WHERE query_name = "All Customers"');
if ($result->fields['query_string'] ==
    'select customers_email_address, customers_firstname, customers_lastname from TABLE_CUSTOMERS order by customers_lastname, customers_firstname, customers_email_address'
) {
    $db->Execute(
        'UPDATE ' . TABLE_QUERY_BUILDER . ' SET query_string="SELECT c.*, a.* FROM TABLE_CUSTOMERS c, TABLE_ADDRESS_BOOK a WHERE c.customers_id = a.customers_id AND c.customers_default_address_id = a.address_book_id ORDER BY customers_lastname, customers_firstname, customers_email_address" WHERE query_name = "All Customers"'
    );
}
//modify "All Newsletter Subscribers"
$result = $db->Execute('SELECT query_string from ' . TABLE_QUERY_BUILDER . ' WHERE query_name = "All Newsletter Subscribers"');
if ($result->fields['query_string'] == "select customers_firstname, customers_lastname, customers_email_address from TABLE_CUSTOMERS where customers_newsletter = '1'"
) {

    $db->Execute(
        'UPDATE ' . TABLE_QUERY_BUILDER . ' SET query_string = 
        "SELECT c.*, a.* FROM TABLE_CUSTOMERS c, TABLE_ADDRESS_BOOK a WHERE c.customers_id = a.customers_id AND c.customers_default_address_id = a.address_book_id AND c.customers_newsletter = \'1\'"
         WHERE query_name = "All Newsletter Subscribers"'
    );
}
//the other queries should also be modified to include telephone and company....