<?php 

if (!function_exists("download_csv")) {
    function download_csv(...$models) {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"table.csv\"");

        $file = fopen("php://output", "wb");

        foreach ($models as $model) {
            foreach ($model::all() as $entry) {
                fputcsv($file, array_values($entry->setHidden([])->attributesToArray()));
            }
        }

        fclose($file);
    }
}

?>