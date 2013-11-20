
<script type="text/javascript">

    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.0', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        // Create our data table out of JSON data loaded from server.
       var data = new google.visualization.DataTable(<?php echo $jsonTable;?>);
       /* var data = new google.visualization.DataTable(
            {
                cols: [{id: 'task', label: 'Task', type: 'string'},
                    {id: 'hours', label: 'Hours per Day', type: 'number'}],
                rows: [{c:[{v: 'Work'}, {v: 11}]},
                    {c:[{v: 'Eat'}, {v: 2}]},
                    {c:[{v: 'Commute'}, {v: 2}]},
                    {c:[{v: 'Watch TV'}, {v:2}]},
                    {c:[{v: 'Sleep'}, {v:7, f:'7.000'}]}
                ]
            },
            0.6
        )


        var data = google.visualization.arrayToDataTable([
            ['x', 'Cats', 'Blanket 1', 'Blanket 2'],
            ['A',   1,       1,           0.5],
            ['B',   2,       0.5,         1],
            ['C',   4,       1,           0.5],
            ['D',   8,       0.5,         1],
            ['E',   7,       1,           0.5],
            ['F',   7,       0.5,         1],
            ['G',   8,       1,           0.5],
            ['H',   4,       0.5,         1],
            ['I',   2,       1,           0.5],
            ['J',   3.5,     0.5,         1],
            ['K',   3,       1,           0.5],
            ['L',   3.5,     0.5,         1],
            ['M',   1,       1,           0.5],
            ['N',   1,       0.5,         1]
        ]);
    */
        console.log(data);
        // Create and draw the visualization.

        var options = {
            title: 'User Transaction Statistics',
            is3D: 'true',
            curveType: "function",
            vAxis: {minValue: 100}
        };
        // Instantiate and draw our chart, passing in some options.
        // Do not forget to check your div ID
        var chart = new google.visualization.LineChart(document.getElementById('chart_div_<?php echo $count;?>'));
        chart.draw(data, options);
        $(window).resize(function(){
            chart.draw(data, options);
        });
    }
</script>
</head>

  <body>
  <!--Div that will hold the pie chart-->
  <div style=" width = 100%;height = 100%" id="chart_div_<?php echo $count;?>" ></div>
  <?PHP // EXIT;?>