<?php declare(strict_types=1);

namespace App\Model\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TCreatedDate
{
    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdDate;

    /**
     * TCreatedDate constructor.
     */
    public function __construct()
    {
        $this->createdDate = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): \DateTime
    {
        return $this->createdDate;
    }
}