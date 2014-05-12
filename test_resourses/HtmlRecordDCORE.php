<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../control/DspaceOAIRequester.php';

//getting dspace oai request attributes
//production
//$oaiBaseUrl = "http://bibliotecadigital.icesi.edu.co/biblioteca_digital-oai/request";
//$retrieveBaseUrl="http://bibliotecadigital.icesi.edu.co/biblioteca_digital/retrieve/";
//development
$oaiBaseUrl = "http://192.168.220.28:8100/biblioteca_digital-oai/request";
$retrieveBaseUrl = "http://192.168.220.28:8100/biblioteca_digital/retrieve/";
$identifier = $_POST["identifier"];
$scope = $_POST["scope"];
$start = $_POST["start"];
$rpp = $_POST["rpp"];
$sort_by = $_POST["sort_by"];
$order = $_POST["order"];
$query = $_POST["query"];

$dspaceOAIRequester = new DspaceOAIRequester($oaiBaseUrl);
$qdcResponse = new SimpleXMLElement($dspaceOAIRequester->getRecord($identifier, 'qdc'));
$oreResponse = new SimpleXMLElement($dspaceOAIRequester->getRecord($identifier, 'ore'));
print_r($qdcResponse);
print_r($oreResponse);

$mainTitle = null;

$titles = null;

$files = null;

$contributors = null;

$dateIssued = null;

$types = null;

$subjects = null;

$publishers = null;

$description = null;

$relatedItems = null;

$identifiers = null;
//echo "idz:".$identifiers->count();

$previewXlink = null;
$previewImage = null;
foreach ($files as $fileGrp) {    
    if ($fileGrp['USE'] == "BRANDED_PREVIEW") {
        //print_r($fileGrp);
        $previewXlink = $fileGrp->file[0]->FLocat[0]->attributes("xlink", true)->href;
        $retrieveUrl = $retrieveBaseUrl . str_replace(".jpeg", "", substr($previewXlink, strlen("bitstream_")));        
    }
}

echo "-previewURL:" . $retrieveUrl;

//displaying record content
echo '<div id="dspacerecord" class="ui-widget-content ui-corner-all">';
echo '<div id="record-header ui-widget-header"><h3>' . $mainTitle . '</h3></div>';
echo '<div id="record-content">';
//if ($previewImage != null && $previewImage != '') {
if ($retrieveUrl!=null && $retrieveUrl!='') {
    echo '<div id="record-preview"><img src="'.$retrieveUrl.'"/></div>';
}
echo '<table id="record-metadata">';

//titles
echo '<tr data-metadata="title"><th>T&iacute;tulo:</th><td>';
foreach ($titles as $titleInfo){
    //print_r($titleInfo);     
    if (($titleInfo->attributes()->type==null) || ($titleInfo->attributes()->type!="alternative")){
        echo $titleInfo->children("mods", true)->title. '<br/>';
    }
    
}
echo '<td></tr>';

//title alternative
echo '<tr data-metadata="title-alternative"><th>Otros t&iacute;tulos</th><td>';
foreach ($titles as $titleInfo){
    if (($titleInfo->attributes()->type!=null) && ($titleInfo->attributes()->type=='alternative'))
    echo $titleInfo->children("mods", true)->title. '<br/>';
}
echo '<td></tr>';

//creators and collaborator
echo '<tr data-metadata="creator"><th>Autores y Colaboradores:</th><td>';
foreach ($contributors as $contributor){        
    echo $contributor->children("mods", true)->namePart.'<br/>';
}
echo '<td></tr>';

//date issued
echo '<tr data-metadata="date-issued"><th>Fecha de Publicaci&oacute;n:</th><td>';
echo $dateIssued;
echo '<td></tr>';

//document types
echo '<tr data-metadata="type"><th>Tipo de documento:</th><td>';
foreach ($types as $type){        
    echo $type.'<br/>';
}
echo '<td></tr>';

//subjects
echo '<tr data-metadata="subject"><th>Palabras Clave:</th><td>';
foreach ($subjects as $subject){        
    echo $subject->children("mods", true)->topic.'<br/>';
}
echo '<td></tr>';

//publisher
echo '<tr data-metadata="publisher"><th>Editorial:</th><td>';
foreach ($publishers as $publisherOption){        
    $publisher=$publisherOption->children("mods", true)->publisher.'<br/>';
    if($publisher!=null){
        echo $publisher;
    }
}
echo '<td></tr>';

//publisher
echo '<tr data-metadata="description"><th>Descripci&oacute;n:</th><td>';
echo $description;
echo '<td></tr>';

//citation
//echo '<tr data-metadata="citation"><th>Citaci&oacute;n:</th><td>';
//foreach ($relatedItems as $relatedItem){        
//    if($relatedItem->attributes()->type=="host"){
//        echo $relatedItem->children("mods", true)->part[0]->children("mods", true)->text;
//    }
//}
//echo '<td></tr>';

echo '<tr data-metadata="isparofseries"><th>Hace parte de:</th><td>';
foreach ($relatedItems as $relatedItem){        
    if($relatedItem->attributes()->type=="series"){
        echo $relatedItem;
    }
}
echo '<td></tr>';




echo '</table>';

foreach ($identifiers as $recordId){        
    if($recordId->attributes()->type=="uri"){
        echo '<a class="app_navlink" href="'.$recordId.'" title="Ver el registro completo en la Biblioteca Digital de la Universidad Icesi">Ver el registro completo</a>';
    }
}


echo '</div>';
echo '</div>';
echo '</div>';
?>
