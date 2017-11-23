<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

class IntegerTransformer extends AbstractTransformer
{
    /**
     * @inheritdoc
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $schema = ['type' => 'integer'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}
