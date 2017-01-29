<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;

class NumberTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, $extensions = [], $format = null)
    {
        $schema = [
            'type' => 'number',
        ];
        if ($liform = $form->getConfig()->getOption('liform')) {
            if ($format = $liform['format']) {
                $schema['format'] = $format;
            }
        }
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
