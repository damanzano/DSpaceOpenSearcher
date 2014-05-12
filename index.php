<?php include_once 'view/HtmlSearchOptions.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Buscador Archivo del Patrimonio Fotográfico y Fílmico del Valle del Cauca</title>        
        <link rel="stylesheet" href="css/smoothness/jquery-ui-1.8.16.custom.css" />
        <link rel="stylesheet" href="css/search.css" />
        <!-- <link rel="stylesheet" href="css/jquery.mobile-1.0.min.css" />-->
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        <!-- <script type="text/javascript" src="js/jquery.mobile-1.0.min.js"></script>-->
        <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
        <script type="text/javascript" src="js/searcher.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23288160-39']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    </head>
    <body>
        <div id="wrapper" data-role="page" class="ui-widget">
            <div id="header" data-role="header" class="ui-widget-header ">
                <h2>
                    Buscador Archivo del Patrimonio Fotogr&aacute;fico y F&iacute;lmico del Valle del Cauca
                </h2>
            </div>

            <div id="widecontent" data-role="content" class="ui-widget-content ui-corner-all">
                <div id="content-header">                   
                    <div id="searcher">
                        <form id="dspaceSearchForm" target="_blank" action="" method="post" name="dspaceSearchForm" >
                            <table width="100%" border="0">
                                <tr>
                                    <td><input width="100%" size="80" type="text" value="Digita el tema a buscar..." id="query" name="query" style="color:#cccccc;" class="ui-input-search"/></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="rpp">Resultados por p&aacute;gina</label>
                                        <select name="rpp" id="rpp">
                                            <option value="5">5</option>
                                            <option selected="selected" value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="30">30</option>
                                            <option value="35">35</option>
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                            <option value="50">50</option>
                                            <option value="55">55</option>
                                            <option value="60">60</option>
                                            <option value="65">65</option>
                                            <option value="70">70</option>
                                            <option value="75">75</option>
                                            <option value="80">80</option>
                                            <option value="85">85</option>
                                            <option value="90">90</option>
                                            <option value="95">95</option>
                                            <option value="100">100</option>
                                        </select>


                                        <label for="sort_by">Ordenar por</label>
                                        <select name="sort_by" id="sort_by">
                                            <option value="score">Relevancia</option>
                                            <option value="dc.title_sort">Titulo</option> 
                                            <option value="dc.date.issued_dt">Fecha de Publicaci&oacuten</option>
                                        </select>
                                        
                                       
                                        <label for="order">En orden</label>
                                        <select name="order" id="order">
                                            <option value="ASC">Ascendente</option>
                                            <option selected="selected" value="DESC">Descendente</option>
                                        </select>

                                        <label for="start">A partir del registro</label>
                                        <select name="start" id="start">
                                            <option selected="selected" value="0">Todo</option>
                                            <option value="1">1</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="15">15</option>
                                            <option value="20">20</option>
                                            <option value="25">25</option>
                                            <option value="30">30</option>
                                            <option value="35">35</option>
                                            <option value="40">40</option>
                                            <option value="45">45</option>
                                            <option value="50">50</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
<!--                                        <input type="button" value="Todo el archivo fotogr&aacute;fico" id="affvc" name="affvc" />
                                        <input type="button" value="Fondo Archivo del Patrimonio Fotofr&aacute;fico y F&iacute;lmico del Valle del Cauca" id="fapffvc" name="affvc" />
                                        <input type="button" value="Fondo Cali Ciudad Visible" id="fccv" name="affvc" />-->
                                        <select name="scope_community" id="scope_community"><?php echo HtmlSearchOptions::communityScopes("10906/5698", false, true, 2, false) ?></select>
                                        <select name="scope_categories" id="10906_5699" style="display: none;"><?php echo "<pre>" . HtmlSearchOptions::communityScopes("10906/5699", true, true, -1, false) . "</pre>" ?></select>
                                        <select name="scope_categories" id="10906_5776" style="display: none;"><?php echo "<pre>" . HtmlSearchOptions::communityScopes("10906/5776", true, true, -1, false) . "</pre>" ?></select>
                                        <select name="scope_categories" id="10906_65426" style="display: none;"><?php echo "<pre>" . HtmlSearchOptions::communityScopes("10906/65426", true, true, -1, false) . "</pre>" ?></select>
                                        <input type="button" value="Buscar" id="search" name="search" />
                                    </td>


                                </tr>
                            </table>
                        </form>
                    </div>
                </div>          
            </div>
			<div id="searchnote" data-role="content"></div>
            <div id="appresults" data-role="content">

            </div>

            <div id="apprecord" data-role="content">
                <a class="back" href="#">volver</a>
            </div> 

            <div id="footer" class="ui-widget-content ui-corner-all">
                <h3>Powered by <a href="http://www.icesi.edu.co" title="Universidad Icesi">Universidad Icesi</a></h3>
            </div>
        </div>

        <!--         
        <div id="logos">
            <img id="logo_dpt" src="images/bibdpt.png" alt="Bilioteca Departamental Jorge Garc&eacute;s Borrero" />
            <a href="http://bibliotecadigital.icesi.edu.co/biblioteca_digital/"><img id="logo_icesi" src="images/logo_icesi.png" alt="Biblioteca Digital - Universidad Icesi"/></a>
        </div>
        -->
    </body>
</html>