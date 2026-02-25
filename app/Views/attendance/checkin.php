<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('attendance') ?>">Absensi</a></li>
                <li class="breadcrumb-item active">Check In / Check Out</li>
            </ol>
        </nav>
        <h4 class="mb-1">
            <i class="ri-map-pin-user-line me-2"></i>Check In / Check Out
        </h4>
        <p class="mb-0 text-muted">Lakukan absensi masuk dan keluar dengan GPS & foto selfie</p>
    </div>
</div>

<!-- Status Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <div class="avatar avatar-xl mx-auto">
                            <span class="avatar-initial rounded-circle bg-label-primary" id="statusAvatar">
                                <i class="ri-calendar-check-line ri-36px"></i>
                            </span>
                        </div>
                        <h5 class="mt-3 mb-1" id="statusText">Belum Absen</h5>
                        <small class="text-muted" id="statusDate"><?= date('l, d F Y') ?></small>
                    </div>
                    <div class="col-md-9">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="d-flex flex-column">
                                    <small class="text-muted mb-1">Jam Masuk</small>
                                    <h4 class="mb-0" id="jamMasukDisplay">--:--</h4>
                                    <small class="text-muted" id="statusMasuk">Belum check-in</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column">
                                    <small class="text-muted mb-1">Jam Keluar</small>
                                    <h4 class="mb-0" id="jamKeluarDisplay">--:--</h4>
                                    <small class="text-muted" id="statusKeluar">Belum check-out</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column">
                                    <small class="text-muted mb-1">Total Jam</small>
                                    <h4 class="mb-0" id="totalJamDisplay">0 Jam</h4>
                                    <small class="text-muted">Durasi kerja</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column - Map & GPS -->
    <div class="col-12 col-lg-7">
        <!-- Map Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ri-map-pin-line me-2"></i>Lokasi GPS
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="refreshLocationBtn">
                    <i class="ri-refresh-line me-1"></i> Refresh
                </button>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted d-block">Latitude</small>
                        <strong id="latitudeDisplay">-</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Longitude</small>
                        <strong id="longitudeDisplay">-</strong>
                    </div>
                    <div class="col-6 mt-2">
                        <small class="text-muted d-block">Jarak dari Kantor</small>
                        <strong id="distanceDisplay" class="text-primary">- meter</strong>
                    </div>
                    <div class="col-6 mt-2">
                        <small class="text-muted d-block">Akurasi GPS</small>
                        <strong id="accuracyDisplay" class="text-success">- meter</strong>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0 d-flex align-items-center" id="locationAlert">
                    <i class="ri-information-line me-2"></i>
                    <small>Mengaktifkan GPS untuk mendeteksi lokasi...</small>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card bg-primary-subtle">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="ri-information-line me-1"></i> Informasi Absensi
                </h6>
                <ul class="ps-3 mb-0">
                    <li class="mb-2"><small>Jam kerja: <?= $settings['jam_masuk'] ?> - <?= $settings['jam_keluar'] ?></small></li>
                    <li class="mb-2"><small>Maksimal radius: <?= $settings['max_radius'] ?> meter dari kantor</small></li>
                    <li class="mb-2"><small>Pastikan GPS dan kamera aktif</small></li>
                    <li><small>Foto selfie wajib diambil saat check-in/out</small></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column - Camera & Actions -->
    <div class="col-12 col-lg-5">
        <!-- Camera Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ri-camera-line me-2"></i>Foto Selfie
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="position-relative" style="max-width: 400px; margin: 0 auto;">
                    <video id="video" width="100%" height="300" autoplay style="border-radius: 8px; background: #000; display: none;"></video>
                    <canvas id="canvas" width="400" height="300" style="border-radius: 8px; display: none;"></canvas>
                    <div id="photoPreview" style="display: none;">
                        <img id="capturedPhoto" src="" alt="Captured" style="width: 100%; border-radius: 8px;">
                    </div>
                    <div id="cameraPlaceholder" class="d-flex align-items-center justify-content-center" style="height: 300px; background: #f5f5f5; border-radius: 8px;">
                        <div class="text-center">
                            <i class="ri-camera-off-line" style="font-size: 48px; opacity: 0.3;"></i>
                            <p class="text-muted mt-2 mb-0">Kamera belum aktif</p>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-outline-primary" id="startCameraBtn">
                        <i class="ri-camera-line me-1"></i> Aktifkan Kamera
                    </button>
                    <button type="button" class="btn btn-primary" id="captureBtn" style="display: none;">
                        <i class="ri-camera-3-line me-1"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="retakeBtn" style="display: none;">
                        <i class="ri-restart-line me-1"></i> Ambil Ulang
                    </button>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="checkinBtn" disabled>
                        <i class="ri-login-box-line me-2"></i> Check In
                    </button>
                    <button type="button" class="btn btn-danger btn-lg" id="checkoutBtn" disabled style="display: none;">
                        <i class="ri-logout-box-line me-2"></i> Check Out
                    </button>
                </div>

                <div class="mt-3 text-center">
                    <small class="text-muted" id="buttonHint">
                        Aktifkan GPS dan ambil foto untuk check-in
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Settings from PHP
    const OFFICE_LAT = <?= $settings['office_lat'] ?>;
    const OFFICE_LNG = <?= $settings['office_lng'] ?>;
    const MAX_RADIUS = <?= $settings['max_radius'] ?>;
    const JAM_MASUK = '<?= $settings['jam_masuk'] ?>';

    // Global variables
    let map;
    let userMarker;
    let officeMarker;
    let radiusCircle;
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let capturedPhotoData = null;
    let userLat = null;
    let userLng = null;
    let userDistance = null;

    // Today's attendance data
    const todayAttendance = <?= json_encode($today_attendance) ?>;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
        checkTodayAttendance();
        requestLocation();
    });

    // Initialize Map
    function initMap() {
        map = L.map('map').setView([OFFICE_LAT, OFFICE_LNG], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Office marker (green)
        officeMarker = L.marker([OFFICE_LAT, OFFICE_LNG], {
            icon: L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #28c76f; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="ri-building-4-fill"></i></div>',
                iconSize: [30, 30]
            })
        }).addTo(map);

        officeMarker.bindPopup('<b>Kantor</b><br>Lokasi absensi');

        // Radius circle
        radiusCircle = L.circle([OFFICE_LAT, OFFICE_LNG], {
            radius: MAX_RADIUS,
            color: '#696cff',
            fillColor: '#696cff',
            fillOpacity: 0.1,
            weight: 2,
            dashArray: '5, 10'
        }).addTo(map);
    }

    // Check today's attendance
    function checkTodayAttendance() {
        if (todayAttendance) {
            if (todayAttendance.jam_masuk) {
                document.getElementById('jamMasukDisplay').textContent = todayAttendance.jam_masuk;
                document.getElementById('statusMasuk').textContent = todayAttendance.status === 'hadir' ? 'Tepat waktu' : 'Terlambat';
                document.getElementById('statusMasuk').className = todayAttendance.status === 'hadir' ? 'text-success' : 'text-warning';

                document.getElementById('statusText').textContent = 'Sudah Check-in';
                document.getElementById('statusAvatar').innerHTML = '<i class="ri-checkbox-circle-line ri-36px"></i>';
                document.getElementById('statusAvatar').className = 'avatar-initial rounded-circle bg-label-success';

                // Show checkout button
                document.getElementById('checkinBtn').style.display = 'none';
                document.getElementById('checkoutBtn').style.display = 'block';
                document.getElementById('checkoutBtn').disabled = false;
            }

            if (todayAttendance.jam_keluar) {
                document.getElementById('jamKeluarDisplay').textContent = todayAttendance.jam_keluar;
                document.getElementById('statusKeluar').textContent = 'Selesai';
                document.getElementById('statusKeluar').className = 'text-success';

                document.getElementById('statusText').textContent = 'Check-out Selesai';
                document.getElementById('statusAvatar').className = 'avatar-initial rounded-circle bg-label-info';

                // Calculate total hours
                const masuk = new Date('2000-01-01 ' + todayAttendance.jam_masuk);
                const keluar = new Date('2000-01-01 ' + todayAttendance.jam_keluar);
                const diff = (keluar - masuk) / 1000 / 60 / 60;
                document.getElementById('totalJamDisplay').textContent = diff.toFixed(1) + ' Jam';

                // Disable all buttons
                document.getElementById('checkoutBtn').disabled = true;
                document.getElementById('buttonHint').textContent = 'Anda sudah menyelesaikan absensi hari ini';
            }
        }
    }

    // Request user location
    function requestLocation() {
        if (!navigator.geolocation) {
            showAlert('error', 'Browser Anda tidak support GPS');
            return;
        }

        const locationAlert = document.getElementById('locationAlert');
        locationAlert.innerHTML = '<i class="ri-loader-4-line me-2 spin"></i><small>Mendeteksi lokasi GPS...</small>';
        locationAlert.className = 'alert alert-warning mt-3 mb-0 d-flex align-items-center';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                updateLocationDisplay(userLat, userLng, accuracy);
                updateMap(userLat, userLng);
                checkDistance();

                locationAlert.innerHTML = '<i class="ri-checkbox-circle-line me-2"></i><small>Lokasi GPS berhasil terdeteksi!</small>';
                locationAlert.className = 'alert alert-success mt-3 mb-0 d-flex align-items-center';
            },
            (error) => {
                console.error('GPS Error:', error);
                locationAlert.innerHTML = '<i class="ri-error-warning-line me-2"></i><small>Gagal mendapatkan lokasi. Pastikan GPS aktif dan izin diberikan.</small>';
                locationAlert.className = 'alert alert-danger mt-3 mb-0 d-flex align-items-center';
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }

    // Update location display
    function updateLocationDisplay(lat, lng, accuracy) {
        document.getElementById('latitudeDisplay').textContent = lat.toFixed(6);
        document.getElementById('longitudeDisplay').textContent = lng.toFixed(6);
        document.getElementById('accuracyDisplay').textContent = Math.round(accuracy) + ' meter';

        if (accuracy > 50) {
            document.getElementById('accuracyDisplay').className = 'text-warning';
        } else {
            document.getElementById('accuracyDisplay').className = 'text-success';
        }
    }

    // Update map with user location
    function updateMap(lat, lng) {
        if (userMarker) {
            map.removeLayer(userMarker);
        }

        userMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #ff6384; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="ri-user-location-fill"></i></div>',
                iconSize: [30, 30]
            })
        }).addTo(map);

        userMarker.bindPopup('<b>Lokasi Anda</b>').openPopup();

        // Draw line between user and office
        L.polyline([
            [lat, lng],
            [OFFICE_LAT, OFFICE_LNG]
        ], {
            color: '#696cff',
            weight: 2,
            opacity: 0.6,
            dashArray: '5, 10'
        }).addTo(map);

        // Fit bounds to show both markers
        map.fitBounds([
            [lat, lng],
            [OFFICE_LAT, OFFICE_LNG]
        ], {
            padding: [50, 50]
        });
    }

    // Calculate distance using Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return Math.round(R * c);
    }

    // Check distance and enable/disable buttons
    function checkDistance() {
        if (!userLat || !userLng) return;

        userDistance = calculateDistance(userLat, userLng, OFFICE_LAT, OFFICE_LNG);
        document.getElementById('distanceDisplay').textContent = userDistance + ' meter';

        if (userDistance <= MAX_RADIUS) {
            document.getElementById('distanceDisplay').className = 'text-success';
            checkButtonsEnable();
        } else {
            document.getElementById('distanceDisplay').className = 'text-danger';
            document.getElementById('checkinBtn').disabled = true;
            document.getElementById('checkoutBtn').disabled = true;
            document.getElementById('buttonHint').innerHTML = `<span class="text-danger">Anda terlalu jauh dari kantor (${userDistance}m). Maksimal ${MAX_RADIUS}m.</span>`;
        }
    }

    // Check if buttons can be enabled
    function checkButtonsEnable() {
        const hasLocation = userLat && userLng && userDistance <= MAX_RADIUS;
        const hasPhoto = capturedPhotoData !== null;

        if (todayAttendance && todayAttendance.jam_masuk && !todayAttendance.jam_keluar) {
            if (hasLocation && hasPhoto) {
                document.getElementById('checkoutBtn').disabled = false;
                document.getElementById('buttonHint').innerHTML = '<span class="text-success">Siap untuk check-out!</span>';
            } else {
                document.getElementById('checkoutBtn').disabled = true;
                document.getElementById('buttonHint').textContent = 'Aktifkan GPS dan ambil foto untuk check-out';
            }
        } else if (!todayAttendance || !todayAttendance.jam_masuk) {
            if (hasLocation && hasPhoto) {
                document.getElementById('checkinBtn').disabled = false;
                document.getElementById('buttonHint').innerHTML = '<span class="text-success">Siap untuk check-in!</span>';
            } else {
                document.getElementById('checkinBtn').disabled = true;
                document.getElementById('buttonHint').textContent = 'Aktifkan GPS dan ambil foto untuk check-in';
            }
        }
    }

    // Camera functions
    document.getElementById('startCameraBtn').addEventListener('click', async function() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user'
                }
            });
            video.srcObject = stream;
            video.style.display = 'block';
            document.getElementById('cameraPlaceholder').style.display = 'none';
            document.getElementById('startCameraBtn').style.display = 'none';
            document.getElementById('captureBtn').style.display = 'inline-block';
        } catch (err) {
            showAlert('error', 'Gagal mengaktifkan kamera. Pastikan izin kamera diberikan.');
        }
    });

    document.getElementById('captureBtn').addEventListener('click', function() {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, 400, 300);

        capturedPhotoData = canvas.toDataURL('image/jpeg');
        document.getElementById('capturedPhoto').src = capturedPhotoData;

        video.style.display = 'none';
        canvas.style.display = 'none';
        document.getElementById('photoPreview').style.display = 'block';
        document.getElementById('captureBtn').style.display = 'none';
        document.getElementById('retakeBtn').style.display = 'inline-block';

        video.srcObject.getTracks().forEach(track => track.stop());

        checkButtonsEnable();
    });

    document.getElementById('retakeBtn').addEventListener('click', function() {
        capturedPhotoData = null;
        document.getElementById('photoPreview').style.display = 'none';
        document.getElementById('retakeBtn').style.display = 'none';
        document.getElementById('startCameraBtn').style.display = 'inline-block';
        document.getElementById('cameraPlaceholder').style.display = 'flex';
        checkButtonsEnable();
    });

    document.getElementById('refreshLocationBtn').addEventListener('click', requestLocation);

    // Check-in
    document.getElementById('checkinBtn').addEventListener('click', async function() {
        const result = await Swal.fire({
            title: 'Check-in Sekarang?',
            html: `<p>Waktu: <strong>${new Date().toLocaleTimeString('id-ID')}</strong></p><p>Jarak: <strong>${userDistance} meter</strong></p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28c76f',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Check-in!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Memproses Check-in...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData();
            formData.append('latitude', userLat);
            formData.append('longitude', userLng);

            const photoBlob = await fetch(capturedPhotoData).then(r => r.blob());
            formData.append('photo', photoBlob, 'checkin.jpg');

            const response = await fetch('<?= base_url('attendance/process-checkin') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-in Berhasil!',
                    html: `<p>Status: <span class="badge bg-label-${data.data.status === 'hadir' ? 'success' : 'warning'}">${data.data.status === 'hadir' ? 'Tepat Waktu' : 'Terlambat'}</span></p><p>Jam: <strong>${data.data.jam_masuk}</strong></p>`,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Check-in',
                    text: data.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem'
            });
        }
    });

    // Check-out
    document.getElementById('checkoutBtn').addEventListener('click', async function() {
        const result = await Swal.fire({
            title: 'Check-out Sekarang?',
            html: `<p>Waktu: <strong>${new Date().toLocaleTimeString('id-ID')}</strong></p><p>Jarak: <strong>${userDistance} meter</strong></p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff4757',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Check-out!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        Swal.fire({
            title: 'Memproses Check-out...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const formData = new FormData();
            formData.append('latitude', userLat);
            formData.append('longitude', userLng);

            const photoBlob = await fetch(capturedPhotoData).then(r => r.blob());
            formData.append('photo', photoBlob, 'checkout.jpg');

            const response = await fetch('<?= base_url('attendance/process-checkout') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-out Berhasil!',
                    html: `<p>Jam Keluar: <strong>${data.data.jam_keluar}</strong></p><p>Total Jam: <strong>${data.data.total_jam}</strong></p>`,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Check-out',
                    text: data.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem'
            });
        }
    });

    function showAlert(type, message) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Berhasil!' : 'Error!',
            text: message
        });
    }
</script>

<style>
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .spin {
        animation: spin 1s linear infinite;
    }
</style>

<?= $this->endSection() ?>