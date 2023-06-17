<?php

namespace App\Controller\Admin;

use App\Entity\GameConfig;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GameConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GameConfig::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'defaultZoom',
            'minZoom',
            'maxZoom',
            TextField::new('defaultCenter'),
            AssociationField::new('defaultBaseLayer'),
            NumberField::new('scaleX'),
            NumberField::new('scaleY'),
            NumberField::new('offsetX'),
            NumberField::new('offsetY'),
        ];
    }
}
