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
 * @property int|null $execution_id
 * @property string $ip
 * @property int $port
 * @property string $module
 * @property \Illuminate\Support\Carbon $detected_at
 * @property string $raw_data
 * @property string|null $hostnames
 * @property string|null $entity
 * @property string|null $isp
 * @property string|null $country_code
 * @property string|null $city
 * @property string|null $os
 * @property string|null $asn
 * @property string $transport
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
 * @property string $transport
 * @property string|null $product
 * @property string|null $product_sn
 * @property string|null $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Execution $execution
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
 * @property int $scan_id
 * @property int $query_id
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property int $count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $errored
 * @property-read \App\Models\Scan $scan
 * @property-read \App\Models\Query $vendorQuery
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Execution whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperExecution {}
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
 * @property string $transport
 * @property string|null $product
 * @property string|null $product_sn
 * @property string|null $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Execution $execution
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

