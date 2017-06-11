<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Test\Functional;

use Jose\Algorithm\ContentEncryption\A128CBCHS256;
use Jose\Algorithm\JWAManager;
use Jose\Algorithm\KeyEncryption\A256GCMKW;
use Jose\Algorithm\Signature\HS512;
use Jose\Algorithm\Signature\RS512;
use Jose\Compression\CompressionManager;
use Jose\Compression\Deflate;
use Jose\Decrypter;
use Jose\Encrypter;
use Jose\Factory\CheckerManagerFactory;
use Jose\JWTCreator;
use Jose\JWTLoader;
use Jose\Object\JWK;
use Jose\Object\JWKSet;
use Jose\Signer;
use Jose\Test\TestCase;
use Jose\Verifier;

/**
 * @group JWTLoader
 * @group JWTCreator
 * @group Functional
 */
final class LoaderAndCreatorTest extends TestCase
{
    public function testSignAndLoadUsingJWTCreatorAndJWTLoader()
    {
        $checker = CheckerManagerFactory::createClaimCheckerManager();
        $signatureAlgorithmManager = JWAManager::create([new HS512()]);
        $signer = new Signer($signatureAlgorithmManager);
        $jwt_creator = new JWTCreator($signer);
        $keyEncryptionAlgorithmManager = JWAManager::create([new A256GCMKW()]);
        $contentEncryptionAlgorithmManager = JWAManager::create([new A128CBCHS256()]);
        $compressionManager = CompressionManager::create([new Deflate()]);
        $encrypter = new Encrypter($keyEncryptionAlgorithmManager, $contentEncryptionAlgorithmManager, $compressionManager);
        $jwt_creator->enableEncryptionSupport($encrypter);

        $signatureAlgorithmManager = JWAManager::create([new HS512(), new RS512()]);
        $verifier = new Verifier($signatureAlgorithmManager);
        $jwt_loader = new JWTLoader($checker, $verifier);
        $decrypter = new Decrypter($keyEncryptionAlgorithmManager, $contentEncryptionAlgorithmManager, $compressionManager);
        $jwt_loader->enableDecryptionSupport($decrypter);

        $jws = $jwt_creator->sign(
            'Live long and Prosper.',
            [
                'alg' => 'HS512',
            ],
            new JWK([
                'kty' => 'oct',
                'k'   => 'hJtXIZ2uSN5kbQfbtTNWbpdmhkV8FJG-Onbc6mxCcYg',
            ])
        );
        $jwe = $jwt_creator->encrypt(
            $jws,
            [
                'alg' => 'A256GCMKW',
                'enc' => 'A128CBC-HS256',
            ],
            new JWK([
                'kty' => 'oct',
                'use' => 'enc',
                'k'   => 'qC57l_uxcm7Nm3K-ct4GFjx8tM1U8CZ0NLBvdQstiS8',
            ])
        );

        $key_set = new JWKSet([
            'keys' => [
                [
                    'kty' => 'oct',
                    'k'   => 'qC57l_uxcm7Nm3K-ct4GFjx8tM1U8CZ0NLBvdQstiS8',
                ],
                [
                    'kty' => 'oct',
                    'k'   => 'hJtXIZ2uSN5kbQfbtTNWbpdmhkV8FJG-Onbc6mxCcYg',
                ],
            ],
        ]);
        $loaded_jwe = $jwt_loader->load(
            $jwe,
            $key_set,
            true
        );
        $jwt_loader->verify($loaded_jwe, $key_set);
    }
}
