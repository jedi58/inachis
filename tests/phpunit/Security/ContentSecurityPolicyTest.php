<?php

namespace App\Tests\phpunit\Security;

use App\Exception\InvalidContentSecurityPolicyException;
use App\Security\ContentSecurityPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @Entity
 * @group unit
 */
class ContentSecurityPolicyTest extends TestCase
{
    /**
     * @var string[] Policies to use for CSP header tests
     */
    protected $csp;

    /**
     * Set-up CSP defaults
     */
    public function setUp()
    {
        $this->csp = json_decode(
            '{
                "enforce": {
                    "default-src": {
                        "self": true
                    },
                    "script-src": {
                        "unsafe-eval": true,
                        "self": true,
                        "sources": [
                            "analytics.google.com"
                        ]
                    },
                    "upgrade-insecure-requests": true
                },
                "report": {
                    "style-src": {
                        "self": true,
                        "data": true
                    }
                }
            }',
            true
        );
    }
    /**
     * Test the enforce header
     * @throws InvalidContentSecurityPolicyException
     */
    public function testGenerateCSPEnforceHeader()
    {
        $this->assertEquals(
            'default-src \'self\'; script-src \'unsafe-eval\' \'self\' analytics.google.com; upgrade-insecure-requests',
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader($this->csp)
        );
    }
    /**
     * Test the report header
     * @throws InvalidContentSecurityPolicyException
     */
    public function testGenerateCSPReportHeader()
    {
        $this->assertEquals(
            'style-src \'self\' data:',
            ContentSecurityPolicy::getInstance()->getCSPReportHeader($this->csp)
        );
    }
    /**
     * Test the enforce header default is not an empty string
     * @throws InvalidContentSecurityPolicyException
     */
    public function testGenerateCSPEnforceHeaderDefault()
    {
        $this->assertEmpty(
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader()
        );
    }
    /**
     * Test the report header default is not an empty string
     * @throws InvalidContentSecurityPolicyException
     */
    public function testGenerateCSPReportHeaderDefault()
    {
        $this->assertEmpty(
            ContentSecurityPolicy::getInstance()->getCSPReportHeader()
        );
    }

    public function testGenerateCSPPolicyFail()
    {
        try {
            $csp = json_decode(
                '{
                    "foo-src": {
                        "bar": true
                    }
                }',
                true
            );
            ContentSecurityPolicy::getInstance()->generateCSP($csp);
        } catch (\Exception $exception) {
            $this->assertContains('policy is not supported', $exception->getMessage());
        }
    }

    public function testGenerateCSPDirectiveFail()
    {
        try {
            $csp = json_decode(
                '{
                    "default-src": {
                        "bar": true
                    }
                }',
                true
            );
            ContentSecurityPolicy::getInstance()->generateCSP($csp);
        } catch (\Exception $exception) {
            $this->assertContains('Could not understand', $exception->getMessage());
        }
    }
}
