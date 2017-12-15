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

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalize instances of FormView
 *
 * @author Nacho Martín <nacho@limenius.com>
 */
class InitialValuesNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($form, $format = null, array $context = [])
    {
        $formView = $form->createView();
        return $this->getValues($form, $formView);
    }

    private function getValues(Form $form, FormView $formView)
    {
        if (!empty($formView->children)) {
            // Force serialization as {} instead of []
            $data = (object) array();
            foreach ($formView->children as $name => $child) {
                // Skip empty values because
                // https://github.com/erikras/redux-form/issues/2149
                if (empty($child->children) && ($child->vars['value'] === null || $child->vars['value'] === '')) {
                    continue;
                }
                $data->{$name} = $this->getValues($form, $child);
            }

            return $data;
        } else {
            // handle separatedly the case with checkboxes, so the result is
            // true/false instead of 1/0
            if (isset($formView->vars['checked'])) {
                return $formView->vars['checked'];
            }

            return $formView->vars['value'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Form;
    }
}
