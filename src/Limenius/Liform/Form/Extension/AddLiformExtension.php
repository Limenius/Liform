<?php
namespace Limenius\Liform\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormTypeExtensionInterface;

/**
 * Class: AddLiformExtension
 *
 * @see AbstractTypeExtension
 */
class AddLiformExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * Add the liform option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('liform'));
    }
}
