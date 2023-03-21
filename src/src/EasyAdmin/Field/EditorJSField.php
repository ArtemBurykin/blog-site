<?php

namespace App\EasyAdmin\Field;

use App\EasyAdmin\FormType\EditorjsType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

/**
 * The Editor Js field for the admin panel.
 */
class EditorJSField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(EditorjsType::class);
    }
}
