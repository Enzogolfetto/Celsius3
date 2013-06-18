<?php

namespace Celsius3\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Celsius3\CoreBundle\Helper\ConfigurationHelper;

class ConfirmationType extends AbstractType
{

    private $configuration_helper;

    public function __construct(ConfigurationHelper $configuration_helper)
    {
        $this->configuration_helper = $configuration_helper;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
                ->setDefaults(
                        array(
                                'choices' => $this->configuration_helper
                                        ->confirmation, 'required' => true,
                                'expanded' => true,));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'celsius3_core.confirmation_type';
    }

}
