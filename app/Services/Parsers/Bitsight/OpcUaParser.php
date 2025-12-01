<?php

namespace App\Services\Parsers\Bitsight;

use App\Services\Parsers\AbstractJsonDataParser;
use App\Services\Parsers\ParsedDeviceData;

/**
 * This parser extracts device information from the OPC-UA protocol data.
 *
 * ---
 *
 * Should only return one device per detection.
 *
 * ---
 *
 * Opc-ua Key Frequency:
 * +---------------+-------+------------+
 * | Key           | Count | Percentage |
 * +---------------+-------+------------+
 * | endpoints     | 9,780 | 64.93%     |
 * | server        | 7,815 | 51.89%     |
 * | child_servers | 5,063 | 33.61%     |
 * +---------------+-------+------------+
 */
class OpcUaParser extends AbstractJsonDataParser
{
    protected function parseData(): array
    {
        // Extract vendor: prioritize ManufacturerName from server, fallback to root vendor keys
        $vendor = $this->extractNested(
            ['Opc-Ua', 'Opc-ua', 'opc-ua', 'opc_ua'],
            ['server.ManufacturerName', 'server.manufacturerName', 'server.manufacturer_name']
        );

        if (empty($vendor)) {
            $vendor = $this->extract(['vendor', 'Vendor', 'vendor_name', 'Vendor_Name']);
        }

        $vendor = !empty($vendor) ? $vendor : 'unknown';

        // Extract fingerprint: prioritize ProductName, fallback to organization from ProductUri
        $fingerprint = $this->extractNested(
            ['Opc-Ua', 'Opc-ua', 'opc-ua', 'opc_ua'],
            ['server.ProductName', 'server.productName', 'server.product_name']
        );

        if (empty($fingerprint)) {
            $productUri = $this->extractNested(
                ['Opc-Ua', 'Opc-ua', 'opc-ua', 'opc_ua'],
                ['server.ProductUri', 'server.productUri', 'server.product_uri']
            );
            $fingerprint = $this->extractOrgFromUrn($productUri);
        }

        // Extract version
        $version = $this->extractNested(
            ['Opc-Ua', 'Opc-ua', 'opc-ua', 'opc_ua'],
            ['server.SoftwareVersion', 'server.softwareVersion', 'server.software_version']
        );

        // Extract security policy from endpoints
        $securityPolicies = $this->extractNested(
            ['Opc-Ua', 'Opc-ua', 'opc-ua', 'opc_ua'],
            ['endpoints.Security_Policy_URIs', 'endpoints.security_policy_uris', 'endpoints.securityPolicyUris']
        );

        $securityPolicy = null;
        if (is_array($securityPolicies) && !empty($securityPolicies)) {
            $securityPolicy = $securityPolicies[0];
        } elseif (is_string($securityPolicies) && !empty($securityPolicies)) {
            $securityPolicy = $securityPolicies;
        }

        return [
            new ParsedDeviceData(
                vendor: $vendor,
                fingerprint: $fingerprint,
                version: $version,
                opc_ua_security_policy: $securityPolicy,
            ),
        ];
    }

    /**
     * Extract organization name from OPC-UA ProductUri URN.
     *
     * URN format: urn:organization:product
     * Example: "urn:Beijer Electronics AB:iX Developer 2.20" -> "Beijer Electronics AB"
     */
    private function extractOrgFromUrn(?string $urn): ?string
    {
        if (empty($urn)) {
            return null;
        }

        $parts = explode(':', $urn);

        // Return the organization part (index 1)
        return $parts[1] ?? null;
    }
}
