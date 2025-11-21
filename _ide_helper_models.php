<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $entity
 * @property string|null $sector
 * @property string|null $entity_country
 * @property string|null $url
 * @property string|null $point_of_contact
 * @property string|null $type_of_account
 * @property string|null $account_manager
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereAccountManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereEntityCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account wherePointOfContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereTypeOfAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUrl($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ip
 * @property string|null $entity
 * @property string|null $sector
 * @property string|null $domain
 * @property string|null $hostnames
 * @property string|null $isp
 * @property string|null $asn
 * @property string|null $whois
 * @property string|null $city
 * @property string|null $country_code
 * @property string|null $source_of_attribution
 * @property \Illuminate\Support\Carbon|null $last_exposure_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetectedExposure> $detectedExposures
 * @property-read int|null $detected_exposures_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution forIp(string $ip)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereAsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereHostnames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereIsp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereLastExposureAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereSourceOfAttribution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attribution whereWhois($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAttribution {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $execution_id
 * @property string $ip
 * @property int $port
 * @property string|null $module
 * @property \Illuminate\Support\Carbon $detected_at
 * @property string $raw_data
 * @property string|null $hostnames
 * @property string|null $entity
 * @property string|null $isp
 * @property string|null $country_code
 * @property string|null $city
 * @property string|null $os
 * @property string|null $asn
 * @property string|null $transport
 * @property string|null $product
 * @property string|null $product_sn
 * @property string|null $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Execution|null $execution
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereAsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereDetectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereExecutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereHostnames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereIsp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereProductSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereRawData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BitsightExposedAsset whereVersion($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBitsightExposedAsset {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $execution_id
 * @property string $ip
 * @property int $port
 * @property string|null $module
 * @property \Illuminate\Support\Carbon $detected_at
 * @property string|null $raw_data
 * @property string|null $hostnames
 * @property string|null $entity
 * @property string|null $isp
 * @property string|null $country_code
 * @property string|null $city
 * @property string|null $os
 * @property string|null $asn
 * @property string|null $transport
 * @property string|null $product
 * @property string|null $product_sn
 * @property string|null $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Execution|null $execution
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereAsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereDetectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereExecutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereHostnames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereIsp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereProductSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereRawData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysExposedAsset whereVersion($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCensysExposedAsset {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $protocol
 * @property string $fields
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysFieldConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysFieldConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysFieldConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysFieldConfiguration whereFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysFieldConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CensysFieldConfiguration whereProtocol($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCensysFieldConfiguration {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $country_code2
 * @property string|null $country_code3
 * @property string $country
 * @property string|null $region
 * @property string|null $ciso_region
 * @property string|null $ciso_zone
 * @property string|null $operation_zone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCisoRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCisoZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCountryCode2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCountryCode3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereOperationZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCountry {}
}

namespace App\Models{
/**
 * Detected exposures table - normalized core detection data (IP+Port).
 * 
 * Links to Attribution model via IP for network/location context.
 * Source tracking via relationships to vendor tables (bitsight_exposed_assets,
 * shodan_exposed_assets, censys_exposed_assets) using composite key (ip, port).
 *
 * @property int $id
 * @property string $ip
 * @property int $port
 * @property \App\Enums\Vendor $source
 * @property string|null $transport
 * @property string|null $module
 * @property \Illuminate\Support\Carbon $first_detected_at
 * @property \Illuminate\Support\Carbon $last_detected_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Attribution|null $attribution
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BitsightExposedAsset> $bitsightDetections
 * @property-read int|null $bitsight_detections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CensysExposedAsset> $censysDetections
 * @property-read int|null $censys_detections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShodanExposedAsset> $shodanDetections
 * @property-read int|null $shodan_detections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure forIpPort(string $ip, int $port)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereFirstDetectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereLastDetectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetectedExposure withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDetectedExposure {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $scan_id
 * @property int $query_id
 * @property string|null $source_file
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property int $count
 * @property int $errored
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Scan|null $scan
 * @property-read \App\Models\Query|null $vendorQuery
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereErrored($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereQueryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereScanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereSourceFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperExecution {}
}

namespace App\Models{
/**
 * @deprecated This model is deprecated. Use DetectedExposure and Attribution instead.
 * The exposed_assets table has been replaced by a normalized schema:
 * - DetectedExposure: Core detection data (IP+Port level)
 * - Attribution: Network/location context (IP level)
 * This model remains for backward compatibility only and will be removed in a future version.
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BitsightExposedAsset> $bitsightDetections
 * @property-read int|null $bitsight_detections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CensysExposedAsset> $censysDetections
 * @property-read int|null $censys_detections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShodanExposedAsset> $shodanDetections
 * @property-read int|null $shodan_detections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset forIpPort(string $ip, int $port)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExposedAsset withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperExposedAsset {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $vendor
 * @property string $source_file
 * @property int $row_number
 * @property string|null $ip
 * @property int|null $port
 * @property string $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereRowNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereSourceFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ImportError whereVendor($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperImportError {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $module
 * @property string|null $protocol
 * @property string|null $severity
 * @property string|null $description
 * @property string|null $modifier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereModifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereProtocol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Protocol whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProtocol {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $product
 * @property string|null $protocol
 * @property string $query
 * @property string|null $query_type
 * @property \App\Enums\Vendor $vendor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereProtocol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereQuery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereQueryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Query whereVendor($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperQuery {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Execution> $executions
 * @property-read int|null $executions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Scan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperScan {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $execution_id
 * @property string $ip
 * @property int $port
 * @property string|null $module
 * @property \Illuminate\Support\Carbon $detected_at
 * @property string|null $raw_data
 * @property string|null $hostnames
 * @property string|null $entity
 * @property string|null $isp
 * @property string|null $country_code
 * @property string|null $city
 * @property string|null $os
 * @property string|null $asn
 * @property string|null $transport
 * @property string|null $product
 * @property string|null $product_sn
 * @property string|null $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Execution|null $execution
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereAsn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereDetectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereExecutionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereHostnames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereIsp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereProductSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereRawData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShodanExposedAsset whereVersion($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperShodanExposedAsset {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

