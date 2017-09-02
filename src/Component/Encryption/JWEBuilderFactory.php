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

namespace Jose\Component\Encryption;

use Jose\Component\Core\JWAManagerFactory;
use Jose\Component\Encryption\Compression\CompressionMethodManagerFactory;

final class JWEBuilderFactory
{
    /**
     * @var JWAManagerFactory
     */
    private $algorithmManagerFactory;

    /**
     * @var CompressionMethodManagerFactory
     */
    private $compressionMethodManagerFactory;

    /**
     * JWEBuilder constructor.
     *
     * @param JWAManagerFactory               $algorithmManagerFactory
     * @param CompressionMethodManagerFactory $compressionMethodManagerFactory
     */
    public function __construct(JWAManagerFactory $algorithmManagerFactory, CompressionMethodManagerFactory $compressionMethodManagerFactory)
    {
        $this->algorithmManagerFactory = $algorithmManagerFactory;
        $this->compressionMethodManagerFactory = $compressionMethodManagerFactory;
    }

    /**
     * @param string[] $keyEncryptionAlgorithms
     * @param string[] $contentEncryptionAlgorithm
     * @param string[] $compressionMethods
     *
     * @return JWEBuilder
     */
    public function create(array $keyEncryptionAlgorithms, array $contentEncryptionAlgorithm, array $compressionMethods): JWEBuilder
    {
        $keyEncryptionAlgorithmManager = $this->algorithmManagerFactory->create($keyEncryptionAlgorithms);
        $contentEncryptionAlgorithmManager = $this->algorithmManagerFactory->create($contentEncryptionAlgorithm);
        $compressionMethodManager = $this->compressionMethodManagerFactory->create($compressionMethods);

        return new JWEBuilder($keyEncryptionAlgorithmManager, $contentEncryptionAlgorithmManager, $compressionMethodManager);
    }
}