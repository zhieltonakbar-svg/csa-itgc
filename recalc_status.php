<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controls = App\Models\Control::all();
foreach($controls as $control){
    $statusInfo = App\Models\Control::calculateStatus(App\Models\Control::where('application_id', $control->application_id)->where('it_category_id', $control->it_category_id)->where('year', $control->year)->where('quarter', $control->quarter)->get());
    App\Models\Control::where('application_id', $control->application_id)->where('it_category_id', $control->it_category_id)->where('year', $control->year)->where('quarter', $control->quarter)->update(['status_it_category' => $statusInfo['cat_status']]);
    $application = App\Models\Application::find($control->application_id);
    if ($application) {
        $application->itCategories()->updateExistingPivot($control->it_category_id, ['completion_status' => $statusInfo['pivot_status']]);
    }
}
echo "Done.\n";
