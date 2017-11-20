<?php
/*
 * This file was originally written as part of the FOSRestBundle package.
 *
 * But we need to do this here without FOSRestBundle present, so...
 *
 */
namespace Limenius\Liform\Serializer\Normalizer;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Normalizes invalid Form instances.
 *
 * @author Ener-Getick <egetick@gmail.com>
 */
class FormErrorNormalizer implements NormalizerInterface
{
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'code' => isset($context['status_code']) ? $context['status_code'] : null,
            'message' => 'Validation Failed',
            'errors' => $this->convertFormToArray($object),
            'redux_form_errors' => $this->convertFormToReduxFormErrorsArray($object),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormInterface && $data->isSubmitted() && !$data->isValid();
    }

    /**
     * same as convertFormtoArray but fit with redux form intented data      
     */
    private function convertFormToReduxFormErrorsArray(FormInterface $data){
        
        $form = $errors = [];
        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }
        if (!empty($errors)) {
            $form = $errors;
        }
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                $result = $this->convertFormToReduxFormErrorsArray($child);
                if($result){
                    $form[$child->getName()] = $this->convertFormToReduxFormErrorsArray($child);
                }
                
            }
        }
        
        if(empty($form)){
            return false;
        }
        return $form;
        
    }


    /**
     * This code has been taken from JMSSerializer.
     */
    private function convertFormToArray(FormInterface $data)
    {
        $form = $errors = [];
        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }
        if ($errors) {
            $form['errors'] = $errors;
        }
        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof FormInterface) {
                $children[$child->getName()] = $this->convertFormToArray($child);
            }
        }
        if ($children) {
            $form['children'] = $children;
        }

        return $form;
    }
    private function getErrorMessage(FormError $error)
    {
        if (null !== $error->getMessagePluralization()) {
            return $this->translator->transChoice($error->getMessageTemplate(), $error->getMessagePluralization(), $error->getMessageParameters(), 'validators');
        }

        return $this->translator->trans($error->getMessageTemplate(), $error->getMessageParameters(), 'validators');
    }
}
