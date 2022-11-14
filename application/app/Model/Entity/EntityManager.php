<?php declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Repository\BaseRepository;
use Doctrine\Persistence\ObjectRepository;
use Nettrine\ORM\EntityManagerDecorator;

class EntityManager extends EntityManagerDecorator
{
    /**
     * @param string $entityName
     * @return BaseRepository<T>|ObjectRepository<T>
     * @internal
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     * @phpstan-template T
     * @phpstan-param class-string<T> $entityName
     */
    public function getRepository($entityName): ObjectRepository
    {
        return parent::getRepository($entityName);
    }
}
