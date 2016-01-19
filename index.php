<!DOCTYPE html>
<html>
    <head>
        <!-- JavaScript -->
        <script src="./assets/js/jquery-2.1.4.min.js"></script>
        <script src="./src/scripts/function.js"></script>
    </head>
    <body>
        <?php
        session_start();

        require 'src/classes/App.php';
        $config = include("src/config/config.php");
        try{
            $app = App::init($config);
        }
        catch(Exception $e){
            die('Error: '.$e->getMessage());
        }

        $pages = include("src/config/pages.php");
        $page = 'home';
        if(isset($_GET["page"]) && $_GET["page"] != ''){
            $page = htmlspecialchars($_GET["page"]);
        }
        if(!(file_exists("src/pages/".$page.".php"))){
            $page = 'error';
            $code = '404';
            header($_SERVER["SERVER_PROTOCOL"]." ".$code);
        }
        $title = isset($pages[$page]["title"]) ? $pages[$page]["title"] : '';
        $keywords = isset($pages[$page]["keywords"]) ? $pages[$page]["keywords"] : '';
        $description = isset($pages[$page]["description"]) ? $pages[$page]["description"] : '';

        $flashMessage = '';
        ob_start();
        try{
            include("src/pages/".$page.".php");
        }
        catch(Exception $e){
            $flashMessage = $e->getMessage();
        }
        $content = ob_get_contents();
        ob_end_clean();

        require_once 'src/templates/default.php';
        ?>
    </body>
</html>
