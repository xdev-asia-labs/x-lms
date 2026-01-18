#!/bin/bash

# Initialize PostgreSQL extensions and configurations

set -e

# Create extensions if needed
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    -- Enable UUID extension
    CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
    
    -- Enable pg_trgm for better text search
    CREATE EXTENSION IF NOT EXISTS pg_trgm;
    
    -- Enable unaccent for accent-insensitive search
    CREATE EXTENSION IF NOT EXISTS unaccent;
    
    -- Grant permissions
    GRANT ALL PRIVILEGES ON DATABASE $POSTGRES_DB TO $POSTGRES_USER;
    GRANT ALL ON SCHEMA public TO $POSTGRES_USER;
EOSQL

echo "PostgreSQL initialized successfully!"
