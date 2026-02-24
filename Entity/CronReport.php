<?php declare(strict_types=1);

namespace Cron\CronBundle\Entity;

use Cron\CronBundle\Repository\CronReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CronReportRepository::class)]
#[ORM\Table(name: 'cron_report')]
class CronReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'run_at', type: 'datetime', nullable: true)]
    protected ?\DateTime $runAt = null;

    #[ORM\Column(name: 'run_time', type: 'float', nullable: true)]
    protected ?float $runTime = null;

    #[ORM\Column(name: 'exit_code', type: 'integer', nullable: true)]
    protected ?int $exitCode = null;

    #[ORM\Column(name: 'output', type: 'text', nullable: true)]
    protected ?string $output = null;

    #[ORM\Column(name: 'error', type: 'text', nullable: true)]
    protected ?string $error = null;

    #[ORM\ManyToOne(targetEntity: CronJob::class, inversedBy: 'reports')]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: true)]
    protected ?CronJob $job = null;

    /**
     * Get id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set job
     */
    public function setJob(?CronJob $job): static
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     */
    public function getJob(): ?CronJob
    {
        return $this->job;
    }

    /**
     * Set output
     */
    public function setOutput(?string $output): static
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get output
     */
    public function getOutput(): ?string
    {
        return $this->output;
    }

    /**
     * Set error
     */
    public function setError(?string $error): static
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Set exit code
     */
    public function setExitCode(?int $exitCode): static
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * Get exit code
     */
    public function getExitCode(): ?int
    {
        return $this->exitCode;
    }

    /**
     * Set run at
     */
    public function setRunAt(?\DateTime $runAt): static
    {
        $this->runAt = $runAt;

        return $this;
    }

    /**
     * Get run at
     */
    public function getRunAt(): ?\DateTime
    {
        return $this->runAt;
    }

    /**
     * Set run time
     */
    public function setRunTime(?float $runTime): static
    {
        $this->runTime = $runTime;

        return $this;
    }

    /**
     * Get run time
     */
    public function getRunTime(): ?float
    {
        return $this->runTime;
    }
}
