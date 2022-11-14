<?php declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Entity\Attributes\TCreatedDate;
use App\Model\Entity\Attributes\TId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Repository\CustomerRepository")
 */
class Customer
{
    public const TYPE_RADIO = 1;
    public const TYPE_TV = 2;
    public const TYPE_IPTV = 3;
    public const TYPE_OTHER = 4;

    public static array $typeNameArr = [
        self::TYPE_RADIO => 'Rozhlas',
        self::TYPE_TV => 'Televize',
        self::TYPE_IPTV => 'IP televize',
        self::TYPE_OTHER => 'Ostatní',
    ];

    use TId;

    /**
     * @ORM\OneToMany(targetEntity="Program", mappedBy="customer", cascade={"persist", "remove"})
     */
    private Collection $programs;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $type;

    /**
     * @ORM\Column(type="text", nullable="true")
     */
    private string $note;

    use TCreatedDate {
        TCreatedDate::__construct as private __tCreatedDateConstruct;
    }

    /**
     * @param string $name
     */
    public function __construct(string $name, int $type)
    {
        $this->name = $name;
        $this->type = $type;
        $this->programs = new ArrayCollection();
        $this->__tCreatedDateConstruct();
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
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Collection
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
    }
}
