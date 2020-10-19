<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

<?= isset($repository_full_class_name) ? "use $repository_full_class_name;\n" : '' ?>
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
<?= $event_full_class_name ? "use $event_full_class_name;\n" : '' ?>

class <?= $class_name ?> implements EventSubscriberInterface
{
<?php if (isset($repository_full_class_name)): ?>
    /**
     * @var <?= $repository_class_name ?><?= PHP_EOL ?>
     */
    protected $<?= $repository_var ?>;

    /**
     * Initialize with repository.
     *
     * @param <?= $repository_class_name ?> $<?= $repository_var ?><?= PHP_EOL ?>
     */
    public function __construct(<?= $repository_class_name ?> $<?= $repository_var ?>)
    {
        $this-><?= $repository_var ?> = $<?= $repository_var ?>;
    }
<?php endif ?>

    public static function getSubscribedEvents()
    {
        return [
            <?= $event ?> => '<?= $method_name ?>',
        ];
    }
    
    /**
     * @param <?= $event_arg ?><?= PHP_EOL ?>
     */
    public function <?= $method_name ?>(<?= $event_arg ?>)
    {
<?php if (isset($repository_full_class_name)): ?>
        $twig = '@admin/<?= ucfirst($route_name) ?>/display.twig';
        $event->addSnippet($twig);
        $<?= $entity_twig_var_plural ?> = $this-><?= $repository_var ?>->getList();
        $parameters = $event->getParameters();
        $parameters['<?= $entity_twig_var_plural ?>'] = $<?= $entity_twig_var_plural ?>;
        $event->setParameters($parameters);
<?php endif ?>
    }
}
