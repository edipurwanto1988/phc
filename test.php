<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// mock errors
$app['view']->share('errors', new Illuminate\Support\MessageBag());
session()->start();

$user = App\Models\User::find(5);
Auth::login($user);
$order = App\Models\Order::find(7);
$controller = new App\Http\Controllers\Admin\OrderAttendanceController();
$html = $controller->index($order)->render();

preg_match_all('/<tr class="border-b.*?<\/tr>/s', $html, $matches);
foreach($matches[0] as $i => $row) {
    if (strpos($row, 'Dewi') !== false) {
        echo "ROW DEWI:\n";
        echo $row;
    }
}
