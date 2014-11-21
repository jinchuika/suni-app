google.load("visualization", "1", {packages:["corechart"]});
google.load('visualization', '1', {packages:['table']});
google.load("visualization", "1", {packages: ["timeline"]});

function drawChart_line(dato, objetivo, titulo) {
  var data = new google.visualization.arrayToDataTable(dato);

  var options = {
    title: titulo,
    pointSize: 5
  };

  var chart = new google.visualization.LineChart(document.getElementById(objetivo));
  chart.draw(data, options);
}

function drawChart_table(dato, objetivo, titulo) {

  var data = new google.visualization.DataTable();
  console.log("long: ", dato[0].length);
  for (var i = 0; i < dato[0].length; i++) {
    if(i==0){
      data.addColumn('string', dato[0][i]);
    }
    else{
      data.addColumn('number', dato[0][i]);
    }
  };
  dato.splice(0,1);
  data.addRows(dato);
  var options = {
    title: titulo,
    pointSize: 5
  };
  var table = new google.visualization.Table(document.getElementById(objetivo));
  table.draw(data, {showRowNumber: true});
}

function drawChart_pie(dato, objetivo, titulo) {
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Datos');
  data.addColumn('number', 'Cantidad');
  for (var i = 0; i < dato[0].length; i++) {
    data.addRow([String(dato[i][0]), Number(dato[i][1])]);
  };
  var options = {
    title: titulo,
    legend: {position: 'labeled'},
    backgroundColor: { fill:'transparent' },
    chartArea:
    {left:0,top:0,width:"80%",height:"80%"},
  };

  var chart = new google.visualization.PieChart(document.getElementById(objetivo));
  chart.draw(data, options);
}

function adjuntar_promedio (datos_entrada, cabeza) {
  /*
  Adjunta los datos después del primer parámetro cuando la cabeza es 1
  */
  datos_entrada[0].splice(cabeza,0,"Promedio");
  for (var i = cabeza; i < datos_entrada.length; i++) {
    var suma = 0, tot= 0;
    for(var e = cabeza; e < datos_entrada[i].length;e++){
      suma = suma + datos_entrada[i][e];
      tot = e;
    }
    var prom = suma/(tot);
    datos_entrada[i].splice(cabeza,0,prom);
  };
  return datos_entrada;
}

function drawChart_interval(dato, objetivo, titulo) {
  dato = adjuntar_promedio(dato, 1);
  var data = new google.visualization.arrayToDataTable(dato);
  for(var i = 2; i < dato[0].length; i++){
    data.setColumnProperty(i,'role','interval');
  }
  
  var options_lines = {
    title: titulo,
    pointSize: 5,
    curveType:'function',
    series: [{'color': '#113AcA'}],
    lineWidth: 2,
    intervals: { 'style':'line' },
  };
  var chart_lines = new google.visualization.LineChart(document.getElementById(objetivo));
  chart_lines.draw(data, options_lines);
}

function drawChart_line_wrap(dato, objetivo, objetivo_control, titulo, vAxisTitle, hAxisTitle) {
  var data = new google.visualization.arrayToDataTable(dato);
  var columnsTable = new google.visualization.DataTable();
  columnsTable.addColumn('number', 'colIndex');
  columnsTable.addColumn('string', 'colLabel');
  var initState= {selectedValues: []};
  for (var i = 1; i < data.getNumberOfColumns(); i++) {
    columnsTable.addRow([i, data.getColumnLabel(i)]);
    initState.selectedValues.push(data.getColumnLabel(i));
  }

  var chart = new google.visualization.ChartWrapper({
    chartType: 'LineChart',
    containerId: objetivo,
    dataTable: data,
    options: {
      title: titulo,
      pointSize: 5,
      vAxis: {
        title: vAxisTitle,
        minValue: 0
      },
      hAxis: {
        title: hAxisTitle,
      },
      animation:{
        duration: 1000,
        easing: 'out',
      },
    }
  });

  chart.draw();

  var columnFilter = new google.visualization.ControlWrapper({
    controlType: 'CategoryFilter',
    containerId: objetivo_control,
    dataTable: columnsTable,
    options: {
      filterColumnLabel: 'colLabel',
      ui: {
        label: 'Líneas',
        allowTyping: true,
        allowMultiple: true,
        selectedValuesLayout: 'belowStacked'
      }
    },
    state: initState
  });

  google.visualization.events.addListener(columnFilter, 'statechange', function () {
    var state = columnFilter.getState();
    var row;
    var columnIndices = [0];
    for (var i = 0; i < state.selectedValues.length; i++) {
      row = columnsTable.getFilteredRows([{column: 1, value: state.selectedValues[i]}])[0];
      columnIndices.push(columnsTable.getValue(row, 0));
    }
        // sort the indices into their original order
        columnIndices.sort(function (a, b) {
          return (a - b);
        });
        chart.setView({columns: columnIndices});
        chart.draw();
      });
  columnFilter.draw();

}


function crear_csv (datos_entrada) {
  var data = new google.visualization.arrayToDataTable(datos_entrada);
  data = dataTableToCSV(data);
  downloadCSV(data);
}
function dataTableToCSV(dataTable_arg) {
  var dt_cols = dataTable_arg.getNumberOfColumns();
  var dt_rows = dataTable_arg.getNumberOfRows();

  var csv_cols = [];
  var csv_out;

  for (var i=0; i<dt_cols; i++) {
    csv_cols.push(dataTable_arg.getColumnLabel(i).replace(/,/g,""));
  }

  csv_out = csv_cols.join(",")+"\r\n";

  for (i=0; i<dt_rows; i++) {
    var raw_col = [];
    for (var j=0; j<dt_cols; j++) {
      raw_col.push(dataTable_arg.getFormattedValue(i, j, 'label').replace(/,/g,""));
    }
    csv_out += raw_col.join(",")+"\r\n";
  }
  return csv_out;
}
function downloadCSV (csv_out) {
  var blob = new Blob([csv_out], {type: 'text/csv;charset=utf-8'});
  var url  = window.URL || window.webkitURL;
  var link = document.createElementNS("http://www.w3.org/1999/xhtml", "a");
  link.href = url.createObjectURL(blob);
  link.download = "filenameOfChoice.csv"; 

  var event = document.createEvent("MouseEvents");
  event.initEvent("click", true, false);
  link.dispatchEvent(event); 
}

function drawChart_timeline(array_datos, objetivo) {
  /*
  array_datos{id, nombre, fecha_inicio, fecha_fin, grupo}
  */
  var container = document.getElementById(objetivo);

  var chart = new google.visualization.Timeline(container);

  var dataTable = new google.visualization.DataTable();

  dataTable.addColumn({ type: 'date', id: 'start' });
  dataTable.addColumn({ type: 'date', id: 'end' });
  dataTable.addColumn({ type: 'string', id: 'content' });
  dataTable.addColumn({ type: 'string', id: 'group' });

  if(array_datos!=null){
    for(var i = 0; i < array_datos.length; i++){
      var fecha_ini = array_datos[i][0][2].split("-");
      fecha_ini = new Date(fecha_ini[0], (fecha_ini[1] - 1), fecha_ini[2]);
      if(array_datos[i][0][3].length > 1){
        var fecha_fin = array_datos[i][0][3].split("-");
        fecha_fin = new Date(fecha_fin[0], (fecha_fin[1] -1), fecha_fin[2]);
      }
      else{
        var fecha_fin = null;
      }
      
      dataTable.addRow([
        fecha_ini,
        fecha_fin,
        array_datos[i][0][1],
        array_datos[i][0][5] ]);
    }
    var options = {
      "selectable": false,
      "showNavigation": true,
      "style": "box"
    };
    options.locale = "es";
    var timeline = new links.Timeline(document.getElementById(objetivo));
    timeline.draw(dataTable, options);
  }
  else{
    alert("Error al cargar los datos");
  }
}