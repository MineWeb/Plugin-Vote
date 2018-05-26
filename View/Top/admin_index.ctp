<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<div class="container-fluid">
    <h3 style="text-align: center">Meilleurs voteurs de cette année (<?= date('Y') ?>)</h3>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php $i = 0; foreach($months as $var) { $i++; ?>
            <li class="<?php if($i == $thism){ ?>active<?php } ?>">
                <a href="#<?= $var ?>" data-toggle="tab" aria-expanded="<?php if($i == $thism){ ?>true<?php } else { ?>false<?php } ?>">
                    <?= $new_months[$i-1] ?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php $im = 0; foreach($months as $var) { $im++; ?>
                <div class="tab-pane <?php if($im == $thism): echo "active"; endif; ?>" id="<?= $var ?>">
                    <?php if(empty($this_year[$im])) { ?>
                        <?php if($im > $thism) { ?>
                            <h3 style="text-align: center; padding: 30px">Nous ne sommes pas encore en <?= $var ?> <?= date('Y') ?>.</h3>
                        <?php } else { ?>
                            <h3 style="text-align: center; padding: 30px">Il n'y a pas eu de voteur en <?= $var ?> <?= date('Y') ?>.</h3>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th class="col-sm-1 col-xs-2">ID</th>
                                    <th class="col-md-2 col-sm-3 col-xs-6">Pseudo</th>
                                    <th class="col-md-2 col-sm-3 col-xs-4">Nombre de votes</th>
                                    <th class="col-md-7 col-sm-4"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $itop = 0; foreach($this_year[$im] as $value) { $itop++; ?>
                                    <?php if($itop > 25) { break; } ?>
                                    <tr>
                                        <td>#<?= $itop ?></td>
                                        <td><?= $value['Vote']['username'] ?></td>
                                        <td><?= $value[0]['count'] ?> vote<?php if($value[0]['count'] < 2){ } else { echo 's';} ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <h4 class="text-center">Il y a eu <?= $vote_this_year[$im][0][0]['count'] ?> vote<?php if($vote_this_year[$im][0][0]['count'] > 1){?>s<?php } ?> ce mois-ci</h4>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <br>
    <h3 style="text-align: center">Meilleurs voteurs de l'année dernière (<?= date('Y') - 1 ?>)</h3>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php $i = 0; foreach($months as $var) { $i++; ?>
            <li class="<?php if($i == "1"){ ?>active<?php } ?>">
                <a href="#last-<?= $var ?>" data-toggle="tab" aria-expanded="<?php if($i == "1"){ ?>true<?php } else { ?>false<?php } ?>">
                    <?= $new_months[$i-1] ?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php $im = 0; foreach($months as $var) { $im++; ?>
                <div class="tab-pane <?php if($im == "1"): echo "active"; endif; ?>" id="last-<?= $var ?>">
                    <?php if(empty($last_year[$im])) { ?>
                        <h3 style="text-align: center; padding: 30px">Il n'y a pas eu de voteur en <?= $var ?> <?= date('Y') - 1 ?>.</h3>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th class="col-sm-1 col-xs-2">ID</th>
                                    <th class="col-md-2 col-sm-3 col-xs-6">Pseudo</th>
                                    <th class="col-md-2 col-sm-3 col-xs-4">Nombre de votes</th>
                                    <th class="col-md-7 col-sm-4"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $itop = 0; foreach($last_year[$im] as $value) { $itop++; ?>
                                    <php if($itop > 25) { break; } ?>
                                    <tr>
                                        <td>#<?= $itop ?></td>
                                        <td><?= $value['Vote']['username'] ?></td>
                                        <td><?= $value[0]['count'] ?> vote<?php if($value[0]['count'] < 2){ } else { echo 's';} ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <h4 class="text-center">Il y a eu <?= $vote_last_year[$im][0][0]['count'] ?> vote<?php if($vote_last_year[$im][0][0]['count'] > 1){?>s<?php } ?> ce mois-ci</h4>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <h3 style="text-align: center; margin-top:50px;">Statistique</h3>

    <div style="margin:30px 0; display: flex;">
    <div class="col-md-6">
        <div id="this-year-votes-comparator"></div>
    </div>
    <div class="col-md-6">
        <div id="last-year-votes-comparator"></div>
    </div>
    </div>
    
    <div class="col-md-6">
        <div id="this-year-comparator"></div>
    </div>
    <div class="col-md-6">
        <div id="last-year-comparator"></div>
    </div>

    <div class="col-md-12" style="margin:30px 0 15px">
        <div id="years-votes-comparator"></div>
    </div>

    <div class="col-md-12" style="margin:15px 0">
        <div id="years-comparator"></div>
    </div>

    <script>
    var this_year = "<?= date('Y') ?>";
    var last_year = "<?= date('Y') - 1 ?>";
    var x_months = [<?php $i = 0; foreach($new_months as $val) { $i++; if($i == 12){ echo "'{$val}'"; } else { echo "'{$val}',"; }} ?>];
    var this_voter_comparator = [<?php $i = 0;foreach($nbr_this_year as $this_y){$i++;if($i == 12){echo $this_y;} else {echo $this_y .',';}} ?>];
    var last_voter_comparator = [<?php $i = 0;foreach($nbr_last_year as $last_y){$i++;if($i == 12){echo $last_y;} else {echo $last_y .',';}} ?>];
    var this_year_comparator_votes = [<?php $i = 0; foreach($nbr_vote_this_year as $this_y){ $i++; if($i == 12){ echo $this_y; } else { echo $this_y .','; }} ?>];
    var last_year_comparator_votes = [<?php $i = 0; foreach($nbr_vote_last_year as $last_y){ $i++; if($i == 12){ echo $last_y; } else { echo $last_y .','; }} ?>];
    var this_voter_comparator_month = [<?php $i = 0;foreach($percent_this_year as $val){$i++;echo "{";echo "name: '{$new_months[$i-1]}',";if($i == "1"){echo "y: {$val},";echo "sliced: true,";echo "selected: true";} else {echo "y: {$val}";}if($i == "12"){echo "}";} else {echo "},";}}?>]
    var last_voter_comparator_month = [<?php $i = 0;foreach($percent_last_year as $val){$i++;echo "{";echo "name: '{$new_months[$i-1]}',";if($i == "1"){echo "y: {$val},";echo "sliced: true,";echo "selected: true";} else {echo "y: {$val}";}if($i == "12"){echo "}";} else {echo "},";}}?>]

    //COMPARAISON VOTE THIS
    Highcharts.chart("this-year-votes-comparator",{chart:{type:"area"},title:{text:"Comparaison du nombre de votes "+this_year},xAxis:{categories:x_months},yAxis:{title:{text:"Nombre de votes"}},tooltip:{pointFormat:"{series.name} : <b>{point.y:,.0f}</b>"},plotOptions:{area:{marker:{enabled:!1,symbol:"circle",radius:2,states:{hover:{enabled:!0}}}}},series:[{name:"Votes en "+this_year,data:this_year_comparator_votes}]});
    //COMPARAISON VOTE LAST 
    Highcharts.chart("last-year-votes-comparator",{chart:{type:"area"},title:{text:"Comparaison du nombre de votes "+last_year},xAxis:{categories:x_months},yAxis:{title:{text:"Nombre de votes"}},tooltip:{pointFormat:"{series.name} : <b>{point.y:,.0f}</b>"},plotOptions:{area:{marker:{enabled:!1,symbol:"circle",radius:2,states:{hover:{enabled:!0}}}}},series:[{name:"Votes en "+last_year,data:last_year_comparator_votes}]});
    //COMPARAISON VOTE LAST ET THIS
    Highcharts.chart("years-votes-comparator",{chart:{type:"column"},title:{text:"Comparaison du nombre de votes "+last_year+" et "+this_year},xAxis:{categories:x_months,crosshair:!0},yAxis:{min:0,title:{text:"Nombre de votes"}},tooltip:{pointFormat:'<br><tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y:.1f}</b></td></tr>',footerFormat:"</table>",shared:!0,useHTML:!0},plotOptions:{column:{pointPadding:.2,borderWidth:0}},series:[{name:last_year,data:last_year_comparator_votes},{name:this_year,data:this_year_comparator_votes}]});
    //COMPARAISON VOTER MONTH THIS YEAR
    Highcharts.chart("this-year-comparator",{chart:{plotBackgroundColor:null,plotBorderWidth:null,plotShadow:!1,type:"pie"},title:{text:"Comparaison du nombre de voteurs "+this_year},tooltip:{pointFormat:"<b>{point.percentage:.1f}%</b>"},plotOptions:{pie:{allowPointSelect:!0,cursor:"pointer",dataLabels:{enabled:!0,format:"<b>{point.name}</b>: {point.percentage:.1f} %",style:{color:Highcharts.theme&&Highcharts.theme.contrastTextColor||"black"}}}},series:[{name:"Brands",colorByPoint:!0,data:this_voter_comparator_month}]});
    //COMPARAISON VOTER MONTH LAST YEAR
    Highcharts.chart("last-year-comparator",{chart:{plotBackgroundColor:null,plotBorderWidth:null,plotShadow:!1,type:"pie"},title:{text:"Comparaison du nombre de voteurs "+last_year},tooltip:{pointFormat:"<b>{point.percentage:.1f}%</b>"},plotOptions:{pie:{allowPointSelect:!0,cursor:"pointer",dataLabels:{enabled:!0,format:"<b>{point.name}</b>: {point.percentage:.1f} %",style:{color:Highcharts.theme&&Highcharts.theme.contrastTextColor||"black"}}}},series:[{name:"Brands",colorByPoint:!0,data:last_voter_comparator_month}]});
    //COMPARAISON VOTER LAST ET THIS
    Highcharts.chart("years-comparator",{chart:{type:"line"},title:{text:"Comparaison du nombre de voteurs "+last_year+" et "+this_year},xAxis:{categories:["Jan","Fev","Mar","Avr","Mai","Juin","Jui","Aou","Sep","Oct","Nov","Dec"]},yAxis:{title:{text:"Nombre de voteur"}},plotOptions:{line:{dataLabels:{enabled:!0},enableMouseTracking:!1}},series:[{name:last_year,data:last_voter_comparator},{name:this_year,data:this_voter_comparator}]});
    </script>
</div>
