<?php

namespace Inachis\Component\CoreBundle\Security;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Exception\InvalidContentSecurityPolicyException;

/**
 * Object for managing CSP headers
 */
class ContentSecurityPolicy
{
    /**
     * @var SRC-based directives
     */
    public static $srcDirectives = array(
        'default-src',
        'connect-src',
        'font-src',
        'frame-ancestors',
        'frame-src',
        'img-src',
        'media-src',
        'object-src',
        'script-src',
        'style-src',
        'xhr-src',
    );
    /**
     * @var URI-based directives for reporting
     */
    public static $uriDirectives = array(
        'report-uri',
        'policy-uri'
    );
    /**
     * @var Directives used for toggling policy components
     */
    public static $otherDirectives = array(
        'upgrade-insecure-requests',
        'block-all-mixed-content'
    );
    /**
     * @var Application reference to instance of self
     */
    private static $instance;
    /**
     * Returns an instance of {@link Application}
     * @param string $env The environment type being used
     * @return Application The current or a new instance of {@link Application}
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * Returns the CSP policy for enforcing
     * @return string The parsed policies
     */
    public static function getCSPEnforceHeader()
    {
        return self::generateCSP(Application::getInstance()->getConfig('csp')->enforce);
    }
    /**
     * Returns the CSP policy for reporting only
     * @return string The parsed policies
     */
    public static function getCSPReportHeader()
    {
        return self::generateCSP(Application::getInstance()->getConfig('csp')->report);
    }
    /**
     * Generates the CSP from the given policy JSON object
     * @param mixed[] $csp The CSP policy
     * @return string The parsed policies
     * @throws InvalidContentSecurityPolicyException
     */
    public static function generateCSP($csp)
    {
        $policies = array();
        foreach ($csp as $policy => $directives) {
            if (!in_array($policy, self::$srcDirectives) && !in_array($policy, self::$uriDirectives)) {
                throw new InvalidContentSecurityPolicyException();
            }
            $policies[$policy] = $policy;
            if (!empty($directives) && is_object($directives)) {
                foreach ($directives as $directive => $value) {
                    switch ($directive) {
                        case 'self':
                        case 'data':
                        case 'unsafe-inline':
                        case 'unsafe-eval':
                            if ($value === true) {
                                $policies[$policy] .= ' \'' . $directive . '\'';
                            }
                            break;

                        case 'sources':
                            if (!empty($value)) {
                                foreach ($value as $source) {
                                    $policies[$policy] .= ' ' . $source;
                                }
                            }
                            break;

                        default:
                            throw new InvalidContentSecurityPolicyException();
                    }
                }
            }
        }
        return trim(implode('; ', $policies));
    }
}
