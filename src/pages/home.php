<?php
    //On choisit la table
    $tableName = 'articlescategories';

    //Recuperation du nombre de résultat à afficher par page
    $_SESSION['nbrResultatPage'] = (isset($_SESSION['nbrResultatPage']) && $_SESSION['nbrResultatPage'] != '') ? $_SESSION['nbrResultatPage'] : $config['variables']['nbrResultatPage'];

    if(isset($_GET['nbrResultatPage']) && $_GET['nbrResultatPage'] != '') {
        $_SESSION['nbrResultatPage'] = $_GET['nbrResultatPage'];
    }

    //Recuperation de la page de resultat s'il y en a une
    $pageResultat = (isset($_GET['pageResultat']) && $_GET['pageResultat'] != '') ? $_GET['pageResultat'] : '';

    //Recuperation des articles
    $query = new Core\Db\QueryBuilder();
    $query->select()->from("$tableName");
    if (isset($_GET['queryFields'])) {
        foreach($_GET['queryFields'] as $key=>$val) {
            $query->where($key." LIKE '%".$val."%'");
        }
    }
    if(isset($_GET['ORDERBY']) && $_GET['ORDERBY'] != '')
    {
        $query->order($_GET['ORDERBY']);
    }
    $sql = $query->getSql();
    $entries = App::db()->fetch($sql);

    //Total des entrées
    $nbrResultat = count($entries);

    //Si tous les résulats ne passent pas sur une page, on fais une pagination
    //Pour cela on refait une requête en ne prennant que les résultats voulus
    $start = 0;
    if($nbrResultat > $_SESSION['nbrResultatPage'] && isset($_GET['pageResultat']) && $_GET['pageResultat'] != '')
    {
        //Calcul des limites des etnrées à afficher
        $start = $pageResultat * $_SESSION['nbrResultatPage'] - $_SESSION['nbrResultatPage'];

        //Recuperation des entrées
        $query = new Core\Db\QueryBuilder();
        $query->select()->from($tableName);
        if (isset($_GET['queryFields'])) {
            foreach($_GET['queryFields'] as $key=>$val) {
                $query->where($key." LIKE '%".$val."%'");
            }
        }
        if(isset($_GET['ORDERBY']) && $_GET['ORDERBY'] != '')
        {
            $query->order($_GET['ORDERBY']);
        }
        $sql = $query->limit($start, $_SESSION['nbrResultatPage']);
        $sql = $query->getSql();
        $entries = App::db()->fetch($sql);
    }

?>
<div id="home">
    <div id="selection">
        <div id="nombre">
            Afficher les résultats de <?php echo $start+1; ?> à <?php  echo ($start + $_SESSION['nbrResultatPage'] > $nbrResultat) ? $nbrResultat : $start + $_SESSION['nbrResultatPage'];?> (total de <?php echo  $nbrResultat;?>). Résultats par page :
                <select id="selectionNbrResultatPage" name="listeDeroulante" size="1">
                    <option value="10" <?php echo ($_SESSION['nbrResultatPage'] == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?php echo ($_SESSION['nbrResultatPage'] == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?php echo ($_SESSION['nbrResultatPage'] == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?php echo ($_SESSION['nbrResultatPage'] == 100) ? 'selected' : '' ?>>100</option>
                </select>
        </div>
        <div class="pagination">
            <ul id="pagination-digg">
                <?php
                for($i = 1; $i <= ceil($nbrResultat / $_SESSION['nbrResultatPage']); $i++)
                {
                    echo "<li><a href='?pageResultat=".$i."'>".$i."</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>
    <table>
        <?php
            if(is_file("src/config/fr_CH.php")) {
                $fieldsTitles = include("src/config/fr_CH.php");
            }
            $fieldsNames = App::db()->getFieldsNames($tableName);
            echo "<tr>";
            foreach($fieldsNames as $fieldName) {
                if(isset($fieldsTitles[$fieldName])) {
                    $url = AddQuerystringVar($_SERVER['REQUEST_URI'], "ORDERBY", $fieldName);
                    echo "<th><a href='".$url."'>".$fieldsTitles[$fieldName]."</th>";
                }
                else {
                    echo "<th><a href='?ORDERBY=".$fieldName."'>".$fieldName."</th>";
                }
            }
            echo "</tr>";
            echo "<tr>";
            for($i = 0; $i < count($fieldsNames); $i++) {
                if(isset($_GET['queryFields'])) {
                    $querystring = $_GET['queryFields'];
                    if(array_key_exists($fieldsNames[$i], $querystring)) {
                        echo "<td><input type='text' class='field' name='".$fieldsNames[$i]."' value='".$querystring[$fieldsNames[$i]]."'></td>";
                    }
                    else
                    {
                        echo "<td><input type='text' class='field' name='".$fieldsNames[$i]."'></td>";
                    }
                }
                else
                {
                    echo "<td><input type='text' class='field' name='".$fieldsNames[$i]."'></td>";
                }
            }
            echo "</tr>";

            foreach($entries as $entry){
                echo "<tr>";
                    foreach($fieldsNames as $fieldName) {
                            echo "<td>".$entry[$fieldName]."</td>";
                     }
                echo "</tr>";
            }

        function AddQuerystringVar($url, $key, $value) {
            $urlArray = explode('?',$url);
            $query = '';
            if(isset($urlArray[1]))
                $query = $urlArray[1];
            parse_str($query,$queryArray);
            $queryArray[$key]=$value;
            $queryArray = http_build_query($queryArray);

            return $urlArray[0].'?'.$queryArray;
        }
        ?>
    </table>
</div>