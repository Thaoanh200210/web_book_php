<?php

use function Project\src\render_view_twig;

$router->set404(function() {
    render_view_twig("404NotFound.twig");
});
