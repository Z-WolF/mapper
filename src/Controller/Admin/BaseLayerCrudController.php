<?php

namespace App\Controller\Admin;

use App\Entity\BaseLayer;
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
