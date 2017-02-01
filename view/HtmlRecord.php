<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Display the a record in html format
 * 
 * @author David Andrés Manzano Herrera - Damanzano
 * @since 2015-12-19
 * @package view
 */
include_once '../control/DspaceOAIRequester.php';
include_once '../control/DspaceFileRequester.php';

// Getting dspace oai request attributes
// Development and test
//$oaiBaseUrl = "http://192.168.220.28:8100/biblioteca_digital-oai/request";
//$retrieveBaseUrl = "http://192.168.220.28:8100/biblioteca_digital/retrieve/";
// Production
$oaiBaseUrl = "https://vitela.javerianacali.edu.co/oai/request";
$retrieveBaseUrl = "https://vitela.javerianacali.edu.co/retrieve/";

$identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_STRING);
$scope = filter_input(INPUT_POST, 'scope', FILTER_SANITIZE_STRING);
$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT);
$rpp = filter_input(INPUT_POST, 'rpp', FILTER_SANITIZE_NUMBER_INT);
$sort_by = filter_input(INPUT_POST, 'sort_by', FILTER_SANITIZE_STRING);
$order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_STRING);
$query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING);

$dspaceOAIRequester = new DspaceOAIRequester($oaiBaseUrl);
$oaiResponse = $dspaceOAIRequester->getRecord($identifier, 'mets');
if ($oaiResponse != null) {
    try {
        $xmlResponse = new SimpleXMLElement($oaiResponse);

        if (!isset($xmlResponse->{"error"})) {
            $mainTitle = (string) $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->titleInfo[0]->children("mods", true)->title;

            $titles = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->titleInfo;

            $files = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->fileSec[0]->fileGrp;

            $contributors = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->name;

            //$dateIssued = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->extesion[0]->children("mods", true)->date;
            
            $dateAvialable = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->extension[1]->children("mods", true)->dateAvialable;

            $types = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->genre;

            $subjects = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->subject;

            $publishers = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->originInfo;

            $description = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->note[0];

            $relatedItems = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->relatedItem;

            $identifiers = $xmlResponse->GetRecord[0]->record[0]->metadata[0]->mets[0]->dmdSec[0]->mdWrap[0]->xmlData[0]->children("mods", true)->mods[0]->children("mods", true)->identifier;

            $previewXlink = null;
            $retrieveUrl = null;
            /*
              DSpace-OAI 4.1 ang higher do not expose the branded preview image so it has to be replaced by the original bundle
              foreach ($files as $fileGrp) {
              if ($fileGrp['USE'] == "BRANDED_PREVIEW") {
              $previewXlink = $fileGrp->file[0]->FLocat[0]->attributes("xlink", true)->href;
              $retrieveUrl = $retrieveBaseUrl . str_replace(".jpeg", "", substr($previewXlink, strlen("bitstream_")));
              }
              }
             */
            
            // url of original files
            foreach ($files as $fileGrp) {
                if ($fileGrp['USE'] == "ORIGINAL") {
                    $retrieveUrl = $previewXlink = $fileGrp->file[0]->FLocat[0]->attributes("xlink", true)->href;
                }
            }
            
            //displaying record content
            echo '<div id="dspacerecord" class="ui-widget-content ui-corner-all">';
            echo '<div id="record-header" class="ui-widget-header"><button class="back" data-icon="arrow-l" data-role="button">Volver</button><h3>' . $mainTitle . '</h3></div>';
            echo '<div id="record-content">';
            
            // display main original file
            if ($retrieveUrl != null && $retrieveUrl != '') {
                echo '<div id="record-preview"><img width="100%" src="' . $retrieveUrl . '"/></div>';
            }
            
            // metadata
            echo '<table id="record-metadata">';

            //titles
            echo '<tr data-metadata="title"><th>T&iacute;tulo:</th><td>';
            foreach ($titles as $titleInfo) {
                if (($titleInfo->attributes()->type == null) || ($titleInfo->attributes()->type != "alternative")) {
                    echo $titleInfo->children("mods", true)->title . '<br/>';
                }
            }
            echo '<td></tr>';

            //title alternative
            echo '<tr data-metadata="title-alternative"><th>Otros t&iacute;tulos</th><td>';
            foreach ($titles as $titleInfo) {
                if (($titleInfo->attributes()->type != null) && ($titleInfo->attributes()->type == 'alternative'))
                    echo $titleInfo->children("mods", true)->title . '<br/>';
            }
            echo '<td></tr>';

            //creators and collaborator
            echo '<tr data-metadata="creator"><th>Autores y Colaboradores:</th><td>';
            foreach ($contributors as $contributor) {
                echo $contributor->children("mods", true)->namePart . '<br/>';
            }
            echo '<td></tr>';

            //date issued
            //echo '<tr data-metadata="date-issued"><th>Fecha de Publicaci&oacute;n:</th><td>';
            //echo $dateIssued;
            //echo '<td></tr>';
            
            //date available
            echo '<tr data-metadata="date-issued"><th>Fecha de Publicaci&oacute;n:</th><td>';
            echo $dateAvialable;
            echo '<td></tr>';

            //document types
            echo '<tr data-metadata="type"><th>Tipo de documento:</th><td>';
            foreach ($types as $type) {
                echo $type . '<br/>';
            }
            echo '<td></tr>';

            //subjects
            echo '<tr data-metadata="subject"><th>Palabras Clave:</th><td>';
            foreach ($subjects as $subject) {
                echo $subject->children("mods", true)->topic . '<br/>';
            }
            echo '<td></tr>';

            //publisher
            echo '<tr data-metadata="publisher"><th>Editorial:</th><td>';
            foreach ($publishers as $publisherOption) {
                $publisher = $publisherOption->children("mods", true)->publisher;
                if ($publisher != null) {
                    echo $publisher . '<br/>';
                }
            }
            echo '<td></tr>';

            //publisher
            echo '<tr data-metadata="description"><th>Descripci&oacute;n:</th><td>';
            echo $description;
            echo '<td></tr>';

            //citation
            /**

             */
            //echo '<tr data-metadata="citation"><th>Citaci&oacute;n:</th><td>';
            //foreach ($relatedItems as $relatedItem){        
            //    if($relatedItem->attributes()->type=="host"){        
            //        if($relatedItem->children("mods", true)->part!=null && $relatedItem->children("mods", true)->part!=''){
            //            echo $relatedItem->children("mods", true)->part[0]->children("mods", true)->text;
            //        }
            //    }
            //}
            //echo '<td></tr>';
            
            
            // is part of
            echo '<tr data-metadata="isparofseries"><th>Hace parte de:</th><td>';
            foreach ($relatedItems as $relatedItem) {
                if ($relatedItem->attributes()->type == "series") {
                    echo $relatedItem;
                }
            }
            echo '<td></tr>';
            echo '</table>';

            
            
            // Prints the list of original files
            // url of original files
            if ($files->count() > 0) {
                foreach ($files as $fileGrp) {
                    if (($fileGrp['USE'] == "ORIGINAL") && $fileGrp->file->count()>0) {
                        echo '<table id="record-original-files">';
                        echo '<tr class="header-row">'
                            .'<th>Ficheros</th>'
                            .'<th>Formato</th>'
                            //.'<th>Ver</th>'
                            .'</tr>';
                            foreach ($fileGrp as $file){
                                $retrieveUrl = $file->FLocat[0]->attributes("xlink", true)->href;
                                $parts = explode("/", $retrieveUrl);
                                echo '<tr>'
                                    .'<td><a href="'.$retrieveUrl.'">'.  end($parts).'</a></td>'
                                    .'<td>'.$file['MIMETYPE'].'</td>'
                                    //.'<td>Ver</td>'
                                    .'</tr>';
                            }
                        echo '</table>';
                    }
                }
            }
            
            // Prints the view complete button
            foreach ($identifiers as $recordId) {
                if ($recordId->attributes()->type == "uri") {
                    echo '<a class="app_navlink" href="' . $recordId . '" target="_blank" title="Ver el registro completo el repositorio Vitela de la universidad Javeriana">Ver el registro completo</a>';
                }
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo 'Error: ' . $xmlResponse->{"error"};
        }
    } catch (Exception $ex) {
        echo $ex;
    }
} else {
    echo "Error - The OAI server is not responding";
}
?>
