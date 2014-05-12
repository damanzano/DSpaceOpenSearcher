<?php
/**
 * This class does requests to a dspace oai webapp
 *
 * @author David andrés Manzano Herrera - damanzano
 * @since 2011-12-19
 * @package control
 */
class DspaceOAIRequester {
  
    private $oaiBaseUrl;
    
    /**
     * Contructor of DspaceOAIRequester object
     * @author damanzano
     * @since 2011-12-19
     * @param string $oaiBaseUrl Base url for dspace oai requests
     */
    function __construct($oaiBaseUrl) {
        $this->oaiBaseUrl = $oaiBaseUrl;
    }

    /**
     * This method returns a xml string for a oai GetRecord request
     * @author damanzano
     * @since 2011-12-19
     * @param string $identifier oai idenfier for the dspace item to be gotten
     * @param string $metadaPrefix oai request metadata prefix 
     */
    public function getRecord($identifier,$metadataPrefix='oai_dc'){
        try {
            $request= curl_init();
            $params="verb=GetRecord&identifier=".$identifier."&metadataPrefix=".$metadataPrefix;
            curl_setopt($request, CURLOPT_URL, $this->oaiBaseUrl."?".$params);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $response=curl_exec($request);
            curl_close($request);
            //echo ("<pre>".$this->oaiBaseUrl."?".$params."</pre>");
            return $response;
        } catch (HttpException $exp) {
            echo $exp;
        }
    }
    
    public function getOaiBaseUrl() {
        return $this->oaiBaseUrl;
    }

    public function setOaiBaseUrl($oaiBaseUrl) {
        $this->oaiBaseUrl = $oaiBaseUrl;
    }

}

?>
