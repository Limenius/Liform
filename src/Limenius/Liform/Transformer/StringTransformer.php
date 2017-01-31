<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;

class StringTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, $extensions = [], $format = null)
    {
        $schema = [
            'type' => 'string',
        ];


        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['format']) && $format = $liform['format']) {
                $schema['format'] = $format;
            }
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }

}
