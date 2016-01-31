<?php
/**
 * This class does a requests to the dspace servlets. This class is not being used for the moment.
 *
 * @author David andrés Manzano Herrera - damanzano
 * @since 2015-12-19
 * @package control
 */
class DspaceFileRequester {
    
    static public function getFile($dspaceFileUrl){
       
    }
    
    static public function getImage($dspaceImageUrl){
         try {
            $request= curl_init();            
            curl_setopt($request, CURLOPT_URL, $dspaceImageUrl);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $response=curl_exec($request);
            curl_close($request);            
            return $response;
        } catch (HttpException $exp) {
            echo $exp;
        }
    }
}

?>
