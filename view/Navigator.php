<?php

/**
 * This Class generate html navigation links for a result list.
 *
 * @author David Andrés Manzano Herrera - damanzano
 * @since 2015-12-16
 */
class Navigator {

    private $startIndex;
    private $page;
    private $pages;
    private $rpp;
    private $size;
    private $query;

    /**
     * This method set all the attributes for the navigator.
     *
     * @author damanzano
     * @since 2015-12-16
     * @param int $startIndex 
     * @param int $rpp
     * @param int $size
     * @param string $query
     */
    public function setAttributes($startIndex, $rpp, $size, $query) {
        $this->startIndex = $startIndex;
        $this->rpp = $rpp;
        $this->size = $size;
        $this->query = $query;
        $this->pages = ceil($this->size / $this->rpp);
        $this->page = ceil($this->startIndex / $this->rpp) + 1;
    }
    
    /**
     * This display the navigator in html format.
     *
     * @author damanzano
     * @since 2015-12-16     
     */
    public function render() {
        $html = '<div class="navigation" data-role="navbar">';
        if ($this->size <= 0) {
            $html = '</div>';
            return $html;
        }
        $numpag = 0;
        $html.='<table border="0" align="center" class="app_navlinks"><tr>';

        /* The initial and before buttons are generated */
        $style_before = 'class="app_navlink_disabled"';
        $style_begining = 'class="app_navlink_disabled"';
        $image_type = '_disabled';
        if ($this->page > 1) {
            $style_begining = 'class="app_navlink" title="Inicio" onclick="loadBDResults(\'0\',\'' . $this->rpp . '\', \'' . $this->query . '\', \'true\');"';
            $style_before = 'class="app_navlink" title="Anterior" onclick="loadBDResults(\'' . ($this->startIndex - $this->rpp) . '\',\'' . $this->rpp . '\', \'' . $this->query . '\', \'true\');"';
            $image_type = '';
        }
        $html.= '<td width="10%" align="left">';
        $html.= '<table><tr>';
        //$html.= '<td><a ' . $style_begining . '><img src="images/start' . $image_type . '.png" alt="Inicio" width="20" height="20"></a></td>';
        //$html.= '<td><a ' . $style_before . '><img src="images/previous' . $image_type . '.png" alt="Anterior" title="Anterior" width="20" height="20"></a></td>';
        
        $html.= '<td><a ' . $style_begining . '><span>Inicio</span></a></td>';
        $html.= '<td><a ' . $style_before . '><span>Anterior</span></a></td>';
        $html.= '</tr></table>';
        $html.= '</td>';

        /* Se Generan las páginas */
        $html.= '<td width="80%" align="center">';
        $html.= '<table align="center"><tr>';
        $diferencia_inicio = (($this->page - 5) < 0) ? abs(($this->page - 5)) : 0;
        $diferencia_final = (($this->page + 5) > $this->pages) ? abs(($this->page + 5) - $this->pages) : 0;
        for ($i = 0; $i < $this->pages; $i++) {
            $claseactual = '';
            $numpag = $i + 1;
            if ($numpag == $this->page) {
                $claseactual = '_active';
            }
            if (($numpag <= $this->pages) && (($numpag >= ($this->page - 4 - $diferencia_final)) && ($numpag <= ($this->page + 4 + $diferencia_inicio)))) {
                $html.= '<td><a class="app_navlink' . $claseactual . '" onclick="loadBDResults(\'' . ($i * $this->rpp) . '\',\'' . $this->rpp . '\', \'' . $this->query . '\', \'true\');">' . $numpag . '</a></td>';
            }
        }
        $html.= '</tr></table>';
        $html.= '</td>';


        /* Se generan los botones siguiente y fin */
        $style_siguiente = 'class="app_navlink_disabled"';
        $style_fin = 'class="app_navlink_disabled"';
        $tipo_imagen_fin = '_disabled';
        $modulo_pag = ($this->size % $this->rpp);
        if ($modulo_pag == 0) {
            $modulo_pag = $this->rpp;
        }
        if ($this->page < $this->pages) {
            $style_siguiente = 'class="app_navlink" title="Siguiente" onclick="loadBDResults(\'' . ($this->startIndex + $this->rpp) . '\',\'' . $this->rpp . '\', \'' . $this->query . '\', \'true\');"';
            $style_fin = 'class="app_navlink" title="Fin" onclick="loadBDResults(\'' . (($this->pages - 1) * $this->rpp) . '\',\'' . $this->rpp . '\', \'' . $this->query . '\');"';
            $tipo_imagen_fin = '';
        }

        $html.= '<td width="10%" align="right">';
        $html.= '<table><tr>';
        //$html.= '<td><a ' . $style_siguiente . '><img src="images/next' . $tipo_imagen_fin . '.png" alt="Siguiente" title="Siguiente" width="20" height="20"></a></td>';
        //$html.='<td><a ' . $style_fin . '><img src="images/end' . $tipo_imagen_fin . '.png" alt="Fin" title="Fin" width="20" height="20"></a></td>';
        
        $html.= '<td><a ' . $style_siguiente . '><span>Siguiente</span></a></td>';
        $html.='<td><a ' . $style_fin . '><span>Fin</span></a></td>';
        $html.= '</tr></table>';
        $html.= '</td>';

        $html.= '</tr></table>';

        /* indicador de página y resultados */
        $end = ($this->startIndex + $this->rpp);
        if ($end > $this->size) {
            $end = $this->size;
        }
        $html.= '<table table border="0" align="center" class="app_navlinks">';
        $html.= '<tr><td align="center">P&aacute;gina ' . $this->page . ' de ' . $this->pages . '</td></tr>';
        $html.= '<tr><td align="center">Registros ' . ($this->startIndex + 1) . ' a ' . ($end) . ' de <strong>' . $this->size . '</strong> en total</td></tr>';
        $html.= '</table>';
        $html.= '</div>';
        return $html;
    }

}

?>
