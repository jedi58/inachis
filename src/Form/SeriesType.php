<?php

namespace App\Form;

use App\Entity\Series;
use App\Form\DataTransformer\ArrayCollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SeriesType extends AbstractType
{
    private $router;
    private $transformer;
    private $translator;

    public function __construct(
        TranslatorInterface $translator,
        RouterInterface $router,
        ArrayCollectionToArrayTransformer $transformer
    ) {
        $this->router = $router;
        $this->translator = $translator;
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'aria-labelledby'  => 'title_label',
                    'aria-required'    => 'true',
                    'data-tip-content' => '<strong>Required.</strong>',
                    'autofocus'        => 'true',
                    'class'            => 'text',
                    'placeholder'      => $this->translator->trans('admin.placeholder.series.title', [], 'messages'),
                ],
                'label'      => $this->translator->trans('admin.label.series.title', [], 'messages'),
                'label_attr' => [
                    'id' => 'title_label',
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'aria-labelledby' => 'description_label',
                    'aria-required'   => 'false',
                    'class' => 'mde_editor',
                ],
                'label'      => $this->translator->trans('admin.label.series.description', [], 'messages'),
                'label_attr' => [
                    'class' => 'hidden',
                    'id'    => 'description_label',
                ],
                'required' => false,
            ])
            ->add('firstDate', DateTimeType::class, [
                'attr' => [
                    'aria-labelledby'  => 'firstDate_label',
                    'aria-required'    => 'false',
                    'data-tip-content' => $this->translator->trans('admin.tip.series.firstDate', [], 'messages'),
                    'data-tip-title'   => $this->translator->trans('admin.tip.title.series.firstDate', [], 'messages'),
                    'readOnly' => true,
                ],
                'format' => 'dd/MM/yyyy HH:mm',
                'html5'  => true,
                'label'  => $this->translator->trans('admin.label.series.firstDate', [], 'messages'),
                'label_attr' => [
                    'id' => 'firstDate_label',
                ],
                'required' => false,
                'widget'   => 'single_text',

            ])
            ->add('lastDate', DateTimeType::class, [
                'attr' => [
                    'aria-labelledby'  => 'lastDate_label',
                    'aria-required'    => 'false',
                    'data-tip-content' => $this->translator->trans('admin.tip.series.lastDate', [], 'messages'),
                    'data-tip-title'   => $this->translator->trans('admin.tip.title.series.lastDate', [], 'messages'),
                    'readOnly' => true,
                ],
                'format' => 'dd/MM/yyyy HH:mm',
                'html5'  => true,
                'label'  => $this->translator->trans('admin.label.series.lastDate', [], 'messages'),
                'label_attr' => [
                    'id' => 'lastDate_label',
                ],
                'required' => false,
                'widget'   => 'single_text',

            ])
        ;
        if (!empty($options['data']->getId())) {
            $builder
                ->add('addItem', ButtonType::class, [
                    'attr' => [
                        'class' => 'button button--information',
                    ],
                    'label' => $this->translator->trans('admin.button.addItem', [], 'messages'),
                ])
//            ->add('items')
            ;
        }
        $builder
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'button button--positive',
                ],
                'label' => $this->translator->trans('admin.button.save', [], 'messages'),
            ])
//            ->add('publish', SubmitType::class, [
//                'attr' => [
//                    'class' => 'button button--info',
//                ],
//                'label' => $this->translator->trans('admin.button.publish', [], 'messages'),
//            ])
            ->add('delete', SubmitType::class, [
                'attr' => [
                    'class' => 'button button--negative',
                ],
                'label' => $this->translator->trans('admin.button.delete', [], 'messages'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form form__post form__series',
            ],
            'data_class' => Series::class,
        ]);
    }
}
