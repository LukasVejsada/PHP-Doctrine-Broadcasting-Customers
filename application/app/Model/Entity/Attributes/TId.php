<?php declare(strict_types=1);

namespace App\Model\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TId
{
    /**
     * @ORM\Column(type="integer", nullable=FALSE)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function __clone()
    {
        $this->id = null;
    }
}
