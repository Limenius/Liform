<?php


namespace Limenius\Liform\Form\Extension;


use Limenius\Liform\Serializer\Normalizer\FormViewNormalizer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * This Extension exists to add a var to the CollectionType so that the FormViewNormalizer can serialise the data
 * ready for the redux-form ArrayWidget.
 */
class CollectionTypeExtension extends AbstractTypeExtension
{

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars[FormViewNormalizer::NORMALIZATION_STRATEGY] = FormViewNormalizer::CHILDREN_AS_ARRAY;
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return CollectionType::class;
    }
}
