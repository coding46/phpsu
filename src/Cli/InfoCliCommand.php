<?php

declare(strict_types=1);

namespace PHPSu\Cli;

use PHPSu\Kernel;
use PHPSu\Tools\EnvironmentUtility;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @internal
 */
final class InfoCliCommand extends AbstractCliCommand
{

    public function configure(): void
    {
        $this->setName('info')
            ->setDescription('show version information of all instances')
            ->setHelp('show version information of all instances or filter the list by providing the instance you want to look into')
        ->addOption('instance', 'i', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter by one or more instances', []);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('List of all dependencies and their versions');
        $this->printDependencyVersions($output, $symfonyStyle);
        return 0;
    }


    public function printDependencyVersions(OutputInterface $output, SymfonyStyle $symfonyStyle): void
    {
        $environmentUtility = Kernel::getContainer()->get(EnvironmentUtility::class);
        foreach ($this->config->getAppInstanceNames() as $instanceName) {
            $output->writeln(sprintf('<info>Installed on %s</info>', $instanceName));
            $symfonyStyle->table(
                ['Dependency', 'Installed', 'Version'],
                [
                    ['rsync', $environmentUtility->isRsyncInstalled() ? '✔' : '✘', $environmentUtility->getRsyncVersion($instanceName)],
                    ['mysql-distribution', $environmentUtility->isMysqlDumpInstalled() ? '✔' : '✘', $environmentUtility->getMysqlDumpVersion($instanceName)['mysqlVersion']],
                    ['mysqldump', $environmentUtility->isMysqlDumpInstalled() ? '✔' : '✘', $environmentUtility->getMysqlDumpVersion($instanceName)['dumpVersion']],
                    ['ssh', $environmentUtility->isSshInstalled() ? '✔' : '✘', $environmentUtility->getSshVersion($instanceName)]
                ]
            );
        }
    }
}
