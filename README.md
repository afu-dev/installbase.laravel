# Installbase2

A cybersecurity data aggregation platform for Schneider Electric that identifies potentially exposed industrial control systems and IoT devices by integrating data from third-party security intelligence sources (Shodan, Censys, BitSight).

## Installation

```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate

# Build frontend assets
npm run build
```

## Usage

### Data Ingestion

The data ingestion workflow processes security scan data from multiple vendors through a series of commands:

#### Prerequisites (First-Time Setup)

**For Censys scanning**, populate field configurations before running scans:

```bash
php artisan censys:populate-field-configurations
```

This imports protocol-specific field mappings from `resources/csv/censys-query-params.csv` into the `censys_field_configurations` table.

#### Running a Scan

**1. Populate queries** (before each new scan):

```bash
php artisan query:populate
```

Imports vendor queries from `resources/csv/fingerprints.tsv` into the `queries` table. Each query specifies a search pattern for either Shodan or Censys.

**2. Create a scan**:

```bash
php artisan scan:create
```

Creates a new scan record and generates execution records for all queries in the database.

**3. Process executions**:

```bash
php artisan execution:work
```

Processes the next available execution (queries vendor APIs and stores results). Run repeatedly until all executions complete:

```bash
# Manual processing (local development)
while php artisan execution:work; do :; done

# In production, this is handled automatically via Laravel scheduler
```

#### Supported Vendors

- **Shodan**: Internet-connected device search engine
- **Censys**: Internet-wide scanning and security intelligence
- **BitSight**: Security ratings and risk monitoring *(coming soon)*

#### Data Storage

Scan results are stored in:
- **Database**: Normalized asset records in `shodan_exposed_assets` and `censys_exposed_assets` tables
- **Bronze layer** (S3/local): Raw JSON responses organized by vendor, date, and query

## Development

```bash
# Start all development services
composer dev

# Run tests
composer test

# Format code
./vendor/bin/pint
```

## License

Proprietary - Schneider Electric
