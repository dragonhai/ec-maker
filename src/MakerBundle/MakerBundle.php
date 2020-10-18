<?php

/*
 * This file is part of the Symfony MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerBundle;

use MakerBundle\DependencyInjection\CompilerPass\MakeCommandRegistrationPass;
use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\RemoveMissingParametersPass;
use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\SetDoctrineManagerRegistryClassPass;
use Symfony\Bundle\MakerBundle\MakerBundle as BaseMakerBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MakerBundle extends BaseMakerBundle
{
    public function build(ContainerBuilder $container)
    {
        // add a priority so we run before the core command pass
        $container->addCompilerPass(new MakeCommandRegistrationPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 12);
        $container->addCompilerPass(new RemoveMissingParametersPass());
        $container->addCompilerPass(new SetDoctrineManagerRegistryClassPass());

    }
}
