<?php

namespace Limenius\Liform;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\Transformer\CompoundTransformer;

class Liform
{
    private $resolver;

    private $extensions = [];

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function transform(FormInterface $form)
    {
        $transformerData = $this->resolver->resolve($form);
        return $transformerData['transformer']->transform($form, $this->extensions, $transformerData['format']);
    }

    public function addExtension($extension)
    {
        $this->extensions[] = $extension;
        return $this;
    }
}
