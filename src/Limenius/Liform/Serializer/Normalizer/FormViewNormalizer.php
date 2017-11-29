<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Serializer\Normalizer;

use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalize instances of FormView
 *
 * @author Nacho Martín <nacho@limenius.com>
 */
class FormViewNormalizer implements NormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!empty($object->children)) {
            // Force serialization as {} instead of []
            $form = (object) array();
            foreach ($object->children as $name => $child) {
                // Skip empty values because
                // https://github.com/erikras/redux-form/issues/2149
                if (empty($child->children) && ($child->vars['value'] === null || $child->vars['value'] === '')) {
                    continue;
                }
                $form->{$name} = $this->normalize($child);
            }

            return $form;
        } else {
            // handle separatedly the case with checkboxes, so the result is
            // true/false instead of 1/0
            if (isset($object->vars['checked'])) {
                return $object->vars['checked'];
            }

            return $object->vars['value'];
        }
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormView;
    }
}
