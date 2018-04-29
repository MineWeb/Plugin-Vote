<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div class="container-fluid">
    <h3 style="text-align: center">Meilleurs voteurs de cette année (<?= date(Y) ?>)</h3>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php $i = 0; foreach($months as $var) { $i++; ?>
            <li class="<?php if($i == $thism){ ?>active<?php } ?>">
                <a href="#<?= $var ?>" data-toggle="tab" style="text-transform:capitalize;" aria-expanded="<?php if($i == $thism){ ?>true<?php } else { ?>false<?php } ?>">
                    <?= $var ?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php $im = 0; foreach($months as $var) { $im++; ?>
                <div class="tab-pane <?php if($im == $thism): echo "active"; endif; ?>" id="<?= $var ?>">
                    <?php if(empty($this_year[$im])) { ?>
                        <?php if($im > $thism) { ?>
                            <h3 style="text-align: center; padding: 30px">Nous ne sommes pas encore en <?= $var ?> <?= date(Y) ?>.</h3>
                        <?php } else { ?>
                            <h3 style="text-align: center; padding: 30px">Il n'y a pas eu de voteur en <?= $var ?> <?= date(Y) ?>.</h3>
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
                                    <tr>
                                        <td>#<?= $itop ?></td>
                                        <td><?= $value['Vote']['username'] ?></td>
                                        <td><?= $value[0]['count'] ?> vote<?php if($value[0]['count'] < 2){ } else { echo 's';} ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <br>
    <h3 style="text-align: center">Meilleurs voteurs de l'année dernière (<?= date(Y) - 1 ?>)</h3>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <?php $i = 0; foreach($months as $var) { $i++; ?>
            <li class="<?php if($i == "1"){ ?>active<?php } ?>">
                <a href="#last-<?= $var ?>" data-toggle="tab" style="text-transform:capitalize;" aria-expanded="<?php if($i == "1"){ ?>true<?php } else { ?>false<?php } ?>">
                    <?= $var ?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php $im = 0; foreach($months as $var) { $im++; ?>
                <div class="tab-pane <?php if($im == "1"): echo "active"; endif; ?>" id="last-<?= $var ?>">
                    <?php if(empty($last_year[$im])) { ?>
                        <h3 style="text-align: center; padding: 30px">Il n'y a pas eu de voteur en <?= $var ?> <?= date(Y)-1 ?>.</h3>
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
                                    <tr>
                                        <td>#<?= $itop ?></td>
                                        <td><?= $value['Vote']['username'] ?></td>
                                        <td><?= $value[0]['count'] ?> vote<?php if($value[0]['count'] < 2){ } else { echo 's';} ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>


    <h3 style="text-align: center; margin-top:50px;">Statistique</h3>

<?php
for($i = 0; $i < 12; $i++)
{
    $nbr_this_year[$i] = 0;
    $nbr_last_year[$i] = 0;
    foreach($this_year[$i] as $count){
    $nbr_this_year[$i-1]++;
    }
    foreach($last_year[$i] as $count){
    $nbr_last_year[$i-1]++;
    }
}
$maxvoteur_this_year = array_sum($nbr_this_year);

$i = 0;
foreach($months as $var)
{
    $i++;
    $percent_this_year[$i] = $nbr_this_year[$i-1]/$maxvoteur_this_year * 100;
}

$maxvoteur_last_year = array_sum($nbr_last_year);

$i = 0;
foreach($months as $var)
{
    $i++;
    $percent_last_year[$i] = $nbr_last_year[$i-1]/$maxvoteur_last_year * 100;
}
?>


<div class="col-md-6">
    <div id="this-year-comparator"></div>
</div>
<div class="col-md-6">
    <div id="last-year-comparator"></div>
</div>

<div class="col-md-12" style="margin-top:50px;">
    <div id="years-comparator"></div>
</div>
<script>

Highcharts.chart('this-year-comparator',
{
	chart:
	{
		plotBackgroundColor: null,
		plotBorderWidth: null,
		plotShadow: false,
		type: 'pie'
	},
	title:
	{
		text: 'Comparaison du nombre de voteurs <?= date('Y') ?>'
	},
	tooltip:
	{
		pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	},
	plotOptions:
	{
		pie:
		{
			allowPointSelect: true,
			cursor: 'pointer',
			dataLabels:
			{
				enabled: true,
				format: '<b>{point.name}</b>: {point.percentage:.1f} %',
				style:
				{
					color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
				}
			}
		}
	},
	series: [
	{
		name: 'Brands',
		colorByPoint: true,
		data: [
<?php
$i = 0;
foreach($percent_this_year as $val)
{
    $i++;
    echo "{";
    echo "name: '{$months[$i-1]}',";
    if($i == "1"){
        echo "y: {$val},";
        echo "sliced: true,";
        echo "selected: true";
    } else {
        echo "y: {$val}";
    }
    if($i == "12"){
         echo "}";
    } else {
    echo "},";
    }
}

?>]
	}]
});

Highcharts.chart('last-year-comparator',
{
	chart:
	{
		plotBackgroundColor: null,
		plotBorderWidth: null,
		plotShadow: false,
		type: 'pie'
	},
	title:
	{
		text: 'Comparaison du nombre de voteurs <?= date('Y')-1 ?>'
	},
	tooltip:
	{
		pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	},
	plotOptions:
	{
		pie:
		{
			allowPointSelect: true,
			cursor: 'pointer',
			dataLabels:
			{
				enabled: true,
				format: '<b>{point.name}</b>: {point.percentage:.1f} %',
				style:
				{
					color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
				}
			}
		}
	},
	series: [
	{
		name: 'Brands',
		colorByPoint: true,
		data: [
<?php
$i = 0;
foreach($percent_last_year as $val)
{
    $i++;
    echo "{";
    echo "name: '{$months[$i-1]}',";
    if($i == "1"){
        echo "y: {$val},";
        echo "sliced: true,";
        echo "selected: true";
    } else {
        echo "y: {$val}";
    }
    if($i == "12"){
         echo "}";
    } else {
    echo "},";
    }
}

?>]
	}]
});

Highcharts.chart('years-comparator', {
  chart: {
    type: 'line'
  },
  title: {
    text: 'Comparaison du nombre de voteurs <?= date('Y')-1 ?> et <?= date('Y') ?>'
  },
  xAxis: {
    categories: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jui', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec']
  },
  yAxis: {
    title: {
      text: 'Nombre de voteur'
    }
  },
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: false
    }
  },
  series: [{
    name: '<?= date('Y')-1 ?>',
    data: [
        <?php
            $i = 0;
            foreach($nbr_last_year as $last_y){
            $i++;
                if($i == 12){
                    echo $last_y;
                } else {
                    echo $last_y .',';
                }
            }
        ?>
    ]
  }, {
    name: '<?= date('Y') ?>',
    data: [
        <?php
            $i = 0;
            foreach($nbr_this_year as $this_y){
            $i++;
                if($i == 12){
                    echo $this_y;
                } else {
                    echo $this_y .',';
                }
            }
        ?>
    ]
  }]
});
</script>
</div>