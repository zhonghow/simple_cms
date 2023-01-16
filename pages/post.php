<?php

$post_id = Post::getPostID($_GET['id']);

require dirname(__DIR__) . "/parts/header.php";
?>

<div class="container mx-auto my-5" style="max-width: 500px;">
    <h1 class="h1 mb-4 text-center"><?= $post_id['title'] ?></h1>
    <?= nl2br($post_id['content']) ?>

    <!-- <?php
            $paragraphs = preg_split('/\n\s*\n/', $post_id['content']);
            foreach ($paragraphs as $string) {
                echo '<p>' . $string . '</p>';
            }
            ?> -->

    <div class="text-center mt-3">
        <a href="/" class="btn btn-link btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
</div>


<?php
require dirname(__DIR__) . "/parts/footer.php";
?>