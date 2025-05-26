#!/bin/bash

echo "⏳ Περιμένω να ξεκινήσει η βάση..."

# Μέγιστες προσπάθειες
MAX_TRIES=30
TRIES=0

until mysql -h db -u root -prootpassword -e "SELECT 1;" > /dev/null 2>&1; do
  TRIES=$((TRIES+1))
  echo "🔁 Προσπάθεια $TRIES: Η βάση δεν είναι έτοιμη ακόμα. Περιμένω..."
  if [ $TRIES -ge $MAX_TRIES ]; then
    echo "❌ Αποτυχία σύνδεσης στη βάση μετά από $MAX_TRIES προσπάθειες. Τερματισμός."
    exit 1
  fi
  sleep 2
done

echo "✅ Η βάση είναι έτοιμη. Εκτελώ schema.sql..."

mysql -h db -u root -prootpassword testdb < /var/www/html/db/schema.sql

echo "🎉 Η βάση ενημερώθηκε με επιτυχία!"
