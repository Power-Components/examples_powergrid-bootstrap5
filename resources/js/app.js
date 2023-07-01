import './bootstrap';
import '../scss/app.scss'
import flatpickr from "flatpickr";
import Alpine from "alpinejs";

window.flatpickr = flatpickr
window.Alpine = Alpine

import "./../../node_modules/flatpickr/dist/flatpickr.min.css";
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/powergrid.css'

Alpine.start()
