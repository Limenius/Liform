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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Limenius\Liform\FormUtil;

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
    public function normalize($object, $format = null, array $context = []): float|array|\ArrayObject|bool|int|string|null
    {
        $formView = $object->createView();

        return $this->getValues($object, $formView);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Form;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(?string $format): array
    {
        return [Form::class => true];
    }

    private function getValues(FormInterface $form, FormView $formView): mixed
    {
        if (!empty($formView->children)) {
            if (in_array('choice', FormUtil::typeAncestry($form)) &&
                $formView->vars['expanded']
            ) {
                if ($formView->vars['multiple']) {
                    return $this->normalizeMultipleExpandedChoice($formView);
                }

                return $this->normalizeExpandedChoice($formView);
            }
            // Force serialization as {} instead of []
            $data = (object) [];
            foreach ($formView->children as $name => $child) {
                // Avoid unknown field error when csrf_protection is true
                // CSRF token should be extracted another way
                if ($form->has($name)) {
                    $data->{$name} = $this->getValues($form->get($name), $child);
                }
            }

            return (array) $data;
        }

        // handle separatedly the case with checkboxes, so the result is
        // true/false instead of 1/0
        if (isset($formView->vars['checked'])) {
            return $formView->vars['checked'];
        }

        return $formView->vars['value'];
    }

    private function normalizeMultipleExpandedChoice(FormView $formView): array
    {
        $data = [];
        foreach ($formView->children as $name => $child) {
            if ($child->vars['checked']) {
                $data[] = $child->vars['value'];
            }
        }

        return $data;
    }

    private function normalizeExpandedChoice(FormView $formView): mixed
    {
        foreach ($formView->children as $name => $child) {
            if ($child->vars['checked']) {
                return $child->vars['value'];
            }
        }

        return null;
    }
}
