<?php declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Attributes\TCreatedDate;
use App\Model\Entity\Attributes\TId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Repository\ProgramRepository")
 */
class Program
{
    use TId;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="programs")
     */
    private Customer $customer;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="datetime", nullable="true")
     */
    private \DateTime $broadcastingDate;

    /**
     * @ORM\Column(type="string", nullable="true")
     */
    private string $description;

    use TCreatedDate {
        TCreatedDate::__construct as private __tCreatedDateConstruct;
    }

    /**
     * @param Customer $customer
     * @param string $name
     */
    public function __construct(Customer $customer, string $name)
    {
        $this->customer = $customer;
        $this->name = $name;
        $this->__tCreatedDateConstruct();
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function getBroadcastingDate(): \DateTime
    {
        return $this->broadcastingDate;
    }

    /**
     * @param \DateTime $broadcastingDate
     */
    public function setBroadcastingDate(\DateTime $broadcastingDate): void
    {
        $this->broadcastingDate = $broadcastingDate;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
