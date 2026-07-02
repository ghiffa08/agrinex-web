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
 * @property int $sesi_id_getdata
 * @property \Illuminate\Support\Carbon $waktu_mulai
 * @property \Illuminate\Support\Carbon|null $waktu_selesai
 * @property int|null $node_sukses
 * @property int|null $node_gagal
 * @property-read mixed $duration
 * @property-read mixed $success_rate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NodeLog> $nodeLogs
 * @property-read int|null $node_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SensorNodeData> $sensorNodeData
 * @property-read int|null $sensor_node_data_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SensorWeatherData> $sensorWeatherData
 * @property-read int|null $sensor_weather_data_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog successful()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog whereNodeGagal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog whereNodeSukses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog whereSesiIdGetdata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog whereWaktuMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GetdataLog whereWaktuSelesai($value)
 */
	class GetdataLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read mixed $duration
 * @property-read mixed $success_rate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NodeLog> $nodeLogs
 * @property-read int|null $node_logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ValveLog> $valveLogs
 * @property-read int|null $valve_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IrrigateLog failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IrrigateLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IrrigateLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IrrigateLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IrrigateLog successful()
 */
	class IrrigateLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $node_id
 * @property int|null $rssi_dbm Signal strength in dBm
 * @property float|null $snr_db Signal to noise ratio in dB
 * @property string|null $signal_quality Excellent/Good/Fair/Poor
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $waktu
 * @property string|null $type_sesi getdata/irrigate
 * @property int|null $sesi_id
 * @property string|null $keterangan timeout/error message
 * @property-read mixed $signal_strength
 * @property-read \App\Models\IrrigateLog|null $irrigateLog
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog getdata()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog irrigate()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog successful()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereRssiDbm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereSesiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereSignalQuality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereSnrDb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereTypeSesi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeLog whereWaktu($value)
 */
	class NodeLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sesi_id_getdata
 * @property int $node_id
 * @property float|null $voltage_v Voltage in V
 * @property int|null $current_ma Current in mA
 * @property int|null $power_mw Power in mW
 * @property float|null $temp_c Temperature in °C
 * @property float|null $soil_pct Soil moisture in %
 * @property int|null $soil_adc Soil sensor ADC value
 * @property int|null $ts_counter Timestamp counter
 * @property \Illuminate\Support\Carbon|null $received_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereCurrentMa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData wherePowerMw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereSesiIdGetdata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereSoilAdc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereSoilPct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereTempC($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereTsCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorNodeData whereVoltageV($value)
 */
	class SensorNodeData extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $sesi_id_getdata
 * @property int $node_id
 * @property float|null $voltage Voltage in V
 * @property int|null $current Current in mA
 * @property int|null $power Power in mW
 * @property int|null $light Light intensity in lux
 * @property int|null $rain Rain percentage
 * @property int|null $rain_adc Rain sensor ADC value
 * @property float|null $wind Wind speed
 * @property int|null $wind_pulse Wind pulse count
 * @property float|null $humidity Humidity in %
 * @property float|null $temp_dht Temperature from DHT in °C
 * @property int|null $ts_counter Timestamp counter
 * @property \Illuminate\Support\Carbon|null $received_at
 * @property-read mixed $light_category
 * @property-read mixed $wind_category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereHumidity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereLight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereRain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereRainAdc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereSesiIdGetdata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereTempDht($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereTsCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereVoltage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereWind($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SensorWeatherData whereWindPulse($value)
 */
	class SensorWeatherData extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read mixed $flow_rate
 * @property-read mixed $volume_liters
 * @property-read \App\Models\IrrigateLog|null $irrigateLog
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValveLog byNode($nodeId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValveLog failed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValveLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValveLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValveLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValveLog successful()
 */
	class ValveLog extends \Eloquent {}
}

