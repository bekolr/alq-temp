@extends('adminlte::page')

@section('title', 'Calendario de Estadías')

@section('content_header')
    <h1>Calendario de Estadías</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <div id="calendario-estadias"></div>
    </div>
</div>

@stop

@section('css')
    {{-- FullCalendar CSS (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <style>
        #calendario-estadias {
            max-width: 100%;
            margin: 0 auto;
        }
    </style>
@endsection

@section('js')
    {{-- FullCalendar JS (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendario-estadias');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                firstDay: 1, // lunes
                height: 'auto',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                // Para que se vean los rangos de estadías
                displayEventTime: false,

                events: '{{ route('estadias.calendario.events') }}',

                // Opcional: tooltip simple con info
                eventDidMount: function (info) {
                    const props = info.event.extendedProps;
                    const titulo = info.event.title;
                    const texto = `${titulo}\nEstado: ${props.estado}\nPago: ${props.estado_pago}`;
                    info.el.setAttribute('title', texto);
                },

                eventClick: function (info) {
                    // Por defecto ya pusimos 'url' en el event, dejo que navegue
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();
        });
    </script>
@endsection
