<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform;

use Limenius\Liform\Transformer\ExtensionInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
interface LiformInterface
{
    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function transform(FormInterface $form);

    /**
     * @param ExtensionInterface $extension
     *
     * @return LiformInterface
     */
    public function addExtension(ExtensionInterface $extension);
}
