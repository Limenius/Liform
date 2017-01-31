<?php

namespace Limenius\Liform;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\Transformer\CompoundTransformer;

/**
 * Class: Liform
 *
 */
class Liform
{
    private $resolver;

    private $extensions = [];

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function transform(FormInterface $form)
    {
        $transformerData = $this->resolver->resolve($form);

        return $transformerData['transformer']->transform($form, $this->extensions, $transformerData['format']);
    }

    /**
     * @param mixed $extension
     *
     * @return Liform
     */
    public function addExtension($extension)
    {
        $this->extensions[] = $extension;

        return $this;
    }
}
