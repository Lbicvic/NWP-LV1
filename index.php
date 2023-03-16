<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    include ("./DataController.php");
    $dataController = new DataController('http://stup.ferit.hr/index.php/zavrsni-radovi/page/3');
    //poziv funkcije kako bi se dohvatili svi potrebni podaci (oib, link, tekst i naziv rada)
    $fetchedData = $dataController->fetchData();

    $rad = new DiplomskiRadovi(array(
        'naziv_rada' => "",
        'tekst_rada' => "",
        'link_rada' => "",
        'oib_tvrtke' => "")
    );
    // za svaki oib ($fetchedData[0]) se kreira novi objekt s pripadajućim podacima, zatim se spremaju u bazu podataka
    for($i=0; $i<count($fetchedData[0]);$i++){
    
        $rad->create(array(
            'naziv_rada' => $fetchedData[2][$i],
            'tekst_rada' => $fetchedData[3][$i],
            'link_rada' => $fetchedData[1][$i],
            'oib_tvrtke' => $fetchedData[0][$i]
        ));
    $rad->save();
    }
    $rad->read();
    ?>
</body>
</html>