<?php

namespace Celsius3\MigrationBundle\Helper;
use Celsius3\CoreBundle\Document\Country;

class CountryHelper
{

    private $container;
    private $dm;
    private $cityHelper;
    private $institutionHelper;

    public function __construct($container, CityHelper $cityHelper,
            InstitutionHelper $institutionHelper)
    {
        $this->container = $container;
        $this->dm = $container->get('doctrine.odm.mongodb.document_manager');
        $this->cityHelper = $cityHelper;
        $this->institutionHelper = $institutionHelper;
    }

    public function migrate($conn)
    {
        $query_paises = 'SELECT * FROM paises WHERE Abreviatura <> ""';
        $result_paises = mysqli_query($conn, $query_paises);

        while ($row_pais = mysqli_fetch_assoc($result_paises)) {
            $country = new Country();
            $country
                    ->setName(mb_convert_encoding($row_pais['Nombre'], 'UTF-8'));
            $country->setAbbreviation($row_pais['Abreviatura']);

            $this->dm->persist($country);

            $this->container->get('celsius3_migration.migration_manager')
                    ->createAssociation($country->getName(), $row_pais['Id'],
                            'paises', $country);

            $this->cityHelper->migrate($conn, $row_pais['Id'], $country);

            $this->institutionHelper
                    ->migrate($conn, $row_pais['Id'], $country, 0);
        }
        $this->dm->flush();
    }

}
