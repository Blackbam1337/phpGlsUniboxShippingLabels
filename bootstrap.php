<?php

/* Load Composer Libraries */
require_once __DIR__ . '/library/vendor/autoload.php';



/**
 * Load Zend
 */
// define an absolute path to library directory
// you don't have to set a constant but it is just good practice
// you can also use a variable or add the path directly below
define('APPLICATION_LIBRARY',__DIR__.'/library');
// Note again: the path is the parent of your Zend folder, not the Zend folder itself.
// now set the include path
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_LIBRARY, get_include_path(),
)));

// Note: you don't have to use this if statement
// but this would tell you why it is not working
if ( !file_exists(APPLICATION_LIBRARY . '/Zend') ) {
    exit('The Zend library folder is missing!');
}

require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);


/* Load Classes */

require('model/Config.class.php');
require('model/Creator.class.php');
require('model/Error.class.php');


// folder label
require('model/Label/Gls_Unibox_Model_Label_Abstract.php');
require('model/Label/Gls/Gls_Unibox_Model_Label_Gls_Business.php');
require('model/Label/Gls/Gls_Unibox_Model_Label_Gls_Express.php');

require('model/Label/Item/Gls_Unibox_Model_Label_Item_Barcode.php');
require('model/Label/Item/Gls_Unibox_Model_Label_Item_Datamatrix.php');
require('model/Label/Item/Gls_Unibox_Model_Label_Item_Font.php');

require('model/Magento/Varien_Object.php');
require('model/Magento/Varien_Data_Collection.php');
require('model/Magento/Mage_Core_Model_Abstract.php');

require('model/Pdf/Gls_Unibox_Model_Pdf_Abstract.php');
require('model/Pdf/Gls_Unibox_Model_Pdf_Label.php');

require('model/Unibox/Gls_Unibox_Model_Unibox_LeapYear.php');
require('model/Unibox/Gls_Unibox_Model_Unibox_Parser.php');

require('model/Util/helpers.php');

require('model/Gls_Unibox_Model_Client.php');
require('model/Gls_Unibox_Model_Shipping.php');