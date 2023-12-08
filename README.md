# Zen Cart - Email Address Exporter

## Current GitHub Status
Tested with Zen Cart 158 and php 8.3.0.  
https://github.com/torvista/Zen_Cart-Email_Address_Exporter

## Notes
You can add custom queries to the dropdown easily: add the query to the query_builder table and set the query_category to "email_exporter".


## (mostly) Original Readme

Add-On: Email Address Exporter Admin Tool
Designed for: Zen Cart v1.5.5+ 

Created by: DrByte
Some revisions added by: Swifty8078, Ooba_Scott, That Software Guy 

Donations:  Please support ZenCart!  paypal@zen-cart.com  - Thank you!
===========================================================

NOTES:
This add-on was created to allow quick and easy exporting of email addresses from your Zen Cart shop.

This is especially useful if you are needing to manage mailing lists and/or send newsletters or mass-emailings from your personal computer.
One great tool to use this with is GroupMail, which you can get a free copy of here: http://www.shareasale.com/r.cfm?B=3937&U=151719&M=1465

As to export formats, you can choose from CSV, TXT (tab-delimited), XML, or HTML formats.

You can select any of the audiences available in your cart, while the query_category in query_builder table has "email_exporter".

If you choose the "Save To File On Server" option, you can set the "Destination Folder" by editing the /admin/includes/extra_datafiles/email_exporter.php and set the DIR_FS_EMAIL_EXPORTER to the desired destination.
The default setting (for simplicity) is the /logs/ folder.

Whatever folder you point this to must be writable so that the webserver can write to files in this folder.
When saving to file, if a file of the same name already exists, it will be overwritten. No backups.

===========================================================

INSTALLATION:  
After unzipping this file you have an "ADMIN_FOLDER" folder on your PC, with several folders/files in it. On your server you also have an "admin" folder renamed to something else for security. Simply upload the files from your PC's "ADMIN_FOLDER" to your server's (renamed) "admin" folder, putting files into the same folders on the server as you find them on your PC.

In your Admin permissions panel, grant access to appropriate users using the Admin Access Management screen.

===========================================================

USE:
To use, just log into the admin area, click on "Tools", and click on "Email Address Exporter".

===========================================================

## Problems
Report here:  
https://github.com/torvista/Zen_Cart-Email_Address_Exporter/issues

## Changelog
08/12/2023: bug fixes, fettling and creation of email_exporter query_category to be able to add more custom queries
17/11/2023: torvista (initial Github commit of version: 157 30/11/2022 from Plugins)   https://www.zen-cart.com/downloads.php?do=file&id=6  
Use ZC158-style admin header, code cleanup from IDE inspections.  
Aug 2017 - included telephone and company fields in export (requires them to be in the db query that feeds the exporter though). Also forced limits on filenames used for exporting.  
Dec 2014 - tidying  
Dec 2013 - DrByte removed debug code left in previous update.  
Nov 2013 - DrByte added a fix to prevent a database error from occurring when you forget to choose an export option  
Sept 2012 - DrByte updated for Zen Cart v1.5.1, and to add ability to easily add more fields to the output, along with THREE-STEPS INSTRUCTIONS in the code on how to do it.  
July 2012 - updated for Zen Cart v1.5.0  
May 11/05   - Added Save To File format, and tweaked streaming methods slightly  
April 15/05 - Added XML format  
March 10/05 - Initial Release  
