<?php
$u = App\Models\User::where('email', 'admin@ivo-karya.com')->first();
if ($u) {
    $u->password = bcrypt('password');
    $u->save();
    echo "Password successfully reset for " . $u->email . "\n";
} else {
    echo "User not found.\n";
}
