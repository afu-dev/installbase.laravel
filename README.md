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

- **Shodan**: Internet-connected device search engine (API-based real-time scanning)
- **Censys**: Internet-wide scanning and security intelligence (API-based real-time scanning)
- **Bitsight**: Security ratings and exposure data (CSV-based historical import)

#### Bitsight Historical Data Import

Import historical Bitsight exposure data from CSV files:

```bash
php artisan bitsight:import-historical resources/csv/bitsight_historical_part_1.csv
```

**Features:**
- Processes large CSV files (multi-GB) with progress bar and execution time tracking
- Automatically handles duplicates via unique constraint on (IP, port, detected_at)
- Batch processing (1000 rows) for memory efficiency
- Logs malformed rows to `import_errors` table for data quality tracking
- Historical imports have `execution_id` set to NULL

**Data Quality Monitoring:**

All import errors (missing fields, invalid dates, etc.) are logged to the `import_errors` table:

```sql
-- View all import errors
SELECT * FROM import_errors WHERE vendor = 'bitsight';

-- Group errors by type
SELECT error_message, COUNT(*) as count
FROM import_errors
GROUP BY error_message;
```

#### Data Storage

Scan results are stored in:
- **Database**: Normalized asset records in `shodan_exposed_assets`, `censys_exposed_assets`, and `bitsight_exposed_assets` tables
- **Bronze layer** (S3/local): Raw JSON responses organized by vendor, date, and query
- **Import errors**: Data quality issues logged in `import_errors` table for audit and review

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
