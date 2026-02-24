<?php declare(strict_types=1);
/**
 * This file is part of the SymfonyCronBundle package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cron\CronBundle\Tests\Command;

use Cron\CronBundle\Command\CronRunCommand;
use Cron\CronBundle\Cron\Manager;
use Cron\CronBundle\Cron\Resolver;
use Cron\CronBundle\Job\ShellJobWrapper;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Cron\CronBundle\Tests\TestCase\SymfonyWebTestCase;
/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class CronRunCommandTest extends SymfonyWebTestCase
{
    public function testNoJobs(): void
    {
        $manager = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $manager
            ->expects($this->once())
            ->method('saveReports')
            ->with($this->isArray());

        $resolver = $this->createStub(Resolver::class);
        $resolver->method('resolve')->willReturn([]);

        $command = $this->getCommand($manager, $resolver);

        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $this->assertStringContainsString('time:', $commandTester->getDisplay());
    }

    public function testOneJob(): void
    {
        $manager = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $manager
            ->expects($this->once())
            ->method('saveReports')
            ->with($this->isArray());

        $job = new ShellJobWrapper();

        $resolver = $this->createStub(Resolver::class);
        $resolver->method('resolve')->willReturn([$job]);

        $command = $this->getCommand($manager, $resolver);

        $commandTester = new CommandTester($command);
        $commandTester->execute(array());

        $this->assertStringContainsString('time:', $commandTester->getDisplay());
    }

    public function testNamedJob(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $manager = $this->createStub(Manager::class);
        $manager->method('getJobByName')->willReturn(null);

        $resolver = $this->createStub(Resolver::class);

        $command = $this->getCommand($manager, $resolver);

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
                'job' => 'jobName',
            ));

        $this->assertStringContainsString('time:', $commandTester->getDisplay());
    }

    protected function getCommand(Manager $manager, Resolver $resolver): Command
    {
        $kernel = static::bootKernel();
        $kernel->getContainer()->set('cron.manager', $manager);
        $kernel->getContainer()->set('cron.resolver', $resolver);

        $application = new Application($kernel);
        $application->addCommand(new CronRunCommand($kernel->getContainer()));

        return $application->find('cron:run');
    }
}
