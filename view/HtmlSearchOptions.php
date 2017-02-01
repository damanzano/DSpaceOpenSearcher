<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

/**
 * Description configuration options as html
 *
 * @author David Andrés Manzano Herrera - Damanzano
 * @since 2015-12-23
 * @package view
 */
class HtmlSearchOptions {

    private $xmlStructure;
    
    function __construct($repositoryKey = null) {
        if ($repositoryKey) {
            $this->xmlStructure = simplexml_load_file("config/categorysearch_" . $repositoryKey . ".xml");
        }else{
            $this->xmlStructure = simplexml_load_file("config/categorysearch_config.xml");
        }
    }
    
    /**
     * Set the configuration file if necessary
     * @param type $repositoryKey
     */
    public static function setConfigurationFile($repositoryKey) {
        if ($repositoryKey) {
            $this->$xmlStructure = simplexml_load_file("config/categorysearch_" . $repositoryKey . ".xml");
        }else{
            $this->$xmlStructure = simplexml_load_file("config/categorysearch_config.xml");
        }
    }
    
    /**
     * Prints a <link> tag corresponding to the additional stylesheets that should be used
     * @return string
     */
    public function cssLinks(){
        $html = '';
        $styles = $this->xmlStructure->styles[0]->style;
        if($styles!=null && $styles->count()>0){
            $orderedStyles = array();
            foreach ($styles as $style){
                $orderedStyles[filter_var($style['order'], FILTER_SANITIZE_NUMBER_INT)] = $style;
            }
            foreach ($orderedStyles as $style){
                $html.= '<link rel="stylesheet" href="'.$style['url'].'" />';
            }
        }
        
        return $html;
    }
    
    /**
     * Prints a <script> tag corresponding to the additional javascripts that should be used
     * @return string
     */
    public function jsLinks(){
        $html = '';
        $styles = $this->xmlStructure->scripts[0]->script;
        if($styles!=null && $styles->count()>0){
            $orderedStyles = array();
            foreach ($styles as $style){
                $orderedStyles[filter_var($style['order'], FILTER_SANITIZE_NUMBER_INT)] = $style;
            }
            foreach ($orderedStyles as $style){
                $html.= '<link rel="stylesheet" href="'.$style['url'].'" />';
            }
        }
        
        return $html;
    }
    
    /**
     * Prints a page title with the name of the select repository
     * @return string
     */
    public function repositoryName(){
        
        $repository = $this->xmlStructure->repository[0];
        if($repository){
            return $repository;
        }else{
            return null;
        }
    }
    
    /**
     * Returns the id of the top comuunity in the consfiguration file, 
     * <null> in case there is not a <community> tag in the configuration file.
     */
    public function topCommunityId(){
        $topCommunity = $this->xmlStructure->community[0];
        if($topCommunity){
            return $topCommunity['identifier'];
        }
    }
    
    public function ComunitiesHeight(){
        return $this->communityHeight($this->xmlStructure->community[0]);
    }
    
    /**
     * Returns the depth of the communities tree. collections do not count.
     * 
     * @param type $communityId
     */
    private function communityHeight($xmlCommunity){
        if($xmlCommunity->community->count()==0){
            return 0;
        }else{
            $levelHeights=array() ;
            $subcommunities = $xmlCommunity->community;
            foreach ($subcommunities as $subcommunity) {
                $levelHeights[] = $this->communityHeight($subcommunity);
            }
            return 1+  max($levelHeights);
        }
    }

    /**
     * Prints selects options with the given community's tree structure
     * 
     * @author damanzano  
     * @since 2015-12-23
     * @param string $communityId
     * @param boolean $showCollections
     * @param boolean $showTopTitle
     * @param int $depth
     * @param boolean $subcommunitiesAsValues
     */
    public function communityScopes($communityId, $showCollections = true, $showTopTitle = true, $depth = -1, $subcommunitiesAsValues = false) {
        $html = '';
        $communities = $this->xmlStructure->community;
        $communityFound = false;
        for ($i = 0; (($i < $communities->count()) && (!$communityFound)); $i++) {
            $community = $communities[$i];
            //process the given community
            $theCommunity = $this->lookForCommunity($communityId, $community);
            //print_r($theCommunity);
            if ($theCommunity != null) {
                $communityFound = true;
                $html = $this->communityOptions($theCommunity, $html, $showCollections, $showTopTitle, $depth, $subcommunitiesAsValues);
            }
        }

        return $html;
    }

    /**
     * Looks for a comunnity element identified with the $comunityId paramenter, inside a given a parent xml structure. 
     * Return a xml structure representing the found comunnity or <null> in case the given identifier is not found.
     * @param string $communityId
     * @param xml $xmlCommunity
     * @return xml
     */
    private function lookForCommunity($communityId, $xmlCommunity) {
        $foundCommunity = null;

        if ($xmlCommunity["identifier"] == $communityId) {
            $foundCommunity = $xmlCommunity;
            return $foundCommunity;
        } else {
            $subcommunities = $xmlCommunity->community;
            if ($subcommunities != null && $subcommunities->count() > 0) {
                foreach ($subcommunities as $subcommunity) {
                    $foundCommunity = $this->lookForCommunity($communityId, $subcommunity);
                    if ($foundCommunity != null) {
                        return $foundCommunity;
                    }
                }
            }
        }
        return $foundCommunity;
    }

    /**
     * Return a string with the html representation of the search option for a comunnity.
     * @param xml $xmlCommunity
     * @param string $html
     * @param boolean $showCollections
     * @param boolean $showOwnTitle
     * @param int $depth
     * @param boolean $subcommunitiesAsValues
     * @param int $start
     * @return string 
     */
    private function communityOptions($xmlCommunity, $html, $showCollections = true, $showOwnTitle = true, $depth = -1, $subcommunitiesAsValues = false, $start = 0) {
        if ($depth != 0) {

            if ($showOwnTitle && $start == 0) {
                $html.='<option value="' . $xmlCommunity["identifier"] . '" data-level="' . $start . '" data-subject="' . $xmlCommunity->name . ' ">' . $xmlCommunity->name . ' - Todo</option>';
            } else {
                $html.='<option value="' . $xmlCommunity["identifier"] . '" data-level="' . $start . '" data-subject="' . $xmlCommunity->name . '">' . $xmlCommunity->name . ' </option>';
            }

            $subcommunities = $xmlCommunity->community;
            //echo "Sub comunidades:" . $subcommunities->count();

            if ($subcommunities != null && $subcommunities->count() > 0) {

                foreach ($subcommunities as $subcommunity) {

//                    if ($subcommunitiesAsValues && !$showOwnTitle) {
//                        $html.= '<option value="' . $subcommunity["identifier"] . '" data-subject="' . $subcommunity->name . '">' . $subcommunity->name . '</option>';
//                    }

                    if ($depth == -1) {
                        $html = $this->communityOptions($subcommunity, $html, $showCollections, false, $depth, false, $start + 1);
                    } else {
                        $html = $this->communityOptions($subcommunity, $html, $showCollections, false, $depth - 1, false, $start + 1);
                    }
                }
            }


            if ($showCollections) {
                $collections = $xmlCommunity->collection;
                //echo "coleciones:" . $collections->count();
                if ($collections != null && $collections->count() > 0) {
                    foreach ($collections as $collection) {
                        $collectionName = $collection->name;
                        $collectionName = str_replace("APFFVC - ", "", $collectionName);
                        $collectionName = str_replace("FCCV - ", "", $collectionName);
                        $collectionName = str_replace("FFDO - ", "", $collectionName);
                        $collectionName = str_replace(" - Patrimonial", "", $collectionName);
                        $html.='<option value="' . $collection["identifier"] . '" data-level="leaf" data-subject="' . $collectionName . '">' . $collectionName . '</option>';
                    }
                }
            }


            return $html;
        } else {
            return $html;
        }
    }

}

?>
