<?= "<?php" ?><?= PHP_EOL ?>

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method <?= $entity_class_name ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?= $entity_class_name ?>[]    getList()
 * @method void     save(<?= $entity_class_name ?> $<?= $entity_class_name ?>, <?= $entity_class_name ?> $<?= $entity_class_name ?>Origin = null)
 * @method void     delete(<?= $entity_class_name ?> $<?= $entity_class_name ?>)
 */
class <?= $class_name ?> extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, <?= $entity_class_name ?>::class);
    }

    /**
     * Get a list of <?= $entity_class_name ?>.
     *
     * @return <?= $entity_class_name ?>[] Array of <?= $entity_class_name ?><?= PHP_EOL ?>
     */
    public function getList()
    {
        $qb = $this
                ->createQueryBuilder('<?= $entity_alias ?>')
                ->orderBy('<?= $entity_alias ?>.id', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Save the <?= $entity_class_name ?>.
     *
     * @param  <?= $entity_class_name ?> $<?= $entity_class_name ?> The <?= $entity_class_name ?><?= PHP_EOL ?>
     */
    public function save($<?= $entity_class_name ?>, $<?= $entity_class_name ?>Origin = null)
    {
        $em = $this->getEntityManager();
        $em->persist($<?= $entity_class_name ?>);
        $em->flush($<?= $entity_class_name ?>);
    }
    
    /**
     * Delete the <?= $entity_class_name ?>.
     *
     * @param  <?= $entity_class_name ?> $<?= $entity_class_name ?> The <?= $entity_class_name ?><?= PHP_EOL ?>
     */
    public function delete($<?= $entity_class_name ?>)
    {
        $em = $this->getEntityManager();
        $em->remove($<?= $entity_class_name ?>);
        $em->flush($<?= $entity_class_name ?>);
    }
}
