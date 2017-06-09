<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\FormUtil;

/**
 * Class: CompoundTransformer
 *
 * @see AbstractTransformer
 */
class CompoundTransformer extends AbstractTransformer
{
    protected $resolver;

    /**
     * __construct
     *
     * @param mixed $translator
     * @param mixed $validatorGuesser
     * @param mixed $resolver
     */
    public function __construct($translator, $validatorGuesser, $resolver)
    {
        parent::__construct($translator, $validatorGuesser);
        $this->resolver = $resolver;
    }

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
        $data = [];
        $order = 1;
        $required = [];
        foreach ($form->all() as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions, $transformerData['widget']);
            $transformedChild['propertyOrder'] = $order;
            $data[$name] = $transformedChild;
            $order ++;

            if ($transformerData['transformer']->isRequired($field)) {
                $required[] = $field->getName();
            }
        }
        $schema = [
            'title' => $form->getConfig()->getOption('label'),
            'type' => 'object',
            'properties' => $data,
        ];

        if (!empty($required)) {
            $schema['required'] = $required;
        }
        $innerType = $form->getConfig()->getType()->getInnerType();

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        if (method_exists($innerType, 'buildLiform')) {
            $schema = $innerType->buildLiform($form, $schema);
        }

        return $schema;
    }
}
