<?php declare(strict_types=1);
/**
 * This file is part of the SymfonyCronBundle package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cron\CronBundle\Tests\Cron;

use Cron\CronBundle\Cron\CommandBuilder;
use Cron\CronBundle\Tests\TestCase\SymfonyWebTestCase;
/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class CommandBuilderTest extends SymfonyWebTestCase
{
    public function testRenderEnvironment()
    {
        $env = rand();
        $builder = new CommandBuilder((string) $env);

        $this->assertMatchesRegularExpression(sprintf('/--env=%s$/', $env), $builder->build(''));
    }

    public function testEnv()
    {
        static::bootKernel();
        $builder = static::getContainer()->get('cron.command_builder');

        $this->assertMatchesRegularExpression(sprintf('/ --env=%s$/', 'test'), $builder->build(''));
    }
}
