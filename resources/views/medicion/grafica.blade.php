@section('content')

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Year', 'Sales'],
            ['20/20/20','5 M3'],

          ]);
          var id = "3";
          var options = {
            chart: {
              title: 'Company Performance',
              subtitle: 'Sales, Expenses, and Profit: 2014-2017',
            }
          };

          var chart = new google.charts.Bar(document.getElementById(id));

          chart.draw(data, google.charts.Bar.convertOptions(options));
        }
      </script>
    <body>
      <div id="3" style="width: 800px; height: 500px;"></div>
    </body>


@endsection
