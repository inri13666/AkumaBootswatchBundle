<?php

namespace Akuma\Bundle\BootswatchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * InstallCommand *
 */
class InstallCommand extends ContainerAwareCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('akuma:bootswatch:install')
            ->setDescription('Installs the icon font');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destDir = $this->getDestDir();

        $finder = new Finder;
        $fs = new Filesystem;

        try {
            $fs->mkdir($destDir);
        } catch (IOException $e) {
            $output->writeln(sprintf('<error>Could not create directory %s.</error>', $destDir));

            return;
        }

        $srcDir = $this->getSrcDir();
        if (false === file_exists($srcDir)) {
            $output->writeln(sprintf(
                '<error>Fonts directory "%s" does not exist. Did you install thomaspark/bootswatch?</error>',
                $srcDir
            ));

            return;
        }
        $finder->files()->in($srcDir);

        foreach ($finder as $file) {
            $dest = sprintf('%s/%s', $destDir, $file->getBaseName());
            try {
                $fs->copy($file, $dest);
            } catch (IOException $e) {
                $output->writeln(sprintf('<error>Could not copy %s</error>', $file->getBaseName()));

                return;
            }
        }


        $output->writeln(sprintf('Copied icon fonts to <comment>%s</comment>.', $destDir));

        if($this->getContainer()->getParameter('akuma_bootswatch.font_awesome')){
            $srcDir = $this->getFaSrcDir();
            if (false === file_exists($srcDir)) {
                $output->writeln(sprintf(
                    '<error>Fonts directory "%s" does not exist. Did you install thomaspark/bootswatch?</error>',
                    $srcDir
                ));

                return;
            }
            $finder->files()->in($srcDir);

            foreach ($finder as $file) {
                $dest = sprintf('%s/%s', $destDir, $file->getBaseName());
                try {
                    $fs->copy($file, $dest);
                } catch (IOException $e) {
                    $output->writeln(sprintf('<error>Could not copy %s</error>', $file->getBaseName()));

                    return;
                }
            }


            $output->writeln(sprintf('Copied Font-Awesome icon fonts to <comment>%s</comment>.', $destDir));
        }
    }

    protected function getFaSrcDir()
    {
        return sprintf(
            '%s/%s',
            $this->getContainer()->getParameter('akuma_bootswatch.bootswatch.path'),
            'bower_components/font-awesome/fonts'
        );

    }

    /**
     * @return string
     */
    protected function getSrcDir()
    {
        return sprintf(
            '%s/%s',
            $this->getContainer()->getParameter('akuma_bootswatch.bootswatch.path'),
            (
                // Sass version stores fonts in a different directory
            in_array($this->getContainer()->getParameter('akuma_bootswatch.less_filter'), array('sass', 'scssphp')) ?
                'bower_components/bootstrap-sass-official/assets/fonts/bootstrap' :
                'bower_components/bootstrap/fonts'
            )
        );
    }

    /**
     * @return string
     */
    protected function getDestDir()
    {
        return $this->getContainer()->getParameter('akuma_bootswatch.fonts_dir');
    }
}
