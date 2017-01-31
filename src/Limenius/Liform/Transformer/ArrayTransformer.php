<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\Exception\TransformerException;
use Limenius\Liform\Resolver;

/**
 * Class: ArrayTransformer
 *
 * @see AbstractTransformer
 */
class ArrayTransformer extends AbstractTransformer
{
    /**
     * __construct
     *
     * @param mixed $resolver
     */
    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * transform
     *
     * @param FormInterface $form
     * @param mixed         $extensions
     * @param mixed         $format
     *
     * @return array
     */
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
                throw new TransformerException('Liform cannot infer the json-schema representation of a an empty Collection or array-like type without the option "allow_add" (to check the proptotype). Evaluating "'.$form->getName().'"');
            }
            $transformerData = $this->resolver->resolve($entryType);
            $children[] = $transformerData['transformer']->transform($entryType, $extensions, $transformerData['format']);
            $children[0]['title'] = 'prototype';
        }

        $schema = [
            'type' => 'array',
            'title' => $form->getConfig()->getOption('label'),
            'items' => $children[0],
        ];

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $format);

        return $schema;
    }
}
