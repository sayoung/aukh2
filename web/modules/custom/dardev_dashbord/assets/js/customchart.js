(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.customChartModule = {
    attach: function (context, settings) {
      // Get the data passed from the form submit handler.
      var data = drupalSettings.dardev_dashbord.datachart.dataCities;

      // Prepare data for Chart.js.
      var labels = Object.keys(data);
      var values = Object.values(data);

      // Generate the chart using Chart.js.
      var ctx = document.getElementById('myChart').getContext('2d');
      var chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Nombre moyen Enotes traité par commune',
            data: values,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
/*const labels = ['Nombre de notes Traité', 'Temps moyen de traitement'];
const values = [drupalSettings.dardev_dashbord.datachart.count, drupalSettings.dardev_dashbord.datachart.avg];

const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'E-notes Statistiques',
      data: values,
      backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'],
      borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
      borderWidth: 1,
      width : 400,
      height : 700
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      },
      xAxes: [{
        barPercentage: 0.2
      }]
    }
  }
});
*/
