#!/bin/sh

cd /var/www/html

echo "📦 Έλεγχος Composer dependencies..."

# Αν υπάρχει recursion vendor/vendor, κάτι πήγε στραβά
if [ -d "vendor/vendor" ]; then
  echo "❌ Λάθος: Εντοπίστηκε nested vendor/vendor. Σταματάει το container."
  exit 1
fi

 Αν δεν υπάρχει ο φάκελος vendor/, κάνε install
if [ ! -d "vendor" ]; then
  echo "➡️ Εκτελείται composer install..."
  composer install --no-dev --optimize-autoloader --no-progress --verbose --timeout=900

  # Έλεγχος αν δημιουργήθηκε το autoload.php
  if [ ! -f "vendor/autoload.php" ]; then
    echo "❌ Αποτυχία: Δεν βρέθηκε το vendor/autoload.php. Τερματισμός."
    exit 1
  else
    echo "✅ Το autoload.php δημιουργήθηκε επιτυχώς."
  fi
fi

# Εκτέλεση SQL schema
echo "🗄️ Εκτελείται αρχικοποίηση βάσης δεδομένων..."
/scripts/init-db.sh


echo "🚀 Εκκίνηση Apache..."
apache2-foreground
