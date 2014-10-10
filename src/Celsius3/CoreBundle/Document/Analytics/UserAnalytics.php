<?php

namespace Celsius3\CoreBundle\Document\Analytics;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="celsius_analytics")
 */
class UserAnalytics extends Analytics
{
    /**
     * @ODM\Int
     */
    private $year;
    /**
     * Este campo contiene un array asociativo de 
     * meses que a su vez contiene un array asociativo 
     * de tipos de usuarios
     * 
     * @ODM\Field(type="collection")
     */
    private $counters;
    
    function getYear()
    {
        return $this->year;
    }

    function getCounters()
    {
        return $this->counters;
    }

    function setYear($year)
    {
        $this->year = $year;
    }

    function setCounters($counters)
    {
        $this->counters = $counters;
    }
}