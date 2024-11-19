<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="height:100%">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Icon Logo -->
    <link rel="icon" href="/images/logo.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Chart.Js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased"
    style="overflow-x: hidden;background-position: center; min-height: 100vh; background: #e6efff ;">

    @if (Auth::user())
        {{-- Navbar --}}
        @include('layouts.staff.navigation')
        {{-- Sidenav --}}
        @include('layouts.staff.offcanvas')
    @endif

    <!-- Page Heading -->
    @if (isset($header))
        <?php
        // Error validation trigger toast
        if (!empty($errors->all())) {
            $err_messages = '';
            foreach ($errors->all() as $err) {
                $err_messages .= ' ' . $err;
            }
            session()->put('notif', ['type' => 'warning', 'message' => $err_messages]);
        }
        ?>

        {{-- Notification Toast --}}
        @if (session()->has('notif'))
            <?php
            // dd(session()->get('notif')['message']);
            if (session()->get('notif')['type'] == 'danger') {
                $notif_type = 'danger';
                $notif_title = 'Danger!';
            } elseif (session()->get('notif')['type'] == 'warning') {
                $notif_type = 'warning';
                $notif_title = 'Warning.';
            } else {
                $notif_type = 'primary';
                $notif_title = 'Information';
            }
            ?>
            <div class="toast-container top-15 start-50 translate-middle-x p-3">
                <div class="toast bg-light border-{{ $notif_type }}" role="alert" aria-live="assertive"
                    aria-atomic="true" data-bs-autohide="true" id="toast_notification">
                    <div class="toast-header bg-{{ $notif_type }}-subtle border-{{ $notif_type }}">
                        <span class="fw-bold text-dark me-auto"><i
                                class="bi bi-app text-{{ $notif_type }} me-2"></i>{{ $notif_title }}</span>
                        <small class="text-body-secondary d-none d-lg-block">{{ now()->format('H:i') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body ">
                        {{ session()->get('notif')['message'] }}
                    </div>
                </div>
            </div>
        @endif
        <header class="border-0 bg-transparent">

            {{-- Page Header --}}
            <div class="mt-4">
                <h2 class="text-primary-emphasis ms-lg-5 ps-lg-5 ms-3">
                    <i class="bi bi-signpost-split border-secondary border-3 border-end me-lg-2 pe-lg-3 me-1 pe-2"></i>
                    {{ $header }}
                    <span class="fs-5 float-end me-5 pe-5 d-none d-lg-block" id="clock"></span>
                    <span class="fs-5 float-end me-3 fw-light d-none d-md-block" id="today_date"></span>
                </h2>
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main>
        {{-- Notif Modal --}}
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow mx-3">
                    <div class="modal-header py-1 ps-3 pe-2">
                        <span class="modal-title fs-5 text-primary-emphasis">
                            <i class="bi bi-exclamation-triangle border-secondary border-end me-2 pe-2"></i>
                            {{ 'Alert Notification' }}
                        </span>
                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal" aria-label="Close"><i
                                class="bi bi-x-lg"></i></button>
                    </div>

                    <div class="modal-body bg-light">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <p class="mb-0" id="notificationMessage"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="button" class="btn btn-sm btn-primary " data-bs-dismiss="modal"
                            aria-label="Close">{{ 'Close' }}</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Confirm Modal --}}
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow mx-3">
                    <div class="modal-header py-1 ps-3 pe-2">
                        <span class="modal-title fs-5 text-primary-emphasis">
                            <i class="bi bi-question-circle border-secondary border-end me-2 pe-2"></i>
                            {{ 'Confirm Action' }}
                        </span>
                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal" aria-label="Close"
                            id="close_button_confirmation_modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <form method="post" id="confirmationForm" action="">
                        @csrf
                        @method('put')
                        <div class="modal-body bg-light">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <p class="mb-0" id="confirmationMessage"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer p-1">
                            <button type="submit" class="btn btn-sm btn-primary ">{{ 'Confirm' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Confirm Submission Modal --}}
        <div class="modal fade" id="confirmSubmissionModal" tabindex="-1" aria-labelledby="confirmSubmissionModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow mx-3">
                    <div class="modal-header py-1 ps-3 pe-2">
                        <span class="modal-title fs-5 text-primary-emphasis">
                            <i class="bi bi-question-circle border-secondary border-end me-2 pe-2"></i>
                            {{ 'Confirm Submission' }}
                        </span>
                        <button type="button" class="btn btn-sm ms-auto" data-bs-dismiss="modal"
                            aria-label="Close"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <p class="mb-0" id="confirmSubmissionMessage"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="submit" class="btn btn-sm btn-primary"
                            id="confirmSubmissionButton">{{ 'Confirm' }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        {{ $slot }}

        <!-- Loading -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="translate-middle position-absolute top-50 start-50">
                <div class="loading" id="loading"></div>
            </div>
        </div>

    </main>
    <script>
        // Confirm Submission Modal
        function confirmSubmission(id, message = 'Are you sure want to submit?') {
            var modal = new bootstrap.Modal(document.getElementById("confirmSubmissionModal"));
            var content = document.getElementById("confirmSubmissionMessage");
            var button = document.getElementById("confirmSubmissionButton");

            content.innerHTML = message;
            button.setAttribute('form', id);
            modal.show();
        }

        // Confirmation Modal
        function confirmation(route, message, return_modal = null) {
            var close_btn = document.getElementById("close_button_confirmation_modal");
            var modal = new bootstrap.Modal(document.getElementById("confirmationModal"));
            var content = document.getElementById("confirmationMessage");
            var form = document.getElementById("confirmationForm");

            form.setAttribute("action", route);
            content.innerHTML = message;
            modal.show();
        }

        // Confirmation Modal
        function notif(message) {
            var modal = new bootstrap.Modal(document.getElementById("notificationModal"));
            var content = document.getElementById("notificationMessage");

            content.innerHTML = message;
            modal.show();
        }

        // Clock
        function updateClock() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            var timeString = hours + ':' + minutes + ':' + seconds;
            document.getElementById('clock').textContent = timeString;
        }
        // Update the clock every second
        setInterval(updateClock, 1000);
        // Initialize the clock immediately
        updateClock();

        //Date
        var today = new Date();
        // Get year, month, and day parts from the date
        var year = today.getFullYear();
        var month = today.toLocaleString('default', {
            month: 'long'
        }); // Get full month name
        var date = today.getDate();
        var day = today.toLocaleString('default', {
            weekday: 'long'
        });

        // Format date as "Month Day, Year"
        var formattedDate = `${day}, ${date} ${month} ${year}`;

        document.getElementById('today_date').textContent = formattedDate;

        // Toggle self
        function toggle_self(self_id) {
            var content = document.getElementById(self_id);
            content.classList.toggle('active');
        }

        // Show Password
        function show_password(input_id, icon_id) {
            var password = document.getElementById(input_id);
            var password_icon = document.getElementById(icon_id);
            if (password.type === "password") {
                password.type = "text";
                password_icon.classList.remove('bi-eye-slash-fill');
                password_icon.classList.add('bi-eye-fill');
            } else {
                password.type = "password";
                password_icon.classList.remove('bi-eye-fill');
                password_icon.classList.add('bi-eye-slash-fill');
            }
        }

        // Array of month names
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        function getMonthName(monthNumber) {
            return monthNames[monthNumber - 1];
        }

        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loadingOverlay');

            function showLoading() {
                loadingOverlay.style.display = 'block';
            }

            function hideLoading() {
                loadingOverlay.style.display = 'none';
            }

            // Handle form submissions
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    showLoading();
                });
            });

            // Handle AJAX requests using jQuery
            $(document).ajaxStart(function() {
                showLoading();
            }).ajaxStop(function() {
                // hideLoading();
            });

            // Handle link clicks
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(event) {
                    if (this.href && this.target !== '_blank') {
                        showLoading();
                        setTimeout(() => {
                            window.location = this.href;
                        }, 500); // Delay the navigation slightly
                        event.preventDefault(); // Prevent immediate navigation
                    }
                });
            });

            // Handle page reloads
            window.addEventListener('beforeunload', function() {
                showLoading();
            });
        });
    </script>
</body>

</html>

<?php
session()->pull('notif');
session()->pull('alert');
?>
