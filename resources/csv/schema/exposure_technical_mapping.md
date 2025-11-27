# modbus

## vendor
in bitsight & shodan: extract from device identification
if cpu module value != NULL then schneider electric
in censys: vendor

if value in brands then schneider electric

## fingerprint
in bitsight & shodan (can be several fingerprints, all must be extracted): extract from device identification or cpu module
remove brand & version
in censys, product_code

## version
extract from device identification or cpu module
in censys, revision

## sn

## device_mac

## modbus_project_info
project information

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# bacnet

## vendor
in bitsight: name

in shodan: vendor name

in censys: vendor

if value in brands then schneider electric

## fingerprint
model

## version
firmware

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# ethernet/ip (eip)

## vendor
in bitsight & shodan: vendor id
in censys: vendor name

if value in brands then schneider electric

## fingerprint
product_name

## version
revision

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# knx

## vendor
device mac vendor

if value in brands then schneider electric

## fingerprint
device friendly name

## version
firmware

## sn

## device_mac
device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# dnp3

## vendor
if brand can be found in raw data then schneider electric

## fingerprint

## version

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# iec61850

## vendor
vendor
if value in brands then schneider electric

## fingerprint
product

## version
version

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected


# opc-ua

## vendor
if brand can be found in raw data then schneider electric

## fingerprint
product name
if product name = NULL then urn

## version
SoftwareVersion

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy
SecurityPolicy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected


# codesys

## vendor
vendor_name
NB: if several fingerprints, vendor_name will be different for each fingerprint

if value in brands then schneider electric

## fingerprint
device name
can be several fingerprints, all must be extracted

## version
version
NB: if several fingerprints, version will be different for each fingerprint

## sn
sn
NB: if several fingerprints, sn will be different for each fingerprint

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected


# iec-104

## vendor
if brand can be found in raw data then schneider electric

## fingerprint

## version

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected


# ion

## vendor
if brand can be found in raw data then schneider electric

## fingerprint
device_type

## version
revision

## sn
serial_num

## device_mac
mac_address

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected


# apcupsd

## vendor
if brand can be found in raw data then schneider electric
or vendor column = schneider_electric

## fingerprint
model

## version
version

## sn
serialno

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# ftp with APxxxx value

## vendor
schneider electric

## fingerprint
Apxxxx

## version
extract from ftp_data

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app

## nmc_card_num
Apxxxx

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected


# SNMP with APC value

## vendor
schneider electric

## fingerprint
MN

## version
PF

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active

## registration_info

## secure_power_app
extract from AN1

## nmc_card_num
Apxxxx

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected

# other

## vendor
if fingerprint != NULL then schneider electric

## fingerprint
fingerprint

## version

## sn

## device_mac

## modbus_project_info

## opc-ua_security_policy

## is_guest_account_active
is_guest_account_active

## registration_info
registration_info

## secure_power_app

## nmc_card_num

## fingerprint_raw
bitsight fingerprint column

## data
raw data collected
