<?php

namespace App\Form;

use App\Entity\EmailProcessLog;
use App\Entity\EmailReceiver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailProcessLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('senderEmail')
            ->add('subject')
            ->add('body')
            ->add('responseStatus')
            ->add('errorMessage')
            ->add('emailReceiver', EntityType::class, [
                'class' => EmailReceiver::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmailProcessLog::class,
        ]);
    }
}
