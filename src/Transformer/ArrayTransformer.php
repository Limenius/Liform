<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\Exception\TransformerException;

class ArrayTransformer extends AbstractTransformer
{
    public function __construct($resolver) {
        $this->resolver = $resolver;
    }

    public function transform(FormInterface $form, $extensions = [], $format = null)
    {
        $children = [];

        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions, $transformerData['format']);
            $children[] = $transformedChild;

            if ($transformerData['transformer']->isRequired($field)) {
                $required[] = $field->getName();
            }
        }

        if (empty($children)) {
            $entryType = $form->getConfig()->getAttribute('prototype');
            if (!$entryType) {
                throw new TransformerException( 'Liform cannot infer the json-schema representation of a an empty Collection or array-like type without the option "allow_add" (to check the proptotype). Evaluating "'.$form->getName().'"');
            }
            $transformerData = $this->resolver->resolve($entryType);
            $children[] = $transformerData['transformer']->transform($entryType, $extensions, $transformerData['format']);
            $children[0]['title'] = 'prototype';
        }

        $schema =[
            'type' => 'array',
            'title' => $form->getConfig()->getOption('label'),
            'items' => $children[0]
        ];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
