<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Transformer;

use Limenius\Liform\ResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class CompoundTransformer extends AbstractTransformer
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @param TranslatorInterface           $translator
     * @param ResolverInterface             $resolver
     * @param FormTypeGuesserInterface|null $validatorGuesser
     */
    public function __construct(TranslatorInterface $translator, ResolverInterface $resolver, FormTypeGuesserInterface $validatorGuesser = null)
    {
        parent::__construct($translator, $validatorGuesser);
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], ?string $widget = null): array
    {
        $data = [];
        $order = 1;
        $required = [];

        $formItems = $form->all();
        uasort($formItems, static fn($a, $b): int => $a->getConfig()->getOption('priority') <=> $b->getConfig()->getOption('priority'));

        foreach ($formItems as $name => $field) {
            $transformerData = $this->resolver->resolve($field);
            $transformedChild = $transformerData['transformer']->transform($field, $extensions, $transformerData['widget']);
            $transformedChild['propertyOrder'] = $order;
            $data[$name] = $transformedChild;
            $order++;

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
