<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

interface ExtensionInterface
{
    /**
     * @param FormInterface $form
     * @param array         $schema
     *
     * @return array
     */
    public function apply(FormInterface $form, array $schema);
}
