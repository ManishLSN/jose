<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Component\Console\Command;

use Jose\Component\Core\JWKFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OkpKeyGeneratorCommand extends AbstractGeneratorCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('key:generate:okp')
            ->setDescription('Generate an Octet Key Pair key (JWK format)')
            ->addArgument('curve', InputArgument::REQUIRED, 'Curve of the key.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curve = $input->getArgument('curve');
        $args = $this->getOptions($input);

        $jwk = JWKFactory::createOKPKey($curve, $args);
        $this->prepareJsonOutput($input, $output, $jwk);
    }
}
