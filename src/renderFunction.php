<?php
    namespace Project\src;
    require "../vendor/autoload.php";

    global $twig;
    $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(VIEWS_DIR));     
    function render_view_twig(string $document, array $data = []) {
       global $twig;
        $username = '';
        if(isset($_SESSION['username']))
            $username = $_SESSION['username'];

        $data = [
                    ...$data,
                    "user" => $username
        ];

        echo $twig->render($document, $data);
    }
