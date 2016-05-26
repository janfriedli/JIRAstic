<?php
/**
 * Customfield Form
 */

namespace JirasticBundle\Form;

use JirasticBundle\Gateway\JiraGateway;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class CustomfieldType
 */
class CustomfieldType extends AbstractType
{
    /**
     * @var JiraGateway
     */
    private $jiraGateway;

    /**
     * @var string customfields rest endpoint
     */
    private $customfieldsUrl;

    /**
     * CustomfieldType constructor.
     * @param JiraGateway $jiraGateway     Jira Gateway
     * @param string      $customfieldsUrl Customfields Service endpoint
     */
    public function __construct(JiraGateway $jiraGateway, $customfieldsUrl)
    {
        $this->jiraGateway = $jiraGateway;
        $this->customfieldsUrl = $customfieldsUrl;
    }
    
    /**
     * @param FormBuilderInterface $builder Builder
     * @param array                $options Options
     * @return Builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repsonse = $this->jiraGateway->getRequest($this->customfieldsUrl);
        $customfields = array();
        foreach ($repsonse as $customfield) {
            $customfields[$customfield->id] = $customfield->name;
        }

        array_multisort($customfields, SORT_ASC, $repsonse);

        $builder
            ->add(
                'testInstructions',
                ChoiceType::class,
                array(
                    'choices' => $customfields
                )
            )
            ->add(
                'storyPoints',
                ChoiceType::class,
                array(
                    'choices' => $customfields
                )
            )
            ->add(
                'storyPointsEstimated',
                ChoiceType::class,
                array(
                    'choices' => $customfields
                )
            )
            ->add(
                'storyOwner',
                ChoiceType::class,
                array(
                    'choices' => $customfields
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
            'data_class' => 'JirasticBundle\Entity\Customfield'
            )
        );
    }
}
