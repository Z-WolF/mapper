<?php

namespace App\Controller\Admin;

use App\Entity\FeatureCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FeatureCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeatureCategory::class;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
//            ->setDefaultSort(['id' => 'ASC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
//            IdField::new('id'),
            TextField::new('name'),
            AssociationField::new('parent'),
            AssociationField::new('game'),
            IntegerField::new('minZoom'),
            IntegerField::new('maxZoom'),
            ColorField::new('color'),
            BooleanField::new('enabled')->renderAsSwitch(false),
            BooleanField::new('label')->renderAsSwitch(false),
        ];
    }

}
