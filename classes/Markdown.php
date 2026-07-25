<?php

class Markdown
{

    public function parse(string $content): string
    {
        // Titres
        $content = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $content);
        $content = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $content);
        $content = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $content);

        // Gras / italique
        $content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);
        $content = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $content);

        // Images
        $content = preg_replace(
            '/!\[(.*?)\]\((.*?)\)/',
            '<img src="$2" alt="$1">',
            $content
        );

        // Liens Markdown
        $content = preg_replace(
            '/\[(.*?)\]\((.*?)\)/',
            '<a href="$2">$1</a>',
            $content
        );

        // Liens Infernal
        $content = preg_replace(
            '/\[(.*?)\]\{(.*?)\}/',
            '<a href="$2">$1</a>',
            $content
        );

        // Code inline
        $content = preg_replace(
            '/`(.+?)`/',
            '<code>$1</code>',
            $content
        );

        // Citation
        $content = preg_replace(
            '/^> (.+)$/m',
            '<blockquote>$1</blockquote>',
            $content
        );

        // Séparateur
        $content = preg_replace(
            '/^---$/m',
            '<hr>',
            $content
        );

        return $this->parseParagraphs($content);
    }

    private function flushParagraph(array $paragraph): string
    {
        if (empty($paragraph)) {
            return '<br>';
        }

        return '<p>'
            . implode("<br>\n", $paragraph)
            . '</p>';
    }

    private function parseParagraphs(string $content): string
    {
        $lines = preg_split("/\R/u", $content);

        $html = '';
        $paragraph = [];

        foreach ($lines as $line) {

            $line = trim($line);


            // Ligne vide = fin du paragraphe
            if ($line === '') {

                $html .= $this->flushParagraph($paragraph);

                $paragraph = [];

                continue;
            }


            // Bloc HTML brut
            if (
                $this->isHtmlBlock($line) ||
                $this->isBlockElement($line)
            ) {

                if (!empty($paragraph)) {

                    $html .= '<p>'
                        . implode("<br>\n", $paragraph)
                        . '</p>';

                    $paragraph = [];
                }

                $html .= $line;

                continue;
            }


            $paragraph[] = $line;
        }


        if (!empty($paragraph)) {

            $html .= '<p>'
                . implode("<br>\n", $paragraph)
                . '</p>';
        }


        return $html;
    }


    private function isHtmlBlock(string $line): bool
    {
        return preg_match(
            '/^<(iframe|div|section|figure|table|video|script|style)/i',
            trim($line)
        ) === 1;
    }


    private function isBlockElement(string $line): bool
    {
        return preg_match(
            '/^<(h1|h2|h3|blockquote|hr|img)/i',
            $line
        ) === 1;
    }
}
