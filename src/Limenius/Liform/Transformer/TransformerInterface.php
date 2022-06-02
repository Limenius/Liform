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

use Symfony\Component\Form\FormInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
interface TransformerInterface
{
    /**
     * @param FormInterface $form
     * @param ExtensionInterface[] $extensions
     * @param string|null $widget
     *
     * @return array
     */
    public function transform(FormInterface $form, array $extensions = [], ?string $widget = null): array;
}
