<?php
/**
 * This class does requests to a dspace open-search servlet and save the xml response into a variable
 *
 * @author David Andrés Manzano Herrera - Damanzano
 * @since 2015-12-19
 * @package control
 */
class DspaceOpensearchRequester {

    private $url;
    private $format;
    private $scope;
    private $start;
    private $rpp;
    private $sort_by;
    private $order;
    private $query;
	private $location;
    
    /**
     * Constructor of DspaceOpensearchRequester object.
     * @author damanzano
     * @since 2015-12-16
     * @param string $url
     * @param string $format
     * @param string $scope
     * @param int $start
     * @param int $rpp
     * @param int $sort_by
     * @param string $order
     * @param string $query
     */
    function __construct($url, $format, $scope, $start, $rpp, $sort_by, $order, $query) {
        $this->url = $url;
        $this->format = $format;
        $this->scope = $scope;
        $this->start = $start;
        $this->rpp = $rpp;
        $this->sort_by = $sort_by;
        $this->order = $order;
        $this->query = $query;
		$this->location = $scope;
    }

    /**
     * Set the attributes for DspaceOpensearchRequester object. This method is equivalent to the contructor.
     * @author damanzano
     * @since 2015-12-16
     * @param string $url
     * @param string $format
     * @param string $scope
     * @param int $start
     * @param int $rpp
     * @param int $sort_by
     * @param string $order
     * @param string $query
     */
    public function setAttributes($url, $format, $scope, $start, $rpp, $sort_by, $order, $query) {
        $this->url = $url;
        $this->format = $format;
        $this->scope = $scope;
        $this->start = $start;
        $this->rpp = $rpp;
        $this->sort_by = $sort_by;
        $this->order = $order;
        $this->query = $query;
		$this->location=$scope;
    }

    /**
     * This method returns a xml string for a open search request
     * @author damanzano
     * @since 2015-12-16
     */
    public function sendRequest() {
        try {
            $request= curl_init();
            $params="format=".$this->format."&scope=".$this->scope."&location=".$this->location."&query=".$this->query."&start=".$this->start."&rpp=".$this->rpp."&sort_by=".$this->sort_by."&order=".$this->order;
            curl_setopt($request, CURLOPT_URL, $this->url."?".$params);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $response=curl_exec($request);
            curl_close($request);
            //echo ("<pre>".$this->url."?".$params."</pre>");
            return $response;           

        } catch (HttpException $exp) {
            echo $exp;
        }
    }
    
    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getFormat() {
        return $this->format;
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getScope() {
        return $this->scope;
    }

    public function setScope($scope) {
        $this->scope = $scope;
		$this->location = $scope;
    }

    public function getStart() {
        return $this->start;
    }

    public function setStart($start) {
        $this->start = $start;
    }

    public function getRpp() {
        return $this->rpp;
    }

    public function setRpp($rpp) {
        $this->rpp = $rpp;
    }

    public function getSort_by() {
        return $this->sort_by;
    }

    public function setSort_by($sort_by) {
        $this->sort_by = $sort_by;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function getQuery() {
        return $this->query;
    }

    public function setQuery($query) {
        $this->query = $query;
    }
	
	public function getLocation() {
        return $this->location;
    }

    public function setLocation($location) {
        $this->location = $location;
		$this->scope = $location;
    }

}

?>
