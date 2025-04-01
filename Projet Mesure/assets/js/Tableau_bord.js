$(function(){

    let dateRangePicker1 = document.getElementById('dateRangePicker1');
    let pickerRange1 = new Lightpick({
        field: dateRangePicker1,
        singleDate: false,
        onSelect: function(start, end){
            let str = '';
            str += start ? start.format('Do MMMM YYYY') + ' to ' : '';
            str += end ? end.format('Do MMMM YYYY') : '...';
            dateRangePicker1.value = str;
        }
    });

    let dateRangePicker2 = document.getElementById('dateRangePicker2');
    let pickerRange2 = new Lightpick({
        field: dateRangePicker2,
        singleDate: false,
        onSelect: function(start, end){
            let str = '';
            str += start ? start.format('Do MMMM YYYY') + ' to ' : '';
            str += end ? end.format('Do MMMM YYYY') : '...';
            dateRangePicker2.value = str;
        }
    });
});



new Chart(document.getElementById("chartjs-bar"), {
  type: "bar",
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: "Last year",
      backgroundColor: window.theme.primary,
      borderColor: window.theme.primary,
      hoverBackgroundColor: window.theme.primary,
      hoverBorderColor: window.theme.primary,
      data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
      barPercentage: .75,
      categoryPercentage: .5
    }, {
      label: "This year",
      backgroundColor: "#dee2e6",
      borderColor: "#dee2e6",
      hoverBackgroundColor: "#dee2e6",
      hoverBorderColor: "#dee2e6",
      data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68],
      barPercentage: .75,
      categoryPercentage: .5
    }]
  },
  options: {
    scales: {
      yAxes: [{
        gridLines: {
          display: false
        },
        stacked: false
      }],
      xAxes: [{
        stacked: false,
        gridLines: {
          color: "transparent"
        }
      }]
    }
  }
});