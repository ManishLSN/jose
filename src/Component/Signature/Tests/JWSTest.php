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

namespace Jose\Component\Signature\Tests;

use Jose\Component\Checker\CheckerManager;
use Jose\Component\Checker\CriticalHeaderChecker;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\NotBeforeChecker;
use Jose\Component\Core\JWK;
use Jose\Component\Factory\JWSFactory;
use Jose\Component\Signature\Signature;
use PHPUnit\Framework\TestCase;

/**
 * final class JWSTest.
 *
 * @group JWS
 * @group Unit
 */
final class JWSTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage  One or more claims are marked as critical, but they are missing or have not been checked (["iss"])
     */
    public function testJWS()
    {
        $claims = [
            'nbf' => time(),
            'iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'Me',
            'aud' => 'You',
            'sub' => 'My friend',
        ];
        $jws = JWSFactory::createJWS($claims);

        $this->assertTrue($jws->hasClaims());
        $this->assertTrue($jws->hasClaim('nbf'));
        $this->assertTrue($jws->hasClaim('iss'));
        $this->assertEquals('Me', $jws->getClaim('iss'));
        $this->assertEquals($claims, $jws->getPayload());
        $this->assertEquals($claims, $jws->getClaims());
        $this->assertEquals(0, $jws->countSignatures());

        $jws = $jws->addSignatureInformation(JWK::create(['kty' => 'none']), ['crit' => ['nbf', 'iat', 'exp', 'iss']]);
        $this->assertEquals(1, $jws->countSignatures());

        $checker_manager = new CheckerManager();
        $checker_manager->addClaimChecker(new ExpirationTimeChecker());
        $checker_manager->addClaimChecker(new IssuedAtChecker());
        $checker_manager->addClaimChecker(new NotBeforeChecker());
        $checker_manager->addHeaderChecker(new CriticalHeaderChecker());
        $checker_manager->checkJWS($jws, 0);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The signature does not exist.
     */
    public function testToCompactJSONFailed()
    {
        $jws = JWSFactory::createJWS([
            'nbf' => time(),
            'iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'Me',
            'aud' => 'You',
            'sub' => 'My friend',
        ]);

        $jws->toCompactJSON(0);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The signature does not exist.
     */
    public function testToFlattenedJSONFailed()
    {
        $jws = JWSFactory::createJWS([
            'nbf' => time(),
            'iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'Me',
            'aud' => 'You',
            'sub' => 'My friend',
        ]);

        $jws->toFlattenedJSON(0);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No signature.
     */
    public function testToJSONFailed()
    {
        $jws = JWSFactory::createJWS([
            'nbf' => time(),
            'iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'Me',
            'aud' => 'You',
            'sub' => 'My friend',
        ]);

        $jws->toJSON();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The payload does not contain claims.
     */
    public function testNoClaims()
    {
        $jws = JWSFactory::createJWS('Hello');

        $jws->getClaims();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The payload does not contain claim "foo".
     */
    public function testClaimDoesNotExist()
    {
        $jws = JWSFactory::createJWS([
            'nbf' => time(),
            'iat' => time(),
            'exp' => time() + 3600,
            'iss' => 'Me',
            'aud' => 'You',
            'sub' => 'My friend',
        ]);

        $jws->getClaim('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The signature contains unprotected headers and cannot be converted into compact JSON
     */
    public function testSignatureContainsUnprotectedHeaders()
    {
        $jws = JWSFactory::createJWS('Hello');

        $jws = $jws->addSignatureInformation(JWK::create(['kty' => 'none']), [], ['foo' => 'bar']);

        $jws->toCompactJSON(0);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The header "foo" does not exist
     */
    public function testSignatureDoesNotContainHeader()
    {
        $signature = new Signature();

        $signature->getHeader('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The protected header "foo" does not exist
     */
    public function testSignatureDoesNotContainProtectedHeader()
    {
        $signature = new Signature();

        $signature->getProtectedHeader('foo');
    }
}