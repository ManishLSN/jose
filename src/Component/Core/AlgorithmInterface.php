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

namespace Jose\Component\Core;

/**
 * Interface JWAInterface
 */
interface AlgorithmInterface
{
    /**
     * @return string Returns the name of the algorithm
     */
    public function name(): string;
}