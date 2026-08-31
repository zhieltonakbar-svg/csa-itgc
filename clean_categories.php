<?php
$ids = App\Models\ItCategory::whereIn('name', ['saya', 'office'])->pluck('id');
App\Models\Control::whereIn('it_category_id', $ids)->delete();
DB::table('application_it_category')->whereIn('it_category_id', $ids)->delete();
DB::table('application_period_it_category')->whereIn('it_category_id', $ids)->delete();
App\Models\ItCategory::whereIn('id', $ids)->delete();
echo "Cleanup done.\n";
