<?php
$directory = __DIR__ . "/resources/views";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$filesModified = 0;

$patterns = [
    // Create/New/Add/Edit
    '/<a([^>]*)href="\{\{\s*route\(\'([^\']+)\'(.*?)\)\s*\}\}"([^>]*)>([\s\S]*?([Aa]dd|[Ee]dit|[Cc]reate|[Nn]ew)[\s\S]*?)<\/a>/i'
];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        // Skip some dirs
        if (strpos($path, '/vendor/') !== false || strpos($path, '/auth/') !== false || strpos($path, '/layouts/') !== false) {
            continue;
        }

        $content = file_get_contents($path);
        $originalContent = $content;

        $content = preg_replace_callback('/<a([^>]*)href="\{\{\s*route\(([^\)]+)\)\s*\}\}"([^>]*)>([\s\S]*?)<\/a>/i', function($matches) {
            $fullMatch = $matches[0];
            $preHref = $matches[1];
            $routeArgs = trim($matches[2]);
            $postHref = $matches[3];
            $innerText = $matches[4];

            // If it already has data-modal-url, skip
            if (strpos($fullMatch, 'data-modal-url') !== false) {
                return $fullMatch;
            }

            // Check if route name implies creation/editing
            $routeNamePart = explode(',', $routeArgs)[0];
            if (preg_match('/(create|new|edit)/i', $routeNamePart) === 1) {
                // Determine title
                $title = "Form";
                if (preg_match('/[Aa]dd/', $innerText)) $title = "Add";
                elseif (preg_match('/[Cc]reate/', $innerText)) $title = "Create";
                elseif (preg_match('/[Ee]dit/', $innerText)) $title = "Edit";
                elseif (preg_match('/[Nn]ew/', $innerText)) $title = "New";

                // Format route with iframe parameter safely
                // e.g. 'clinic.patients.create' -> 'clinic.patients.create', ['iframe' => 1]
                // e.g. 'clinic.patients.edit', $patient -> 'clinic.patients.edit', array_merge((array)$patient, ['iframe' => 1]) - too complex.
                // Simple string append hack: if there are no parameters just add array.
                if (strpos($routeArgs, ',') === false) {
                    $newRoute = $routeArgs . ", ['iframe' => 1]";
                } else {
                    // Split at the first comma
                    $parts = explode(',', $routeArgs, 2);
                    $newRoute = trim($parts[0]) . ", " . trim($parts[1]) . " + ['iframe' => 1]"; // This is invalid PHP mostly.
                    return $fullMatch; // We skip complex ones for now to avoid syntax errors, will do simple creates first
                }

                $routeCall = "{{ route({$newRoute}) }}";
                return sprintf('<a%shref="%s" data-modal-url="%s" data-modal-title="%s"%s>%s</a>',
                    $preHref, $routeCall, $routeCall, $title, $postHref, $innerText
                );
            }

            return $fullMatch;
        }, $content);

        if ($content !== $originalContent) {
            file_put_contents($path, $content);
            $filesModified++;
            echo "Modified: $path\n";
        }
    }
}
echo "Total modified: $filesModified\n";
