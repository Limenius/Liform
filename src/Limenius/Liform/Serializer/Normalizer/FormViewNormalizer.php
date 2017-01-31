<?php
namespace Limenius\Liform\Serializer\Normalizer;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Limenius\LiformBundle\Liform\FormUtil;

/**
 * Class: FormViewNormalizer
 *
 * @see NormalizerInterface
 */
class FormViewNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!empty($object->children)) {
            $form = [];
            foreach ($object->children as $name => $child) {
                $form[$name] = $this->normalize($child);
            }

            return $form;
        } else {

            return $object->vars['value'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormView;
    }
}
