<?php

namespace App\Controller\Admin;

use App\Entity\BaseLayer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class BaseLayerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BaseLayer::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::BATCH_DELETE)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('game'),
            TextField::new('name'),
            UrlField::new('url')->hideOnIndex(),
            IntegerField::new('maxZoom'),
            BooleanField::new('enabled')->renderAsSwitch(false)
        ];
    }
}
