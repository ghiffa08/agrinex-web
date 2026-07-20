<?php
/**
 * Helper script to create admin user for AgriNex
 * Run: php create_admin_user.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "════════════════════════════════════════════════════════════════════\n";
echo "AgriNex - Create Admin User\n";
echo "════════════════════════════════════════════════════════════════════\n\n";

// Get input
echo "Username: ";
$username = trim(fgets(STDIN));

echo "Email: ";
$email = trim(fgets(STDIN));

echo "Full Name: ";
$fullName = trim(fgets(STDIN));

echo "Password: ";
// Hide password input (works on Linux/Mac)
system('stty -echo');
$password = trim(fgets(STDIN));
system('stty echo');
echo "\n";

echo "Confirm Password: ";
system('stty -echo');
$confirmPassword = trim(fgets(STDIN));
system('stty echo');
echo "\n\n";

// Validate
if ($password !== $confirmPassword) {
    echo "❌ Password tidak sama!\n";
    exit(1);
}

if (strlen($password) < 8) {
    echo "❌ Password minimal 8 karakter!\n";
    exit(1);
}

// Check if user exists
$existingUser = User::where('username', $username)
    ->orWhere('email', $email)
    ->first();

if ($existingUser) {
    echo "❌ Username atau email sudah digunakan!\n";
    exit(1);
}

// Create user
try {
    $user = User::create([
        'username' => $username,
        'email' => $email,
        'full_name' => $fullName,
        'password_hash' => Hash::make($password),
        'role' => 'admin',
        'is_active' => true,
    ]);

    echo "✅ User berhasil dibuat!\n\n";
    echo "Details:\n";
    echo "  ID: {$user->id}\n";
    echo "  Username: {$user->username}\n";
    echo "  Email: {$user->email}\n";
    echo "  Full Name: {$user->full_name}\n";
    echo "  Role: {$user->role}\n";
    echo "  Status: " . ($user->is_active ? 'Active' : 'Inactive') . "\n\n";
    echo "Login URL: https://smartdrip-system.agrinex.io/login\n\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
