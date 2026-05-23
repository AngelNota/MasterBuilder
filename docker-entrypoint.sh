#!/bin/bash
set -e

# Copy env if it does not exist
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
fi

# Ensure APP_KEY is set
if ! grep -q "APP_KEY=base64" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Wait for DB connection
echo "Waiting for database connection..."
php -r '
$host = getenv("DB_HOST") ?: "db";
$port = getenv("DB_PORT") ?: "3306";
$user = getenv("DB_USERNAME") ?: "root";
$pass = getenv("DB_PASSWORD") ?: "";
$db   = getenv("DB_DATABASE") ?: "masterbuilder";

for ($i = 0; $i < 30; $i++) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
        echo "Database is ready!\n";
        exit(0);
    } catch (PDOException $e) {
        echo "Database not ready yet (Attempt " . ($i + 1) . "/30), waiting...\n";
        sleep(2);
    }
}
exit(1);
'

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed database only if users table is empty
echo "Checking if database needs seeding..."
php -r '
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $userExists = DB::table("users")->exists();
    if (!$userExists) {
        echo "Database has no users. Seeding...\n";
        $code = Artisan::call("db:seed", ["--force" => true]);
        echo "Seeding output code: $code\n";
    } else {
        echo "Database already has users. Skipping seed.\n";
    }
} catch (Exception $e) {
    echo "Error checking/seeding database: " . $e->getMessage() . "\n";
}
'

# Execute the CMD passed to docker run
exec "$@"
