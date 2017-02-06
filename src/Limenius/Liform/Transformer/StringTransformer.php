<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

/**
 * Class: StringTransformer
 *
 * @see AbstractTransformer
 */
class StringTransformer extends AbstractTransformer
{
    /**
     * transform
     *
     * @param FormInterface $form
     * @param array         $extensions
     * @param srting|null   $widget
     *
     * @return array
     */
    public function transform(FormInterface $form, $extensions = [], $widget = null)
    {
        $schema = [
            'type' => 'string',
        ];


        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['widget']) && $widget = $liform['widget']) {
                $schema['widget'] = $widget;
            }
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}
