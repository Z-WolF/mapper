<?php

namespace App\Form\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\EntityFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeatureGameFilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'value_type_options' => [
                'class' => Game::class,
            ]
        ]);
    }

    public function getParent()
    {
        return EntityFilterType::class;
    }
}
