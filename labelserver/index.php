<?php
/**
 * Created by IntelliJ IDEA.
 * User: david
 * Date: 10/2/15
 * Time: 3:51 PM
 */

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


/**
 * Load Config
 */
require('Config.php');


// Paket Label ID
$labelId = "lorem121212";

// Paket Nummer
$paketnummer = "11244232223";

// das Tag
$example_tag = '\\\\\\\\\\GLS\\\\\\\\\\T090:NOPRINT|T050:Versandsoftware|T051:V 1.0|T8700:DE 640|T400:641202003285|T545:26.01.2009|T805:1010|T809:company|T810:Walter Schmidt|T820:Neumarktstr. 35|T821:D|T822:36286|T823:Neuenstein|T851:Cust.no.:|T852:236|T853:Id.no.:|T854:987654321|T859:company|T860:PC Soft Inc.|T863:ul. Fajansowa 1|T864:Warszawa|T8904:001|T8905:001| T8914:27600ABCDE|T8914:2761234567|T080:4.67|T520:23012009|T510:ba|T500:DE 640|T560:DE03|T8797:IBOXCUS|T540:26.01.2009|T541:14:20|T100:PL|CTRNUM:616|CTRA2:PL|T330:01-224|T202:|T210:|ARTNO:Standard|T530:3.00|ALTZIP:01-224|FLOCCODE:PL5000|OWNER:19|TOURNO:7841|T320:7841|TOURTYPE:21102|SORT1:0|T310:0|T331:01-224|T890:9060|ROUTENO:1137705|ROUTE1:A|T110:A|FLOCNO:1071|T101:5000|T105:PL|T300:61650007|NDI:|T8970:A|T8971:A|T8975:1010|T207:|T206:10000|T8980:CC|T8974:|T8916:641202003285|T8950:Tour|T8951:ZipCode|T8952:YourGLS Track ID|T8953:Product|T8954:Service Code|T8955:Delivery Address|T8956:Contact ID|T8958:Contact|T8957:Customer ID:|T8959:Phone|T8960:Note|T8961:Parcel|T8962:Weight|T8963:Notification on damage which is not recognisable from outside had to be submitted toGLS|T8964:on the same Day of Delivery in writing. This Transport is based on GLS terms andconditions|T8913:ZJIM3DE0|T8972:ZJIM3DE0|T8902:ADE640PL500000000000002760000000ZJIM3DE0CC0A 784101-224 00300000000000001010|T8903:A¬PC Soft Inc.¬ul. Fajansowa 1¬Warszawa¬¬¬987654321¬|PRINTINFO:|PRINT1:|RESULT:E000:641202003285|T565:142018|PRINT0:xxGLSintermecpf4i.int01|/////GLS/////';



// folder label
require('Model/Label/Gls_Unibox_Model_Label_Abstract.php');
require('Model/Label/Gls/Gls_Unibox_Model_Label_Gls_Business.php');
require('Model/Label/Gls/Gls_Unibox_Model_Label_Gls_Express.php');

require('Model/Label/Item/Gls_Unibox_Model_Label_Item_Barcode.php');
require('Model/Label/Item/Gls_Unibox_Model_Label_Item_Datamatrix.php');
require('Model/Label/Item/Gls_Unibox_Model_Label_Item_Font.php');

require('Model/Magento/Varien_Object.php');
require('Model/Magento/Varien_Data_Collection.php');
require('Model/Magento/Mage_Core_Model_Abstract.php');

require('Model/Pdf/Gls_Unibox_Model_Pdf_Abstract.php');
require('Model/Pdf/Gls_Unibox_Model_Pdf_Label.php');

require('Model/Unibox/Gls_Unibox_Model_Unibox_LeapYear.php');
require('Model/Unibox/Gls_Unibox_Model_Unibox_Parser.php');

require('Model/Gls_Unibox_Model_Client.php');
require('Model/Gls_Unibox_Model_Shipping.php');


$glsService = new Gls_Unibox_Model_Label_Gls_Business(); // we using the business service here, express is also possible

$tags = parseIncomingTag($example_tag); // returns parsed GLS Tags as array

$ready_tags = array();

if($glsService != null) {
    //check if wrong $service is submitted
    /*	No Error => Save Info in Database.
    $glsCustomerId = $shipfrom->getCustomerid();
    $glsContactId = $shipfrom->getContactid();
    $glsDepotCode = $shipfrom->getDepotcode();
    $glsKundennummer = $shipfrom->getKundennummer();
    $glsDepotnummer = $shipfrom->getDepotnummer();
    $paketnummer = $this->nextAvailableLabelNumber($service, $shipfrom);

    $glsSave = Mage::getModel('glsbox/shipment');
    $glsSave->setService($service)
        ->setShipmentId($shipment->getId())
        ->setGlsMessage($returnedtag)
        ->setWeight($weight)
        ->setKundennummer($glsKundennummer)
        ->setCustomerid($glsCustomerId)
        ->setContactid($glsContactId)
        ->setDepotcode($glsDepotCode)
        ->setDepotnummer($glsDepotnummer)
        ->setNotes($notiz)
        ->setPaketnummer($paketnummer)
        ->save();
    */
    $glsService->importValues($tags);
    $ready_tags = $glsService->getData();
}


$gls_unibox_label_pdf = new Gls_Unibox_Model_Pdf_Label();

$pdf = $gls_unibox_label_pdf->createLabel($ready_tags);

$content = $pdf->render();

http_response_code(200);
header('Pragma: public',true);
header('Cache-Control: must-revalidate, post-check=0, pre-check=0',true);
header('Content-type: application/pdf',true);
header('Content-Length: '.strlen($content));
header('Content-Disposition: attachment; filename='.Config::$filename_prefix.$paketnummer.'.pdf');
header('Last-Modified: '.date('r'));

echo $content;

die;



function parseIncomingTag($returnedtag) {
    //$returnedtag = '\\\\\\\\\\GLS\\\\\\\\\\T010:|T050:Versandsoftwarename|T051:V 1.5.2|T8700:DE 550|T330:20354|T090:NOPRINT|T400:552502000716|T545:26.01.2009|T8904:001|T8905:001|T800:Absender|T805:12|T810:GLS IT Services GmbH|T811:Project Management|T820:GLS Germany Str. 1-7|T821:DE|T822:36286|T823:Neuenstein / Aua|T851:KD Nr.:|T852:10166|T853:ID No.:|T854:800018406|T859:Company|T860:GLS Germany GmbH & Co.OHG|T861:Depot 20|T863:Pinkertsweg 49|T864:Hamburg|T921:Machine Parts|T8914:27600ABCDE |T8915:2760000000|T080:4.67|T520:21012009|T510:ba|T500:DE 550|T560:DE03|T8797:IBOXCUS|T540:26.01.2009|T541:11:20|T100:DE|CTRNUM:276|CTRA2:DE|T202:|T210:|ARTNO:Standard|T530:16.20|ALTZIP:20354|FLOCCODE:DE 201|OWNER:5|TOURNO:1211|T320:1211|TOURTYPE:21102|SORT1:0|T310:0|T331:20354|T890:2001|ROUTENO:1006634|ROUTE1:R33|T110:R33|FLOCNO:629|T101: 201|T105:DE|T300:27620105|NDI:|T8970:A|T8971:A|T8975:12|T207:|T206:10001|T8980:AA|T8974:|T8916:552502000716|T8950:Tour|T8951:ZipCode|T8952:Your GLS Track ID|T8953:Product|T8954:Service Code|T8955:Delivery Address|T8956:Contact|T8958:Contact|T8957:Customer ID|T8959:Phone|T8960:Note|T8961:Parcel|T8962:Weight|T8963:Notification on damage which is not recognisable from outside had to be submitted to GLS|T8964:on the same Day of Delivery in writing. This Transport is based on GLS terms and conditions|T8913:ZFFX4HDZ|T8972:ZFFX4HDZ|T8902:ADE 550DE 20100000000002760000000ZFFX4HDZAA 0R33121120354 01620000000000000012 |T8903:A¬GLS Germany GmbH & Co.OHG¬Pinkertsweg 49¬Hamburg¬¬¬800018406¬|PRINTINFO:|PRINT1:|RESULT:E000:552502000716|T565:112059|PRINT0:xxGLSintermecpf4i.int01|/////GLS/////';
    //$returnedtag = iconv("ISO-8859-1" ,"UTF-8//TRANSLIT", $returnedtag);

    if( stripos($returnedtag ,'\\\\\\\\\\GLS\\\\\\\\\\' ) !== false && stripos($returnedtag ,'/////GLS/////' ) !== false ){
        $returnedtag = str_ireplace ( array('\\\\\\\\\\GLS\\\\\\\\\\','/////GLS/////') ,'', $returnedtag);
    } else {
        return 'Fehler: Kein gültiger GLS Stream';
    }
    //Sonderzeichen der Datamatrix2 umwandeln in + für die Speicherung in der Datenbank
    $returnedtag = str_replace("¬", "+",$returnedtag);
    $returnedtag = explode('|',$returnedtag);
    $glsTags = array();
    foreach ($returnedtag as $item) {
        if (stripos($item,'T') === 0) {
            $tmp = explode(':',$item,2); $tmp[0] = str_ireplace('T','',$tmp[0]);
            if($tmp[1] != ''){
                $glsTags[$tmp[0]] = $tmp[1] ;
            }
        }elseif (stripos($item,'RESULT') === 0 && stripos($item,'E000') === false ) {
            return 'Fehler - Rückgabewert der Unibox : '.$item;
        }
        $tmp = null;
    }
    return $glsTags;
}