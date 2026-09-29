<?php
$file = 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/css/global-core.css';
if (file_exists($file)) {
    $content = file_get_contents($file);
    $old = "cursor: url('../images/favicon_cursor.png'), auto !important;";
    $new = "cursor: url('/images/favicon_cursor.png'), auto !important;";
    
    if (strpos($content, $old) !== false) {
        $content = str_replace($old, $new, $content);
        file_put_contents($file, $content);
        echo "Successfully updated custom cursor in global-core.css\n";
    } else {
        echo "Old cursor string not found in global-core.css (or already updated)\n";
    }
} else {
    echo "global-core.css does not exist\n";
}
