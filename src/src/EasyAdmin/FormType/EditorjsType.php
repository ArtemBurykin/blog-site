<?php

namespace App\EasyAdmin\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The form type for the editorjs field.
 */
class EditorjsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if ($value === null) {
                    return [];
                }

                $data = json_decode($value, true);

                if (is_array($data)) {
                    return $data;
                }

                return [];
            },
            function ($value) {
                return $value;
            },
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'compound' => false,
            ]);
    }
}

