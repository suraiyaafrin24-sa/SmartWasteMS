@extends('layouts.waste_collector')

@section('title', 'Live Field Map')

@section('content')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Live Field Map</h1>
            <p class="text-sm font-medium text-gray-500">Visual navigation for your active pickup route.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('waste_collector.live_route') }}" class="bg-white border border-gray-100 text-gray-900 text-[10px] font-black py-2.5 px-6 rounded-xl hover:bg-gray-50 transition flex items-center gap-2 shadow-sm uppercase tracking-wide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                List View
            </a>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white rounded-[32px] p-4 border border-gray-100 shadow-xl overflow-hidden mb-8">
        <div id="map" style="height: 600px;" class="rounded-2xl z-10"></div>
    </div>

    <!-- Route Details (Conditional) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($activeRoute->take(3) as $request)
        <div class="bg-white rounded-2xl p-5 border border-gray-50 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">{{ $loop->iteration }}</div>
            <div>
                <h4 class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $request->address }}</h4>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $request->user->name }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Dhaka default view
        var map = L.map('map').setView([23.8103, 90.4125], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Current Location Marker
        var userMarker = L.circleMarker([0,0], {
            radius: 10,
            fillColor: "#2D5CFE",
            color: "#fff",
            weight: 3,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);

        function onLocationFound(e) {
            userMarker.setLatLng(e.latlng);
            map.setView(e.latlng, 15);
            userMarker.bindPopup("<b>You are here</b>").openPopup();
        }

        map.on('locationfound', onLocationFound);
        map.locate({setView: true, maxZoom: 16});

        // Define mock locations for demonstration (Geocoding would be real-time in production)
        var locations = [
            {lat: 23.8150, lng: 90.4150, name: 'Pickup Stop #1', address: 'Banani Area'},
            {lat: 23.8050, lng: 90.4050, name: 'Pickup Stop #2', address: 'Gulshan 2'},
            {lat: 23.8200, lng: 90.4200, name: 'Pickup Stop #3', address: 'Mohakhali'}
        ];

        locations.forEach(function(loc, index) {
            var marker = L.marker([loc.lat, loc.lng]).addTo(map);
            marker.bindPopup("<b>" + loc.name + "</b><br>" + loc.address).openPopup();
        });

        // Add a route line
        var polyline = L.polyline(locations.map(l => [l.lat, l.lng]), {color: '#2D5CFE', weight: 4, opacity: 0.6, dashArray: '10, 10'}).addTo(map);
        
        // Zoom to fit markers
        var group = new L.featureGroup(locations.map(l => L.marker([l.lat, l.lng])));
        map.fitBounds(group.getBounds().pad(0.1));
    </script>
@endsection
