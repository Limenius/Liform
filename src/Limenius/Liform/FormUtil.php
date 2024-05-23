<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class FormUtil
{
    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public static function typeAncestry(FormInterface $form)
    {
        $types = [];
        self::typeAncestryForType($types, $form->getConfig()->getType());

        return $types;
    }

    /**
     * @param ResolvedFormTypeInterface $formType
     * @param array                     $types
     *
     * @return void
     */
    public static function typeAncestryForType(array &$types, ResolvedFormTypeInterface $formType = null): void
    {
        if (!($formType instanceof ResolvedFormTypeInterface)) {
            return;
        }

        $types[] = $formType->getBlockPrefix();

        self::typeAncestryForType($types, $formType->getParent());
    }

    /**
     * Returns the dataClass of the form or its parents, if any
     *
     *
     * @return string|null the dataClass
     */
    public static function findDataClass(mixed $formType)
    {
        if ($dataClass = $formType->getConfig()->getDataClass()) {
            return $dataClass;
        } else {
            if ($parent = $formType->getParent()) {
                return self::findDataClass($parent);
            } else {
                return null;
            }
        }
    }

    /**
     * @param FormInterface $form
     *
     * @return boolean
     */
    public static function isTypeInAncestry(FormInterface $form, mixed $type)
    {
        return in_array($type, self::typeAncestry($form));
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     */
    public static function type(FormInterface $form)
    {
        return $form->getConfig()->getType()->getName();
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     */
    public static function label(FormInterface $form)
    {
        return $form->getConfig()->getOption('label', $form->getName());
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     */
    public static function isCompound(FormInterface $form)
    {
        return $form->getConfig()->getOption('compound');
    }
}
