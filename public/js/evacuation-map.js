(function () {
    function escapeHtml(value) {
        var element = document.createElement('div');
        element.textContent = value == null ? '' : value;
        return element.innerHTML;
    }

    function initializeMap(centers) {
        var container = document.getElementById('drmsEvacMap');
        if (!container || typeof window.L === 'undefined') return;

        var map = window.L.map(container).setView([14.5171, 121.2672], 12);
        window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        centers.forEach(function (center) {
            if (!center.latitude || !center.longitude) return;
            var available = Math.max(0, (center.capacity || 0) - (center.current_occupancy || 0));
            window.L.marker([center.latitude, center.longitude])
                .addTo(map)
                .bindPopup('<strong>' + escapeHtml(center.name) + '</strong><br>' + available + ' slots open');
        });
    }

    function start() {
        if (typeof window.L === 'undefined') {
            window.setTimeout(start, 100);
            return;
        }
        fetch('/evac-centers/map-data')
            .then(function (response) { return response.json(); })
            .then(initializeMap)
            .catch(function () {});
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();
