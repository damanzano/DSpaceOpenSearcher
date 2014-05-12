<?php

//ini_set('display_errors',1); 
//error_reporting(E_ALL ^ E_NOTICE);
/**
 * Display the a record in html format
 *
 * @author David Andrés Manzano Herrera - Damanzano
 * @since 2011-12-19
 * @package view
 */

include_once '../control/DspaceOAIRequester.php';
include_once '../control/DspaceFileRequester.php';

//getting dspace oai request attributes
//production
//$oaiBaseUrl = "http://bibliotecadigital.icesi.edu.co/biblioteca_digital-oai/request";
//$retrieveBaseUrl="http://bibliotecadigital.icesi.edu.co/biblioteca_digital/retrieve/";
//development
//$oaiBaseUrl = "http://192.168.220.28:8100/biblioteca_digital-oai/request";
//$retrieveBaseUrl = "http://192.168.220.28:8100/biblioteca_digital/retrieve/";
//test
$oaiBaseUrl = "http://192.168.220.228:8100/biblioteca_digital-oai/request";
$retrieveBaseUrl="http://192.168.220.228:8100/biblioteca_digital/retrieve/";

$identifier = $_POST["identifier"];
$scope = $_POST["scope"];
$start = $_POST["start"];
$rpp = $_POST["rpp"];
$sort_by = $_POST["sort_by"];
$order = $_POST["order"];
$query = $_POST["query"];

$dspaceOAIRequester = new DspaceOAIRequester($oaiBaseUrl);
$xmlResponse = new SimpleXMLElement($dspaceOAIRequester->getRecord($identifier, 'mets'));

if(!isset($xmlResponse->{"error"})){
$mainTitle = (string) $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->titleInfo[0]->children("mods", true)->title;

$titles = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->titleInfo;

$files = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->fileSec[0]->fileGrp;

$contributors = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->name;

$dateIssued = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->originInfo[0]->children("mods", true)->dateIssued;

$types = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->genre;

$subjects = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->subject;

$publishers = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->originInfo;

$description = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->note[0];

$relatedItems = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->relatedItem;

$identifiers = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->identifier;

$previewXlink = null;
$retrieveUrl = null;
/*
DSpace-oai 4.1 does not expose the branded preview image so it has to be replaced by the original bundle
foreach ($files as $fileGrp) {    
    if ($fileGrp['USE'] == "BRANDED_PREVIEW") {        
        $previewXlink = $fileGrp->file[0]->FLocat[0]->attributes("xlink", true)->href;
        $retrieveUrl = $retrieveBaseUrl . str_replace(".jpeg", "", substr($previewXlink, strlen("bitstream_")));        
    }
}
*/
foreach ($files as $fileGrp) {    
    if ($fileGrp['USE'] == "BRANDED_PREVIEW") {        
		$retrieveUrl = $previewXlink = $fileGrp->file[0]->FLocat[0]->attributes("xlink", true)->href;     
    }
}
//displaying record content
echo '<div id="dspacerecord" class="ui-widget-content ui-corner-all">';
echo '<div id="record-header" class="ui-widget-header"><button class="back" data-icon="arrow-l" data-role="button">Volver</button><h3>' . $mainTitle . '</h3></div>';
echo '<div id="record-content">';

if ($retrieveUrl!=null && $retrieveUrl!='') {
    echo '<div id="record-preview"><img src="'.$retrieveUrl.'"/></div>';
}
echo '<table id="record-metadata">';

//titles
echo '<tr data-metadata="title"><th>T&iacute;tulo:</th><td>';
foreach ($titles as $titleInfo){         
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
echo '<tr data-metadata="citation"><th>Citaci&oacute;n:</th><td>';
foreach ($relatedItems as $relatedItem){        
    if($relatedItem->attributes()->type=="host"){        
        if($relatedItem->children("mods", true)->part!=null && $relatedItem->children("mods", true)->part!=''){
            echo $relatedItem->children("mods", true)->part[0]->children("mods", true)->text;
        }
    }
}
echo '<td></tr>';

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
        echo '<a class="app_navlink" href="'.$recordId.'" target="_blank" title="Ver el registro completo en la Biblioteca Digital de la Universidad Icesi">Ver el registro completo</a>';
    }
}

echo '</div>';
echo '</div>';
echo '</div>';
}else{
  echo 'Error: ' . $xmlResponse->{"error"};
}
?>
