<?php

namespace Celsius3\CoreBundle\Manager;

use Celsius3\CoreBundle\Entity\Instance;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\MarkerImage;

class MapManager
{
    public function getCiudades($provincia)
    {
    }

    /**
     * @param $ciudad
     * @param null $tipo
     *
     * @return array
     */
    public function getResultados($ciudad, $orden, $tipo = null)
    {
    }

    public function search($orden, $nombre = null, $tipo = null)
    {
    }

    private function addMarker(Map $map, Instance $instance, $windowOpen = false)
    {
        if (!$instance->getLatitud() || !$instance->getLongitud()) {
            return;
        }

        $markerImage = new MarkerImage();

        $infoWindow = new InfoWindow();
        $infoWindow->setOpen($windowOpen);
        $infoWindow->setContent(sprintf(
            '<p><h4>%s - %s</h4></p><p>%s</p>', $instance->getName(),$instance->getAbbreviation(),
            $instance->getWebsite()
        ));


        $marker = new Marker();
        $marker->setIcon($markerImage);
        $marker->setPosition((float) $instance->getLatitud(), (float) $instance->getLongitud());
        $marker->setInfoWindow($infoWindow);
        $map->addMarker($marker);
        $map->setCenter((float) $instance->getLatitud(), (float) $instance->getLongitud());
    }

    /**
     * @param $instancia
     *
     * @return Map
     */
    public function createMap($instancias, $windowOpen = false)
    {
        $map = new Map();
        $map->setAutoZoom(true);
        $map->setAsync(true);
        /* @var $instance */
        foreach ($instancias as $instancia) {
            $this->addMarker($map, $instancia, $windowOpen);
        }
        $map->setStylesheetOptions(array(
            'width' => '100%',
            'height' => '1000px',
        ));

        return $map;
    }

    /**
     * @param $instancias
     * @param $latitude
     * @param $longitude
     *
     * @return Map
     *
     * @throws \Ivory\GoogleMap\Exception\MapException
     * @throws \Ivory\GoogleMap\Exception\OverlayException
     */
    public function createMapFromApiSearch($instancias, $latitude, $longitude)
    {
        $map = $this->createMap($instancias);
        // my position
        $myPosition = new Marker();
        $myPosition->setPosition($latitude, $longitude);
        $map->addMarker($myPosition);

        return $map;
    }
}
