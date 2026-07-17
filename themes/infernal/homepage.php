<main class="content">
    <section class="entries">
        <h2>Last entries</h2>
        <?php

        if (count($articles->getEntries()) > 0) {

            foreach ($articles->getEntries() as $entry) {

                echo '<article>';

                echo '<h2>' . $entry->getTitle() . '</h2>';
                echo '<p>Posté le ' . $entry->getFormattedDate() . ' dans ' . implode(', ', $entry->getCategories()) . '</p>';
                echo '<p>' . $entry->getContent() . '</p>';

                echo '</article>';
            }
        } else {

            echo "No entries for the moment!";
        }
        ?>
    </section>
    <nav class="pagination">
        <?php echo $articles->pagination(); ?>
    </nav>
</main>