<?php
/**
 * StatusType Form
 */
namespace JirasticBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * @package JirasticBundle\Form
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class StatusType
 */
class StatusType extends AbstractType
{
    /**
     * @var array
     */
    private $fontAwesomeIcons;

    /**
     * @var array
     */
    private $classes;

    /**
     * StatusType constructor.
     * @param Container $container Container
     */
    public function __construct($fontAwesomeIcons, $classes)
    {
        $this->fontAwesomeIcons = $fontAwesomeIcons;
        $this->classes = $classes;
    }
    /**
     * @param FormBuilderInterface $builder Form Builder
     * @param array                $options Options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('titleShort')
                ->add('bgcolor')
                ->add(
                    'icon',
                    ChoiceType::class,
                    array(
                        //@todo inject directly
                        'choices' => $this->fontAwesomeIcons
                    )
                )
                ->add(
                    'class',
                    ChoiceType::class,
                    array(
                        'choices' => $this->classes
                    )
                )
                ->add('orderId')
                ->add(
                    'statusMapping',
                    EntityType::class,
                    array(
                        'class' => 'JirasticBundle:StatusMapping',
                        'choice_label' => 'name',
                        'multiple' => true
                    )
                );
    }

    /**
     * @param OptionsResolver $resolver Resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'JirasticBundle\Entity\Status'
            )
        );
    }
}
