<?php

namespace Staffim\RollbarBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Andrew Fureev <a.fureev@staffim.ru>
 */
class ReportCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('staffim_rollbar:report:message')
            ->setDescription('Report a message to the rollbar')
            ->addArgument('message', InputArgument::REQUIRED, 'Reported message')
            ->addOption('level', 'l', InputOption::VALUE_OPTIONAL, 'Level name. Default "info"');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->getContainer()->get('staffim_rollbar.rollbar')->report_message(
                $input->getArgument('message'),
                $input->getOption('level') ?: 'info'
            );
        } catch (\RuntimeException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return 1;
        }

        $output->writeln('<info>Message was reported.</info>');
        return 0;
    }
}
