#!/bin/sh

echo "⚠️  Φόρτωση schema.sql μέσα στη βάση MySQL..."

docker compose exec db sh -c "mysql -u root -prootpassword testdb < /docker-entrypoint-initdb.d/schema.sql"

if [ $? -eq 0 ]; then
  echo '✅ Η βάση ενημερώθηκε επιτυχώς!'
else
  echo '❌ Κάτι πήγε στραβά με την εισαγωγή του schema.sql'
fi
