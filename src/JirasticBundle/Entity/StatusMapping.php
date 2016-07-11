<?php
/**
 * Status mapping
 */

namespace JirasticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package JirasticBundle\Entity
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class StatusMapping
 *
 * @ORM\Table(name="mapping")
 * @ORM\Entity(repositoryClass="JirasticBundle\Repository\StatusMappingRepository")
 */
class StatusMapping
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Status", mappedBy="StatusMapping", cascade={"all"})
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $fieldName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="This value needs to be a string"
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $name;


    /**
     * StatusMapping constructor.
     */
    public function __construct()
    {
        $this->status = new ArrayCollection();
    }

    /**
     * @param \JirasticBundle\Entity\Status $status Status
     * @return void
     */
    public function addStatus(\JirasticBundle\Entity\Status $status)
    {
        $this->status[] = $status;
    }

    /**
     * @param \JirasticBundle\Entity\Status $status Status
     * @return void
     */
    public function removeStatus(\JirasticBundle\Entity\Status $status)
    {
        $this->status->removeElement($status);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description Description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status Status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $statusId Status id
     * @return void
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;
    }

    /**
     * @return int
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @param string $fieldName Fieldname
     * @return void
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
}
