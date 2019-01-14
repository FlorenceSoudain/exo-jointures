<?php
/**
 * Created by PhpStorm.
 * User: sstienface
 * Date: 04/12/2018
 * Time: 11:25
 */


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exojointures";

$connection = new mysqli($servername, $username, $password);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
} else {
    $connection->select_db($dbname);
}

//affiche les élèves et leurs informations
function a()
{
    global $connection;
    $sql = "SELECT * FROM `eleves_informations`, `eleves` WHERE `eleves_informations`.`eleves_id` = `eleves`.`id`";
    $result = $connection->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "ID : " . $row['id'] . " Prenom : " . $row['prenom'] . " Nom : " . $row['nom'] . " Login : " . $row['login'] . " Password : " . $row['password'] . " Age : " . $row['age'] . " Ville : " . $row['ville'] . " Avatar : " . $row['avatar'] . "<br>";
    }
}

//liste des compétences d'un élève et son niveau + niveau auto-évalué
function b()
{
    global $connection;
    $sql2 = "SELECT competences.titre, eleves_competences.niveau, eleves_competences.niveau_ae , eleves.nom, eleves.prenom
FROM competences, eleves_competences, eleves
WHERE competences.id = competences_id AND eleves.id = eleves_id";
    $result = $connection->query($sql2);
    while ($row = $result->fetch_assoc()) {
        echo " Nom : " . $row['nom'] . " Prénom : " . $row['prenom'] . " Compétence : " . $row['titre'] . " Niveau : " . $row['niveau'] . " Niveau auto-évalué : " . $row['niveau_ae'] . "<br>";
    }
}

//données à mettre dans le graphique
$a = "SELECT niveau FROM eleves_competences, competences WHERE competences.id = 1 AND eleves_competences.competences_id = competences.id";
$resultA = $connection->query($a);
while ($row = mysqli_fetch_array($resultA)) {
    $data[] = $row['niveau'];
}

$b = "SELECT niveau FROM eleves_competences, competences WHERE competences.id = 2 AND eleves_competences.competences_id = competences.id";
$resultB = $connection->query($b);
while ($row = mysqli_fetch_array($resultB)) {
    $dataB[] = $row['niveau'];
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h3>Liste des élèves</h3>
<?php a(); ?>
<h3>Comp</h3>
<?php b(); ?>
<div id="container" style="min-width: 310px; max-width: 400px; height: 400px; margin: 0 auto"></div>
<script src="highcharts/highcharts.js"></script>
<script src="highcharts/highcharts-more.js"></script>
<script src="highcharts/exporting.js"></script>
<script src="highcharts/export-data.js"></script>
<script>
    Highcharts.chart('container', {

        chart: {
            polar: true
        },

        title: {
            text: 'Highcharts Polar Chart'
        },

        subtitle: {
            text: 'Also known as Radar Chart'
        },

        pane: {
            startAngle: 0,
            endAngle: 360
        },

        xAxis: {
            tickInterval: 1,
            min: 0,
            max: 10,
            labels: {
                format: '{value}'
            }
        },

        yAxis: {
            min: 0
        },

        plotOptions: {
            series: {
                pointStart: 0,
                pointInterval: 1
            },
            column: {
                pointPadding: 0,
                groupPadding: 0
            }
        },

        series: [{
            type: 'line',
            name: 'CSS',
            data: [<?php echo join($data, ',')?>],
            pointPlacement: 'between'
        }, {
            type: 'line',
            name: 'HTML',
            data: [<?php echo join($dataB, ',')?>]
        }]
    });
</script>
</body>
</html>