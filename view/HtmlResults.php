<?php

/**
 * Display the search results in html format
 *
 * @author David Andrés Manzano Herrera - Damanzano
 * @since 2011-12-16
 * @package view
 */
include_once '../control/DspaceOpensearchRequester.php';
include_once 'Navigator.php';

//getting dspace request attributes
//production
//$url = "http://bibliotecadigital.icesi.edu.co/biblioteca_digital/open-search";
//$oaiBaseIdentifier = "oai:http://www.icesi.edu.co/biblioteca_digital:";
//development
//$url = "http://192.168.220.28:8100/biblioteca_digital/open-search";
//$oaiBaseIdentifier="oai:http://192.168.220.29/biblioteca_digital:";
//test
$url = "http://192.168.220.228:8100/biblioteca_digital/open-search";
$oaiBaseIdentifier = "oai:192.168.220.228:";

$format = "atom";
$scope = $_POST["scope"];
$start = $_POST["start"];
$rpp = $_POST["rpp"];
$sort_by = $_POST["sort_by"];
$order = $_POST["order"];
$query = $_POST["query"];

//making request to dspace repository
$dspaceRequester = new DspaceOpensearchRequester($url, $format, $scope, $start, $rpp, $sort_by, $order, $query);
$xmlResponse = new SimpleXMLElement($dspaceRequester->sendRequest());

//calculating pages and result size
$size = (string) $xmlResponse->children("opensearch", true)->totalResults;
$xmlEntries = $xmlResponse->entry->count();
$navigation = new Navigator();
$navigation->setAttributes($start, $rpp, $size, $query);

//top navigation 
echo '<div class="nav-wrapper ui-widget-content ui-corner-all">' . $navigation->render() . '</div>';

//displaying results in html format
echo '<div id="dspaceresults" class="ui-widget-content ui-corner-all">
      <table class="results-table">
        <tr>
            <th>Fecha de Publicaci&oacute;n</th>
            <th>T&iacute;tulo</th>
            <th>Autor(es)</th>
            <th></th>
        </tr>';
for ($i = 0; ($i < $rpp) && ($i < $xmlEntries); $i++) {
    if (($start + $i) <= $size) {
        $fullHandleUrl = $xmlResponse->entry[$i]->id;
        $dspaceId = $oaiBaseIdentifier . substr($fullHandleUrl, 22);

        $published = substr($xmlResponse->entry[$i]->published, 0, 10);

        echo '<tr><td>' . $published . '</td>';
        echo '<td><a class="dspace-item" data-dspaceitemid="' . $dspaceId . '" href="' . $xmlResponse->entry[$i]->id . '">' . $xmlResponse->entry[$i]->title . '</a></td>';
       $creator = (string) $xmlResponse->entry[$i]->author[0]->name;
       
        echo '<td>';
        if ($creator != null && $creator != '') {
            echo $creator;
        }
        echo '</td>';
        //echo '<td><a class="app_navlink" href="' . $xmlResponse->entry[$i]->id . '" target="_blank">Ver registro completo</td></tr>';
        echo '<td><a class="dspace-item app_navlink" data-dspaceitemid="' . $dspaceId . '" href="' . $xmlResponse->entry[$i]->id . '" target="_blank">Ver registro</td></tr>';
    }
}
echo '</table></div>';

//bottom navigation
echo '<div class=" nav-wrapper ui-widget-content ui-corner-all">' . $navigation->render() . '</div>';
?>
