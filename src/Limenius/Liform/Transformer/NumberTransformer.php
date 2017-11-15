<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

class NumberTransformer extends AbstractTransformer
{
    /**
     * @inheritdoc
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $schema = ['type' => 'number'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}
