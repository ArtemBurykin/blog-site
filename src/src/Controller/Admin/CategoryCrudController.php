<?php

namespace App\Controller\Admin;

use App\Entity\Blog\Category;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Main information');
        yield TextField::new('title')->setRequired(true);
        yield TextField::new('seoUrl')->setRequired(true);
        yield TextareaField::new('description')->hideOnIndex();
        yield ImageField::new('mainImage')
            ->hideOnIndex()
            ->setUploadDir('public/uploads/category')
            ->setBasePath('uploads/category');

        yield FormField::addTab('Meta information');
        yield TextField::new('metaTitle')->hideOnIndex();
        yield TextareaField::new('metaDescription')->hideOnIndex();

        yield FormField::addTab('Open graph information');
        yield TextField::new('ogTitle')->hideOnIndex();
        yield TextareaField::new('ogDescription')->hideOnIndex();
        yield ImageField::new('ogImage')
            ->hideOnIndex()
            ->setUploadDir('public/uploads/category')
            ->setBasePath('uploads/category');
    }
}
