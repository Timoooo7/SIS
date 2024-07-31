<?php
use Illuminate\Support\Carbon;
?>
<x-app-layout>
    <x-slot name="header">
        <nav aria-label="breadcrumb w-auto b-0" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <li class="breadcrumb-item font-semibold text-xl text-gray-800 leading-tight" aria-current="page">
                    {{ $title }}</li>
            </ol>
        </nav>
    </x-slot>

    <div class="container py-3 px-10">
        {{-- Tab --}}
        <ul class="nav nav-tabs">
            <li class="nav-item"><a id="tab_1" onclick="show_tab(1)" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a id="tab_2" onclick="show_tab(2)" class="nav-link me-2">Income</a></li>
            <li class="nav-item"><a id="tab_3" onclick="show_tab(3)" class="nav-link">Expense</a></li>
        </ul>

        {{-- Dashboard Tab --}}
        <div id="content_1" class="row pt-0">


        </div>

        {{-- Income Tab --}}
        <div id="content_2" class="row pt-0">


        </div>

        {{-- Expense Tab --}}
        <div id="content_3" class="row pt-0 ">

        </div>
    </div>

    <script>
        var default_tab = sessionStorage.getItem('default_tab');
        window.onload = function() {
            if (default_tab !== null) {
                show_tab(default_tab);
            } else {
                show_tab(1);
            }
        };

        function show_tab(target) {
            const tabs = 3;
            for (let number = 1; number <= tabs; number++) {
                let tab = document.getElementById('tab_' + number);
                let content = document.getElementById('content_' + number);
                if (target != number) {
                    // set tab to deactive
                    tab.setAttribute('class', 'nav-link');
                    // set content to hide
                    content.setAttribute('hidden', '');
                } else {
                    // set tab to active
                    tab.setAttribute('class', 'nav-link active');
                    // set content to show
                    content.removeAttribute('hidden');
                }
            }

            sessionStorage.setItem('default_tab', target);
        }
    </script>
</x-app-layout>
