<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerBundle\Maker;

use Doctrine\Common\Inflector\Inflector as LegacyInflector;
use Doctrine\Inflector\InflectorFactory;
use MakerBundle\EventRegistry;
use MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
final class MakeListener extends AbstractMaker
{
    private $doctrineHelper;

    private $inflector;

    private $eventRegistry;

    public function __construct(EventRegistry $eventRegistry, DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
        $this->eventRegistry = $eventRegistry;

        if (class_exists(InflectorFactory::class)) {
            $this->inflector = InflectorFactory::create()->build();
        }
    }

    public static function getCommandName(): string
    {
        return 'make:listener';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new event listener class')
            ->addArgument('name', InputArgument::OPTIONAL, 'Choose a class name for your event listener (e.g. <fg=yellow>ExceptionListener</>)')
            ->addArgument('event', InputArgument::OPTIONAL, 'What event do you want to subscribe to?')
            ->addOption(
                'entity',
                null,
                InputOption::VALUE_OPTIONAL,
                'Look like good job on entity'
            )
            ->setHelp(file_get_contents(__DIR__ . '/../Resources/help/MakeListener.txt'))
        ;

        $inputConf->setArgumentAsNonInteractive('event');
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if (!$input->getArgument('event')) {
            $events = $this->eventRegistry->getAllActiveEvents();

            $io->writeln(' <fg=green>Suggested Events:</>');
            $io->listing($this->eventRegistry->listActiveEvents($events));
            $question = new Question(sprintf(' <fg=green>%s</>', $command->getDefinition()->getArgument('event')->getDescription()));
            $question->setAutocompleterValues($events);
            $question->setValidator([Validator::class, 'notBlank']);
            $event = $io->askQuestion($question);
            $input->setArgument('event', $event);
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $repositoryVars = [];
        $entity = $input->getOption('entity');
        if ($entity) {
            $entityClassDetails = $generator->createClassNameDetails(
                Validator::entityExists($entity, $this->doctrineHelper->getEntitiesForAutocomplete()),
                'Entity\\'
            );

            $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

            if (null !== $entityDoctrineDetails->getRepositoryClass()) {
                $repositoryClassDetails = $generator->createClassNameDetails(
                    '\\' . $entityDoctrineDetails->getRepositoryClass(),
                    'Repository\\',
                    'Repository'
                );
                $repositoryClassName = $repositoryClassDetails->getShortName();
                $routeName = Str::asRouteName(substr($repositoryClassName, 0, strlen($repositoryClassName) - 10));
                $entityTwigVarPlural = Str::asTwigVariable(lcfirst($this->pluralize($routeName)));

                $repositoryVars = [
                    'repository_full_class_name' => $repositoryClassDetails->getFullName(),
                    'repository_class_name' => $repositoryClassName,
                    'repository_var' => lcfirst($this->singularize($repositoryClassDetails->getShortName())),
                    'route_name' => $routeName,
                    'entity_twig_var_plural' => $entityTwigVarPlural,
                ];
            }
        }

        $listenerClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'EventListener\\',
            'Listener'
        );

        $event = $input->getArgument('event');
        $eventFullClassName = $this->eventRegistry->getEventClassName($event);
        $eventClassName = $eventFullClassName ? Str::getShortClassName($eventFullClassName) : null;

        $methodName = class_exists($event)
        ? (Str::asEventMethod(Str::asSnakeCase($eventClassName)))
        : (Str::asEventMethod(Str::asSnakeCase($event)));
        $generator->generateClass(
            $listenerClassNameDetails->getFullName(),
            'event/Listener.tpl.php',
            array_merge([
                'event' => class_exists($event) ? sprintf('%s::class', $eventClassName) : sprintf('\'%s\'', $event),
                'event_full_class_name' => $eventFullClassName,
                'event_arg' => $eventClassName ? sprintf('%s $event', $eventClassName) : '$event',
                'method_name' => $methodName,
            ],
                $repositoryVars
            )
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text([
            'Next: Open your new listener class and start customizing it.',
            'Find the documentation at <fg=yellow>https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-listener</>',
        ]);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    private function pluralize(string $word): string
    {
        if (null !== $this->inflector) {
            return $this->inflector->pluralize($word);
        }

        return LegacyInflector::pluralize($word);
    }

    private function singularize(string $word): string
    {
        if (null !== $this->inflector) {
            return $this->inflector->singularize($word);
        }

        return LegacyInflector::singularize($word);
    }
}
