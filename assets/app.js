import './bootstrap.js';
import './styles/app.css';
import zoomPlugin from 'chartjs-plugin-zoom';


document.addEventListener('chartjs:init', function (event) {
        const Chart = event.detail.Chart;
        Chart.register(zoomPlugin);
});