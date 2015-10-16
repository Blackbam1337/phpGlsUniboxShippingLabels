<?php
/**
 * Main class for controlling the flow.
 */

class Creator {

    private $errors = array();
    private $content;
    private $packagenumber;

    public function getErrors() {
        return $this->errors;
    }

    public function createFromJson($json) {

        // 1. Parse JSON
        $decoded = json_decode($json);

        if($decoded!==null) {

            if($this->validateJson($decoded)) {

                $this->packagenumber = $decoded->package->number;

                $tags = $this->getPreparedTags($decoded->tags_format,$decoded->tags);

                // Populate Config overwrite defaults
                Config::$glsbox_label_beginx = intval($decoded->pdf->beginx); // zero is the default anyway
                Config::$glsbox_label_beginy = intval($decoded->pdf->beginy);

                $format = resempty($decoded,array('pdf','format'),'A4',Primitive::STR);

                if(in_array($format,array('A4','A5'))) {
                    Config::$glsbox_label_papersize = $format;
                }

                Config::$filename_prefix = resempty($decoded,array('pdf','prefix'),"",Primitive::STR);

                $mode = resempty($decoded,'mode','business',Primitive::STR);

                if(in_array($mode,array('business','express'))) {
                    Config::$mode = $mode;
                }


                $glsService = null;
                if(Config::$mode=="express") {
                    $glsService = new Gls_Unibox_Model_Label_Gls_Express();
                } else {
                    $glsService = new Gls_Unibox_Model_Label_Gls_Business();
                }

                $glsService->importValues($tags);
                $ready_tags = $glsService->getData();

                $gls_unibox_label_pdf = new Gls_Unibox_Model_Pdf_Label();
                $pdf = $gls_unibox_label_pdf->createLabel($ready_tags);
                $this->content = $pdf->render();

                return true;
            } else {
                array_push($this->errors,new Error("Error: Validation of input parameters did not succeed."));
            }

        } else {
            array_push($this->errors,new Error("Error: Decoding of the JSON-Input failed - invalid JSON."));
        }
        return false;
    }


    public function flush() {
        http_response_code(200);
        header('Pragma: public',true);
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0',true);
        header('Content-type: application/pdf',true);
        header('Content-Length: '.strlen($this->content));
        header('Content-Disposition: attachment; filename='.Config::$filename_prefix.$this->packagenumber.'.pdf');
        header('Last-Modified: '.date('r'));

        echo $this->content;
    }

    protected function getPreparedTags($tags_format,$tag_input) {
        switch ($tags_format):
            case "string": {
                return $this->parseIncomingTag($tag_input);
            }
            case "json": {
                return json_decode($tag_input);
            }
            case "comma": {
                $tagar = array();
                $tags = explode(PHP_EOL,$tag_input);
                foreach($tags as $tag) {
                    $tp = array_map('trim',explode(":",$tag));
                    $tagar[$tp[0]] = $tp[1];
                }
                return $tagar;
            }
        endswitch;
    }

    protected function validateJson($json) {

        if(!in_array($json->tags_format,array("string","comma","json"))) {
            array_push($this->errors,new Error("Error: Unknown tags format."));
            return false;
        }

        if(!isset($json->tags) || $json->tags=="") {
            array_push($this->errors,new Error("Error: Tags missing."));
            return false;
        }

        if(!isset($json->package->label) || $json->package->label=="") {
            array_push($this->errors,new Error("Error: Package Label is required."));
            return false;
        }

        if(!isset($json->package->number) || $json->package->number=="") {
            array_push($this->errors,new Error("Error: Package Number is required."));
            return false;
        }

        return true;
    }

    protected function parseIncomingTag($returnedtag) {
        //$returnedtag = '\\\\\\\\\\GLS\\\\\\\\\\T010:|T050:Versandsoftwarename|T051:V 1.5.2|T8700:DE 550|T330:20354|T090:NOPRINT|T400:552502000716|T545:26.01.2009|T8904:001|T8905:001|T800:Absender|T805:12|T810:GLS IT Services GmbH|T811:Project Management|T820:GLS Germany Str. 1-7|T821:DE|T822:36286|T823:Neuenstein / Aua|T851:KD Nr.:|T852:10166|T853:ID No.:|T854:800018406|T859:Company|T860:GLS Germany GmbH & Co.OHG|T861:Depot 20|T863:Pinkertsweg 49|T864:Hamburg|T921:Machine Parts|T8914:27600ABCDE |T8915:2760000000|T080:4.67|T520:21012009|T510:ba|T500:DE 550|T560:DE03|T8797:IBOXCUS|T540:26.01.2009|T541:11:20|T100:DE|CTRNUM:276|CTRA2:DE|T202:|T210:|ARTNO:Standard|T530:16.20|ALTZIP:20354|FLOCCODE:DE 201|OWNER:5|TOURNO:1211|T320:1211|TOURTYPE:21102|SORT1:0|T310:0|T331:20354|T890:2001|ROUTENO:1006634|ROUTE1:R33|T110:R33|FLOCNO:629|T101: 201|T105:DE|T300:27620105|NDI:|T8970:A|T8971:A|T8975:12|T207:|T206:10001|T8980:AA|T8974:|T8916:552502000716|T8950:Tour|T8951:ZipCode|T8952:Your GLS Track ID|T8953:Product|T8954:Service Code|T8955:Delivery Address|T8956:Contact|T8958:Contact|T8957:Customer ID|T8959:Phone|T8960:Note|T8961:Parcel|T8962:Weight|T8963:Notification on damage which is not recognisable from outside had to be submitted to GLS|T8964:on the same Day of Delivery in writing. This Transport is based on GLS terms and conditions|T8913:ZFFX4HDZ|T8972:ZFFX4HDZ|T8902:ADE 550DE 20100000000002760000000ZFFX4HDZAA 0R33121120354 01620000000000000012 |T8903:A¬GLS Germany GmbH & Co.OHG¬Pinkertsweg 49¬Hamburg¬¬¬800018406¬|PRINTINFO:|PRINT1:|RESULT:E000:552502000716|T565:112059|PRINT0:xxGLSintermecpf4i.int01|/////GLS/////';
        //$returnedtag = iconv("ISO-8859-1" ,"UTF-8//TRANSLIT", $returnedtag);


        $re = "/\\\\*GLS\\\\*([^\\/]+)\\/+GLS\\/+/";

        // check if gls string is valid and works
        if(preg_match($re, $returnedtag)) {
            $returnedtag = preg_replace($re, "$1", $returnedtag);
        } else {
            array_push($this->errors,new Error('Fehler: Kein gültiger GLS Stream'));
            return false;
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
                array_push($this->errors,new Error('Fehler - Rückgabewert der Unibox : '.$item));
                return false;
            }
            $tmp = null;
        }
        return $glsTags;
    }

}