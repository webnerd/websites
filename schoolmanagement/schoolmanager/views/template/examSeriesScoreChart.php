
<script type="text/javascript">

    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.0', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'SUBJECTS');
        data.addColumn('number', 'MARKS');
        /*data.addRows([
            ['Mushrooms', 3],
            ['Onions', 1],
            ['Olives', 1],
            ['Zucchini', 1],
            ['Pepperoni', 2]
        ]);*/
        data.addRows(<?php echo json_encode($marks); ?>);
        // Set chart options
        var options = {'title':'How Much you scored ?'};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div<?php echo $count;?>'));
        chart.draw(data, options);
        $(window).resize(function(){
            chart.draw(data, options);
        });
    }
</script>
</head>

  <body>
  <!--Div that will hold the pie chart-->
  <div style=" width = 100%;height = 100%" id="chart_div<?php echo $count;?>" style="width:400; height:300"></div>