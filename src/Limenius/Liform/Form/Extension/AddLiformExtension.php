<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Adds a 'liform' configuration option to instances of FormType
 *
 * @author Nacho Martín <nacho@limenius.com>
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
     * Gets the extended types.
     *
     * @return iterable
     */
    public static function getExtendedTypes()
    {
        return [FormType::class];
    }

    /**
     * Add the liform option
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['liform']);
    }
}
