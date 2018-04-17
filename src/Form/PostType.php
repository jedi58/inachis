<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class PostType extends AbstractType
{
    private $translator;
    private $router;

    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $options['data']->getCategories();
        $tags = $options['data']->getTags();
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'aria-labelledby' => 'title_label',
                    'aria-required' => 'true',
                    'data-tip-content' => '<strong>Required.</strong> This will also be used in the URL for your content. You can click on the link icon to adjust the URL.',
                    'autofocus' => 'true',
                    'class' => 'text',
                    'placeholder' => $this->translator->trans('admin.placeholder.post.title', [], 'messages'),
                ],
                'label' => $this->translator->trans('admin.label.post.title', [], 'messages'),
                'label_attr' => [
                    'id' => 'title_label',
                ],
            ])
            ->add('subTitle', TextType::class, [
                'attr' => [
                    'aria-labelledby' => 'subTitle_label',
                    'aria-required' => 'false',
                    'placeholder' => $this->translator->trans('admin.placeholder.post.subTitle', [], 'messages'),
                ],
                'label' => $this->translator->trans('admin.label.post.subTitle', [], 'messages'),
                'label_attr' => [
                    'id' => 'subTitle_label',
                ],
                'required' => false,
            ])
            //value="{% if post.urls is not empty %}{{ post.urls|first.link }}{% endif %}"
            ->add('url', TextType::class, [
                'attr' => [
                    'aria-labelledby' => 'url_label',
                    'aria-required' => 'false',
                ],
                'label' => $this->translator->trans('admin.placeholder.post.url', [], 'messages'),
                'label_attr' => [
                    'id' => 'url_label',
                ],
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'aria-labelledby' => 'content_label',
                    'aria-required' => 'false',
                ],
                'label' => $this->translator->trans('admin.label.post.content', [], 'messages'),
                'label_attr' => [
                    'class' => 'hidden',
                    'id' => 'content_label',
                ],
                'required' => false,
            ])
            ->add('visibility', TextType::class, [
                'attr' => [
                    'aria-labelledby' => 'visibility_label',
                    'aria-required' => 'false',
                    'class' => 'ui-switch',
                    'data-label-off' => 'private',
                    'data-label-on' => 'public',
                ],
                'label' => $this->translator->trans('admin.label.post.visibility', [], 'messages'),
                'label_attr' => [
                    'id' => 'visibility_label',
                ],
                'required' => false,
            ])
            ->add('postDate', DateTimeType::class, [
                'attr' => [
                    'aria-labelledby' => 'postDate_label',
                    'aria-required' => 'false',
                    'data-tip-content' => $this->translator->trans('admin.tip.post.postDate', [], 'messages'),
                    'data-tip-title' => $this->translator->trans('admin.tip.title.post.postDate', [], 'messages'),
                ],
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => true,
                'label' => $options['data']->getPostDate() < new \DateTime() ?
                    $this->translator->trans('admin.label.post.postDate-past', [], 'messages'):
                    $this->translator->trans('admin.label.post.postDate-future', [], 'messages'),
                'label_attr' => [
                    'id' => 'postDate_label',
                ],
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('categories', EntityType::class, [
                'class' => Page::class,
                'choice_label' => $this->translator->trans('admin.label.post.categories', [], 'messages'),
                'attr' => [
                    'aria-labelledby' => 'categories_label',
                    'aria-required' => 'false',
                    'class' => 'js-select',
                    'data-placeholder' => $this->translator->trans('admin.placeholder.post.categories', [], 'messages'),
                    'data-tip-content' => $this->translator->trans('admin.tip.content.post.categories', [], 'messages'),
                    'data-url' => $this->router->generate('app_admindialog_getcategorymanagerlistcontent'),
                ],
//                'label' => $this->translator->trans('admin.label.post.categories', [], 'messages'),
                'label_attr' => [
                    'id' => 'categories_label',
                ],
                'multiple' => true,
                'required' => false,
            ])
//            ->add('tags', EntityType::class, [
//                'class' => Page::class,
//                'choice_label' => $this->translator->trans('admin.label.post.tags', [], 'messages'),
//                'attr' => [
//                    'aria-labelledby' => 'categories_label',
//                    'aria-required' => 'false',
//                    'class' => 'js-select',
//                    'data-tags' => 'true',
//                    'data-tip-content' => $this->translator->trans('admin.tip.content.post.tags', [], 'messages'),
//                ],
////                'label' => $this->translator->trans('admin.label.post.tags', [], 'messages'),
//                'label_attr' => [
//
//                ],
//                'multiple' => true,
//                'required' => false,
//            ])

//            ->add('categories', CollectionType::class, [
//                'allow_delete' => true,
//                'by_reference' => false,
//                'entry_type' => CategoryType::class,
//            ])
            ->add('tags', CollectionType::class, [
//                'allow_delete' => true,
//                'by_reference' => false,
                'allow_add' => true,
                'entry_type' => TagType::class,
            ])

//            ->add('latlong', TextType::class, [
//                'attr' => [
//                    'aria-labelledby' => 'latlong_label',
//                    'aria-required' => 'false',
//                    'class' => 'ui-map',
//                    'data-google-key' => '{{ settings.google.key }}',
//                    'data-tip-content' => 'Please enter a location name, postcode, zip code, or longitude/latitude to search for the location.',
//                ],
//                'label' => 'Location',
//                'label_attr' => [
//                    'id' => 'latlong_label'
//                ],
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form form__post'
            ],
            'data_class' => Page::class,
        ]);
    }
}
