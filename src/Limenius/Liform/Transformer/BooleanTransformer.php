<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;

class BooleanTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, $extensions = [], $format = null)
    {
        $schema = ['type' => 'boolean'];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
