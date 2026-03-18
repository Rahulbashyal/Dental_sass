<?php
$directory = __DIR__ . "/resources/views";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$filesModified = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $filename = $file->getFilename();
        
        // We only care about create or edit views
        if (strpos($filename, 'create.blade.php') === false && strpos($filename, 'edit.blade.php') === false) {
            continue;
        }

        $content = file_get_contents($path);
        
        // Check if it's already using the ternary iframe layout logic
        if (strpos($content, "request()->has('iframe')") !== false) {
            continue;
        }

        // Search for standard @extends declarations
        if (preg_match("/@extends\('([^']+)'\)/", $content, $matches)) {
            $layoutName = $matches[1];
            
            // Only modify standard dashboard layouts
            if (strpos($layoutName, 'layouts.') === 0) {
                // Replace strictly the first occurrence
                $replacement = "@extends(request()->has('iframe') ? 'layouts.iframe' : '$layoutName')";
                $newContent = str_replace($matches[0], $replacement, $content);
                
                // ALSO logic for replacing redirect -> with script tags -> not as easy universally.
                // Let's at least inject the hidden iframe input field into forms.
                if (preg_match("/<form([^>]*)>/i", $newContent, $formMatches)) {
                    $hiddenInput = "\n    @if(request()->has('iframe'))\n        <input type=\"hidden\" name=\"iframe\" value=\"1\">\n    @endif\n";
                    $newContent = str_replace($formMatches[0], $formMatches[0] . $hiddenInput, $newContent);
                }

                // Append the auto-close code snippet at the end of the file
                $autoCloseScript = "\n\n{{-- Auto-close modal script on success --}}\n@if(session('success') && request()->has('iframe'))\n    <script>\n        setTimeout(() => {\n            window.parent.location.reload();\n        }, 1500);\n    </script>\n@endif\n";
                $newContent .= $autoCloseScript;
                
                file_put_contents($path, $newContent);
                echo "Setup IFrame layout for: $path\n";
                $filesModified++;
            }
        }
    }
}
echo "Total updated layouts: $filesModified\n";
