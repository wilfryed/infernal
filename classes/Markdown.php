<?php

class Markdown
{

    public function parse($content)
    {
        $content = htmlspecialchars($content);

        $content = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $content);
        $content = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $content);
        $content = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $content);

        $content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);
        $content = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $content);

        $content = preg_replace('/!\[(.*?)\]\((.*?)\)/', '<img src="$2" alt="$1">', $content);

        $content = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $content);

        $content = preg_replace('/`(.+?)`/', '<code>$1</code>', $content);

        $content = preg_replace('/^---$/m', '<hr>', $content);

        $content = preg_replace('/^> (.+)$/m', '<blockquote>$1</blockquote>', $content);

        $content = $this->parseParagraphs($content);

        return $content;
    }

    private function parseParagraphs($content)
    {
        $lines = explode("\n", $content);

        $html = '';
        $paragraph = [];

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '') {

                if (!empty($paragraph)) {
                    $html .= '<p>' . implode(' ', $paragraph) . '</p>';
                    $paragraph = [];
                }

                continue;
            }

            $paragraph[] = $line;
        }

        if (!empty($paragraph)) {
            $html .= '<p>' . implode(' ', $paragraph) . '</p>';
        }

        return $html;
    }
}
